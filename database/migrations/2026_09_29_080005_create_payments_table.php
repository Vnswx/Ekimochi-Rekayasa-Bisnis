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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method', 50);
            $table->string('transaction_id')->unique();
            $table->string('payment_type', 50)->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('status', 50)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('payment_url')->nullable();
            $table->text('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
