<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. New Year Special
        Voucher::updateOrCreate(
            ['code' => 'NURYA2026'],
            [
                'title' => 'New Year Special',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_spend' => 0,
                'is_public' => true,
                'is_active' => true,
                'expires_at' => '2026-01-01',
            ]
        );

        // 2. Wedding Collection Offer
        Voucher::updateOrCreate(
            ['code' => 'WEDDING24'],
            [
                'title' => 'Wedding Collection Offer',
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'min_spend' => 50000,
                'is_public' => true,
                'is_active' => true,
                'expires_at' => '2025-12-31',
            ]
        );
        
        // Delete the garbage one if exists
        Voucher::where('code', 'gubljk')->delete();
    }
}
