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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('promo_id')->nullable()->after('notes');
            $table->string('promo_name')->nullable()->after('promo_id');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('promo_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['promo_id', 'promo_name', 'discount_amount']);
        });
    }
};