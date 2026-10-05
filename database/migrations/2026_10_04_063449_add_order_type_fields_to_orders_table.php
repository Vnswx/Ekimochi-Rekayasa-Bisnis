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
            $table->enum('order_type', ['dine_in', 'take_away', 'delivery'])->default('delivery')->after('order_number');
            $table->foreignId('outlet_id')->nullable()->after('order_type')->constrained('outlets')->onDelete('set null');
            $table->foreignId('outlet_table_id')->nullable()->after('outlet_id')->constrained('outlet_tables')->onDelete('set null');
            $table->decimal('delivery_distance', 8, 2)->nullable()->after('shipping_cost')->comment('Jarak pengiriman dalam km');
            $table->timestamp('estimated_ready_time')->nullable()->after('delivery_distance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['outlet_id']);
            $table->dropForeign(['outlet_table_id']);
            $table->dropColumn([
                'order_type',
                'outlet_id',
                'outlet_table_id',
                'delivery_distance',
                'estimated_ready_time',
            ]);
        });
    }
};
