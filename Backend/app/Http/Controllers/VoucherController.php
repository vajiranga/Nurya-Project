<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Admin: List all vouchers
    public function index()
    {
        return Voucher::orderBy('created_at', 'desc')->get();
    }

    // Public: List only public active vouchers
    public function publicIndex()
    {
        return Voucher::where('is_public', true)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            // Optionally check usage limit
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                      ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code',
            'title' => 'required|string',
            'discount_type' => 'required|in:fixed,percentage,free_shipping',
            'discount_value' => 'nullable|numeric', 
            'min_spend' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'limit_per_user' => 'nullable|integer',
            'is_public' => 'boolean',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        if (!isset($validated['discount_value'])) {
            $validated['discount_value'] = 0;
        }

        $voucher = Voucher::create($validated);
        return response()->json($voucher, 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code,'.$id,
            'title' => 'required|string',
            'discount_type' => 'required|in:fixed,percentage,free_shipping',
            'discount_value' => 'nullable|numeric',
            'min_spend' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'limit_per_user' => 'nullable|integer',
            'is_public' => 'boolean',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

         if (!isset($validated['discount_value'])) {
             $validated['discount_value'] = 0;
         }

        $voucher->update($validated);
        return response()->json($voucher);
    }

    public function destroy($id)
    {
        Voucher::findOrFail($id)->delete();
        return response()->json(['message' => 'Voucher deleted']);
    }

    public function verify(Request $request)
    {
        $code = $request->input('code');
        $total = $request->input('total', 0);
        // $userId = $request->user() ? $request->user()->id : null; // If auth required

        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired voucher.'], 404);
        }

        // Check global usage limit
        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
             return response()->json(['valid' => false, 'message' => 'This voucher has reached its usage limit.'], 400);
        }
        
        // Check per user limit - Implementation requires order history or user ID tracking. 
        // For now, we just acknowledge the field exists. Actual enforcement typically happens at order creation.

        if ($voucher->min_spend && $total < $voucher->min_spend) {
            return response()->json([
                'valid' => false, 
                'message' => "Sorry, this voucher requires a minimum spend of LKR {$voucher->min_spend}"
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'voucher' => $voucher
        ]);
    }
}
