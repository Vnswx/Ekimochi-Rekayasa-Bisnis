<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Preferensi notifikasi per pengguna (kategori + channel).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_promo')->default(true)->after('avatar_color');
            $table->boolean('notify_order')->default(true)->after('notify_promo');
            $table->boolean('notify_product')->default(true)->after('notify_order');
            $table->boolean('notify_outlet')->default(false)->after('notify_product');
            $table->boolean('notify_email')->default(true)->after('notify_outlet');
            $table->boolean('notify_whatsapp')->default(false)->after('notify_email');
            $table->boolean('notify_push')->default(true)->after('notify_whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_promo',
                'notify_order',
                'notify_product',
                'notify_outlet',
                'notify_email',
                'notify_whatsapp',
                'notify_push',
            ]);
        });
    }
};
