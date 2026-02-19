<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // 'bankak' or 'cod' or 'card'
            $table->string('payment_status')->default('pending')->after('payment_method'); // 'pending', 'awaiting_admin_approval', 'verified', 'failed', 'pending_delivery'
            $table->string('transaction_id')->nullable()->unique()->after('payment_status');
            $table->string('receipt_path')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'transaction_id', 'receipt_path']);
        });
    }
};
