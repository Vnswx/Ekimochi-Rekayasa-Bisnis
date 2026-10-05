<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\OutletTable;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outlets = [
            [
                'name' => 'Ekimochi Senayan City',
                'address' => 'Senayan City Lt. LG',
                'city' => 'Jakarta Selatan',
                'phone' => '021-72791234',
                'latitude' => -6.225788,
                'longitude' => 106.799478,
                'is_active' => true,
            ],
            [
                'name' => 'Ekimochi Bandung',
                'address' => 'Jl. Riau No. 54',
                'city' => 'Bandung',
                'phone' => '022-4231234',
                'latitude' => -6.917464,
                'longitude' => 107.619123,
                'is_active' => true,
            ],
            [
                'name' => 'Ekimochi Surabaya',
                'address' => 'Tunjungan Plaza 3',
                'city' => 'Surabaya',
                'phone' => '031-5321234',
                'latitude' => -7.263149,
                'longitude' => 112.738728,
                'is_active' => true,
            ],
        ];

        foreach ($outlets as $outletData) {
            $outlet = Outlet::create($outletData);

            // Buat 10 meja untuk setiap outlet
            for ($i = 1; $i <= 10; $i++) {
                OutletTable::create([
                    'outlet_id' => $outlet->id,
                    'table_number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'qr_token' => OutletTable::generateQrToken(),
                    'is_active' => true,
                ]);
            }
        }
    }
}
