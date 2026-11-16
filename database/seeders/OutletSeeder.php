<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\OutletTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutletSeeder extends Seeder
{
    /**
     * Jumlah meja yang dibuat untuk setiap outlet.
     */
    private const TABLES_PER_OUTLET = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outlets = [
            [
                'name' => 'Ekimochi Senayan City',
                'image' => 'outlets/jakarta-senayan.jpg',
                'address' => 'Senayan City Lt. LG, Jl. Asia Afrika Lot. 19',
                'city' => 'Jakarta Selatan',
                'phone' => '021-72791234',
                'latitude' => -6.225788,
                'longitude' => 106.799478,
                'is_active' => true,
            ],
            [
                'name' => 'Ekimochi Bandung',
                'image' => 'outlets/bandung.jpg',
                'address' => 'Jl. Riau No. 54',
                'city' => 'Bandung',
                'phone' => '022-4231234',
                'latitude' => -6.917464,
                'longitude' => 107.619123,
                'is_active' => true,
            ],
            [
                'name' => 'Ekimochi Surabaya',
                'image' => 'outlets/surabaya.jpg',
                'address' => 'Tunjungan Plaza 3 Lt. 5',
                'city' => 'Surabaya',
                'phone' => '031-5321234',
                'latitude' => -7.263149,
                'longitude' => 112.738728,
                'is_active' => true,
            ],
        ];

        DB::transaction(function () use ($outlets) {
            foreach ($outlets as $outletData) {
                $outlet = Outlet::updateOrCreate(
                    ['name' => $outletData['name']],
                    $outletData
                );

                for ($i = 1; $i <= self::TABLES_PER_OUTLET; $i++) {
                    OutletTable::firstOrCreate(
                        [
                            'outlet_id' => $outlet->id,
                            'table_number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                        ],
                        [
                            'qr_token' => OutletTable::generateQrToken(),
                            'is_active' => true,
                        ]
                    );
                }
            }
        });

        $totalOutlets = Outlet::count();
        $totalTables = OutletTable::count();

        $this->command?->info("Outlet seeded successfully! {$totalOutlets} outlet, {$totalTables} meja.");
    }
}
