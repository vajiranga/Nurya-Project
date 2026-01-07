<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsAppOrderController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'secondary_phone' => 'nullable|string',
            'address' => 'required|string',
            'special_notes' => 'nullable|string',
            'payment_method' => 'required|string',
            'shipping_fee' => 'required|numeric',
            'coupon_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'nullable',
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $shipping = $validated['shipping_fee'];
        $discount = $validated['discount_amount'] ?? 0;
        $total = $subtotal - $discount + $shipping;

        $order = \App\Models\Order::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'items_json' => $validated['items'],
            'total_amount' => $total,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
        ]);

        // Format Message
        $message = "*New Order from Nurya Jewellery* 💍\n\n";
        $message .= "*Order ID:* #{$order->id}\n";
        $message .= "*Customer:* {$order->customer_name}\n";
        $message .= "*Primary Mobile:* {$order->phone}\n";
        
        if (!empty($validated['secondary_phone'])) {
            $message .= "*Secondary Mobile:* {$validated['secondary_phone']}\n";
        }
        
        $message .= "*Address:* {$order->address}\n";
        
        if (!empty($validated['special_notes'])) {
            $message .= "*Special Notes:* {$validated['special_notes']}\n";
        }

        $message .= "*Payment Method:* " . strtoupper(str_replace('_', ' ', $validated['payment_method'])) . "\n\n";
        
        $message .= "*Items:*\n";

        foreach ($validated['items'] as $item) {
            $sku = str_pad((string)$item['id'], 6, '0', STR_PAD_LEFT);
            $size = isset($item['size']) ? " (Size: {$item['size']})" : "";
            $message .= "- {$item['name']}{$size}\n";
            $message .= "  SKU: #{$sku} | Qty: {$item['quantity']} | LKR " . number_format($item['price'], 2) . "\n";
        }

        $message .= "\n*Subtotal:* LKR " . number_format($subtotal, 2);
        
        if ($discount > 0) {
            $code = $validated['coupon_code'] ?? 'APPLIED';
            $message .= "\n*Discount:* -LKR " . number_format($discount, 2) . " (Code: {$code})";
        }

        $message .= "\n*Shipping:* LKR " . number_format($shipping, 2);
        $message .= "\n*Total Amount:* LKR " . number_format($total, 2);

        if ($validated['payment_method'] === 'bank_transfer') {
             $message .= "\n\n⚠️ *NOTE:* Waiting for bank details and confirmation.";
        }

        $message .= "\n\nPlease confirm my order.";

        return response()->json([
            'success' => true,
            'whatsapp_message' => $message,
            'whatsapp_link' => "https://wa.me/94776819034?text=" . urlencode($message)
        ]);
    }
}
