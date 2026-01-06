<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('min_spend', 10, 2)->nullable()->after('discount_value');
            $table->boolean('is_public')->default(false)->after('is_active');
            // We can also allow null discount_value if type is free_shipping, but current structure expects value
            // We will handle free_shipping by setting discount_type = 'free_shipping' and value = 0 if needed.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['min_spend', 'is_public']);
        });
    }
};
