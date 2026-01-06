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
            $table->integer('usage_limit')->nullable()->after('is_public'); // Total number of times voucher can be used
            $table->integer('limit_per_user')->nullable()->after('usage_limit'); // Max usage per user
            $table->integer('used_count')->default(0)->after('limit_per_user'); // Track total usage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['usage_limit', 'limit_per_user', 'used_count']);
        });
    }
};
