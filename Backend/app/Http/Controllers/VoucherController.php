<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        // Return active vouchers not expired
        return \App\Models\Voucher::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers',
            'title' => 'required',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric',
            'expires_at' => 'nullable|date',
        ]);

        return \App\Models\Voucher::create($validated);
    }
}
