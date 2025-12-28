<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\ProductTemplate;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Seed partners with their product templates.
     */
    public function run(): void
    {
        $partnersData = [
            [
                'name' => 'Bu Siti - Gorengan',
                'phone' => '081234567890',
                'address' => 'Jl. Pasar Baru No. 10',
                'products' => [
                    ['name' => 'Bakwan', 'base_price' => 1000, 'selling_price' => 1500],
                    ['name' => 'Tahu Isi', 'base_price' => 1500, 'selling_price' => 2000],
                    ['name' => 'Tempe Goreng', 'base_price' => 1000, 'selling_price' => 1500],
                    ['name' => 'Pisang Goreng', 'base_price' => 2000, 'selling_price' => 2500],
                    ['name' => 'Risoles', 'base_price' => 2500, 'selling_price' => 3500],
                ],
            ],
            [
                'name' => 'Pak Budi - Kue Basah',
                'phone' => '081234567891',
                'address' => 'Jl. Merdeka No. 5',
                'products' => [
                    ['name' => 'Klepon', 'base_price' => 1500, 'selling_price' => 2000],
                    ['name' => 'Onde-onde', 'base_price' => 2000, 'selling_price' => 2500],
                    ['name' => 'Getuk', 'base_price' => 1500, 'selling_price' => 2000],
                    ['name' => 'Lapis Legit', 'base_price' => 3000, 'selling_price' => 4000],
                ],
            ],
            [
                'name' => 'Mbak Ani - Roti',
                'phone' => '081234567892',
                'address' => 'Jl. Kenanga No. 15',
                'products' => [
                    ['name' => 'Roti Coklat', 'base_price' => 3000, 'selling_price' => 4000],
                    ['name' => 'Roti Keju', 'base_price' => 3500, 'selling_price' => 5000],
                    ['name' => 'Roti Srikaya', 'base_price' => 3000, 'selling_price' => 4000],
                    ['name' => 'Donat', 'base_price' => 2500, 'selling_price' => 3500],
                ],
            ],
            [
                'name' => 'Pak Hendra - Snack',
                'phone' => '081234567893',
                'address' => 'Jl. Flamboyan No. 20',
                'products' => [
                    ['name' => 'Pastel', 'base_price' => 2000, 'selling_price' => 3000],
                    ['name' => 'Lumpia', 'base_price' => 2500, 'selling_price' => 3500],
                    ['name' => 'Sosis Solo', 'base_price' => 3000, 'selling_price' => 4000],
                    ['name' => 'Kroket', 'base_price' => 2500, 'selling_price' => 3500],
                ],
            ],
            [
                'name' => 'Bu Dewi - Minuman',
                'phone' => '081234567894',
                'address' => 'Jl. Anggrek No. 8',
                'products' => [
                    ['name' => 'Es Teh Manis', 'base_price' => 2000, 'selling_price' => 3000],
                    ['name' => 'Es Jeruk', 'base_price' => 3000, 'selling_price' => 5000],
                    ['name' => 'Kopi Susu', 'base_price' => 5000, 'selling_price' => 8000],
                    ['name' => 'Es Campur', 'base_price' => 7000, 'selling_price' => 10000],
                ],
            ],
            [
                'name' => 'Toko Berkah - ATK',
                'phone' => '081234567895',
                'address' => 'Jl. Sudirman No. 100',
                'products' => [
                    ['name' => 'Pulpen', 'base_price' => 2000, 'selling_price' => 3000],
                    ['name' => 'Buku Tulis', 'base_price' => 5000, 'selling_price' => 7000],
                    ['name' => 'Pensil', 'base_price' => 1000, 'selling_price' => 2000],
                    ['name' => 'Penghapus', 'base_price' => 1500, 'selling_price' => 2500],
                ],
            ],
            [
                'name' => 'Pak Joko - Jajanan',
                'phone' => '081234567896',
                'address' => 'Jl. Dahlia No. 3',
                'products' => [
                    ['name' => 'Cilok', 'base_price' => 1000, 'selling_price' => 2000],
                    ['name' => 'Cireng', 'base_price' => 1000, 'selling_price' => 2000],
                    ['name' => 'Batagor', 'base_price' => 3000, 'selling_price' => 5000],
                    ['name' => 'Siomay', 'base_price' => 3000, 'selling_price' => 5000],
                ],
            ],
        ];

        foreach ($partnersData as $partnerData) {
            $partner = Partner::create([
                'name' => $partnerData['name'],
                'phone' => $partnerData['phone'],
                'address' => $partnerData['address'],
                'is_active' => true,
            ]);

            foreach ($partnerData['products'] as $product) {
                ProductTemplate::create([
                    'partner_id' => $partner->id,
                    'name' => $product['name'],
                    'base_price' => $product['base_price'],
                    'default_selling_price' => $product['selling_price'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
