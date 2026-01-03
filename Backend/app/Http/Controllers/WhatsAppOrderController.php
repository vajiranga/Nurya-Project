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
            'address' => 'required|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = \App\Models\Order::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'items_json' => $validated['items'],
            'total_amount' => $total,
            'status' => 'pending',
            'payment_method' => 'whatsapp',
        ]);

        // Format Message
        $message = "*New Order form Nurya Jewellery* 💍\n\n";
        $message .= "*Order ID:* #{$order->id}\n";
        $message .= "*Customer:* {$order->customer_name}\n";
        $message .= "*Phone:* {$order->phone}\n";
        $message .= "*Address:* {$order->address}\n\n";
        $message .= "*Items:*\n";

        foreach ($validated['items'] as $item) {
            $message .= "- {$item['name']} (x{$item['quantity']}): LKR " . number_format($item['price'] * $item['quantity'], 2) . "\n";
        }

        $message .= "\n*Total Amount:* LKR " . number_format($total, 2);

        return response()->json([
            'success' => true,
            'whatsapp_message' => $message,
            'whatsapp_link' => "https://wa.me/?text=" . urlencode($message)
        ]);
    }
}
