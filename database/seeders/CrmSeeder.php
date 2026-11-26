<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Pelanggan contoh untuk halaman CRM (admin/crm).
 *
 * Halaman CRM menghitung: jumlah pelanggan, pelanggan repeat (>= 2 pesanan),
 * total belanja lunas, dan pesanan terakhir -> semuanya diambil dari relasi
 * User::orders() + Payment::status = settlement, jadi pesanan contohnya
 * dibuat oleh OrderSeeder.
 */
class CrmSeeder extends Seeder
{
    /**
     * Password semua akun pelanggan contoh.
     */
    private const PASSWORD = 'password123';

    /**
     * Warna avatar (senada dengan User::generateAvatarColor()).
     */
    private const COLORS = ['FFB3BA', 'C7B3FF', 'B3D9FF', 'B3FFB3', 'FFFFB3', 'FFD4B3'];

    /**
     * @var array<int, array{name: string, username: string, email: string, city: string}>
     */
    private const CUSTOMERS = [
        ['name' => 'Budi Santoso', 'username' => 'budisantoso', 'email' => 'budi.santoso@example.com', 'city' => 'Jakarta Selatan'],
        ['name' => 'Siti Nurhaliza', 'username' => 'sitinurhaliza', 'email' => 'siti.nurhaliza@example.com', 'city' => 'Bandung'],
        ['name' => 'Andi Wijaya', 'username' => 'andiwijaya', 'email' => 'andi.wijaya@example.com', 'city' => 'Surabaya'],
        ['name' => 'Dewi Lestari', 'username' => 'dewilestari', 'email' => 'dewi.lestari@example.com', 'city' => 'Jakarta Pusat'],
        ['name' => 'Rizky Pratama', 'username' => 'rizkypratama', 'email' => 'rizky.pratama@example.com', 'city' => 'Depok'],
        ['name' => 'Putri Ayu Wulandari', 'username' => 'putriayu', 'email' => 'putri.ayu@example.com', 'city' => 'Tangerang'],
        ['name' => 'Agus Setiawan', 'username' => 'agussetiawan', 'email' => 'agus.setiawan@example.com', 'city' => 'Bekasi'],
        ['name' => 'Maya Sari', 'username' => 'mayasari', 'email' => 'maya.sari@example.com', 'city' => 'Bogor'],
        ['name' => 'Fajar Nugroho', 'username' => 'fajarnugroho', 'email' => 'fajar.nugroho@example.com', 'city' => 'Yogyakarta'],
        ['name' => 'Intan Permata', 'username' => 'intanpermata', 'email' => 'intan.permata@example.com', 'city' => 'Semarang'],
        ['name' => 'Bayu Ramadhan', 'username' => 'bayuramadhan', 'email' => 'bayu.ramadhan@example.com', 'city' => 'Malang'],
        ['name' => 'Nadia Kusuma', 'username' => 'nadiakusuma', 'email' => 'nadia.kusuma@example.com', 'city' => 'Denpasar'],
        ['name' => 'Hendra Gunawan', 'username' => 'hendragunawan', 'email' => 'hendra.gunawan@example.com', 'city' => 'Medan'],
        ['name' => 'Lina Marlina', 'username' => 'linamarlina', 'email' => 'lina.marlina@example.com', 'city' => 'Palembang'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::CUSTOMERS as $index => $customer) {
            User::firstOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'username' => $customer['username'],
                    'password' => Hash::make(self::PASSWORD),
                    'role' => 'user',
                    'avatar_color' => self::COLORS[$index % count(self::COLORS)],
                    'email_verified_at' => now(),
                ]
            );
        }

        $total = User::where('role', 'user')->count();

        $this->command?->info("Pelanggan CRM siap: {$total} akun pelanggan (contoh password: ".self::PASSWORD.').');
    }
}
