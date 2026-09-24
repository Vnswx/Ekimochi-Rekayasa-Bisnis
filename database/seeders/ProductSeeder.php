<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Classic',
                'description' => 'Varian rasa klasik favorit dengan buah asli dan cokelat premium',
            ],
            [
                'name' => 'Original Series',
                'description' => 'Koleksi rasa original dengan perpaduan unik khas Ekimochi',
            ],
            [
                'name' => 'Premium',
                'description' => 'Pilihan eksklusif dengan bahan premium dan rasa istimewa',
            ],
            [
                'name' => 'Paket Box',
                'description' => 'Paket box eksklusif untuk berbagai kebutuhan dan acara spesial',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        // Get categories
        $classic = Category::where('name', 'Classic')->first();
        $original = Category::where('name', 'Original Series')->first();
        $premium = Category::where('name', 'Premium')->first();
        $paketBox = Category::where('name', 'Paket Box')->first();

        // Create Products
        $products = [
            // Classic
            [
                'name' => 'Strawberry Classic',
                'sku' => 'MCH-STR-001',
                'category_id' => $classic->id,
                'description' => 'Manis berpadu dengan keasaman yang menyegarkan.',
                'price' => 5000,
                'stock' => 100,
                'unit' => 'pcs',
                'image' => 'strawberry.png',
                'status' => 'active',
            ],
            [
                'name' => 'Matcha Classic',
                'sku' => 'MCH-MTC-002',
                'category_id' => $classic->id,
                'description' => 'Lelehan saus matcha Uji Jepang asli yang pekat.',
                'price' => 5000,
                'stock' => 100,
                'unit' => 'pcs',
                'image' => 'matcha.png',
                'status' => 'active',
            ],
            [
                'name' => 'Chocolate Classic',
                'sku' => 'MCH-CHC-003',
                'category_id' => $classic->id,
                'description' => 'Sangat creamy, manis, dan pekat dengan kelezatan cokelat belgia pilihan.',
                'price' => 5000,
                'stock' => 100,
                'unit' => 'pcs',
                'image' => 'chocolate.png',
                'status' => 'active',
            ],
            // Original Series
            [
                'name' => 'Taro',
                'sku' => 'MCH-TRO-004',
                'category_id' => $original->id,
                'description' => 'Kelembutan krim taro khas Jepang yang meleleh gurih berpadu dengan aroma talas alami.',
                'price' => 6000,
                'stock' => 80,
                'unit' => 'pcs',
                'image' => 'taro.png',
                'status' => 'active',
            ],
            [
                'name' => 'Fruity Mango',
                'sku' => 'MCH-MNG-005',
                'category_id' => $original->id,
                'description' => 'Perpaduan rasa mangga segar dan isian buah pilihan memberikan sensasi rasa manis menyegarkan di lidah.',
                'price' => 6000,
                'stock' => 80,
                'unit' => 'pcs',
                'image' => 'mango.png',
                'status' => 'active',
            ],
            // Premium
            [
                'name' => 'Lotus Biscoff',
                'sku' => 'MCH-LTS-006',
                'category_id' => $premium->id,
                'description' => 'Rasa karamel biskuit Lotus Biscoff renyah dengan selai lezat berpadu sempurna dalam balutan mochi lembut.',
                'price' => 8000,
                'stock' => 50,
                'unit' => 'pcs',
                'image' => 'lotus.png',
                'status' => 'active',
            ],
            // Paket Box
            [
                'name' => 'Petite Box of 4',
                'sku' => 'BOX-PT4-007',
                'category_id' => $paketBox->id,
                'description' => 'Sangat pas untuk dinikmati sendiri atau berdua saat bersantai sore. Bebas pilih 4 varian rasa mochi, termasuk greeting card mini, box pita cantik & eksklusif.',
                'price' => 49000,
                'stock' => 30,
                'unit' => 'box',
                'image' => 'kiekimochi matcha.png',
                'status' => 'active',
            ],
            [
                'name' => 'Delight Box of 6',
                'sku' => 'BOX-DL6-008',
                'category_id' => $paketBox->id,
                'description' => 'Porsi ideal untuk kumpul keluarga atau hantaran spesial di berbagai acara. Bebas pilih 6 varian rasa mochi, free ice gel tahan dingin 3 jam, kartu ucapan custom & pita mewah.',
                'price' => 59000,
                'stock' => 40,
                'unit' => 'box',
                'image' => 'kiekimochi strwberry.png',
                'status' => 'active',
            ],
            [
                'name' => 'Luxury Box of 12',
                'sku' => 'BOX-LX12-009',
                'category_id' => $paketBox->id,
                'description' => 'Kotak besar penuh kebahagiaan untuk pesta, ulang tahun, atau hari raya. Lengkap semua varian rasa (12 pcs), hardbox mewah eksklusif Ekimochi, free gift bag & double ice gel.',
                'price' => 124000,
                'stock' => 20,
                'unit' => 'box',
                'image' => 'kiekimochi coklat.png',
                'status' => 'active',
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }
    }
}
