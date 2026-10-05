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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->after('id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method', 50)->after('order_id');
            $table->string('transaction_id')->after('payment_method')->unique();
            $table->string('payment_type', 50)->after('transaction_id')->nullable();
            $table->decimal('amount', 12, 2)->after('payment_type');
            $table->string('status', 50)->after('amount')->default('pending');
            $table->timestamp('paid_at')->after('status')->nullable();
            $table->text('payment_url')->after('paid_at')->nullable();
            $table->text('midtrans_response')->after('payment_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'order_id',
                'payment_method',
                'transaction_id',
                'payment_type',
                'amount',
                'status',
                'paid_at',
                'payment_url',
                'midtrans_response',
            ]);
        });
    }
};
