<?php

namespace Database\Seeders;

use App\Models\BoxOrder;
use App\Models\BoxOrderItem;
use App\Models\BoxTemplate;
use App\Models\DailyConsignment;
use App\Models\Partner;
use App\Models\ProductTemplate;
use App\Models\ShopSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedPartners();
        $this->seedBoxTemplates();
        $this->seedShopSessions();
        $this->seedBoxOrders();
    }

    /**
     * Seed users: 1 Admin, 3 Employees
     */
    private function seedUsers(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Posita',
            'email' => 'admin@posita.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Employee/Kasir Users
        $employees = [
            ['name' => 'Rivaldi', 'email' => 'rivaldi@posita.com'],
            ['name' => 'Amar', 'email' => 'amar@posita.com'],
            ['name' => 'Nurita', 'email' => 'nurita@posita.com'],
            ['name' => 'Belva', 'email' => 'belva@posita.com'],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
            ]);
        }
    }

    /**
     * Seed partners with their product templates
     */
    private function seedPartners(): void
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

    /**
     * Seed box templates (Heavy Meal & Snack Box)
     */
    private function seedBoxTemplates(): void
    {
        $templates = [
            // Heavy Meal
            [
                'name' => 'Nasi Box A',
                'type' => 'heavy_meal',
                'price' => 25000,
                'items_json' => ['Nasi Putih', 'Ayam Goreng', 'Sambal', 'Lalapan', 'Kerupuk'],
            ],
            [
                'name' => 'Nasi Box B',
                'type' => 'heavy_meal',
                'price' => 30000,
                'items_json' => ['Nasi Putih', 'Rendang', 'Telur Dadar', 'Sambal', 'Lalapan'],
            ],
            [
                'name' => 'Nasi Box Premium',
                'type' => 'heavy_meal',
                'price' => 40000,
                'items_json' => ['Nasi Putih', 'Ayam Bakar', 'Ikan Goreng', 'Sayur Asem', 'Sambal'],
            ],
            // Snack Box
            [
                'name' => 'Snack Box A',
                'type' => 'snack_box',
                'price' => 15000,
                'items_json' => ['Risoles', 'Pastel', 'Kue Sus', 'Air Mineral'],
            ],
            [
                'name' => 'Snack Box B',
                'type' => 'snack_box',
                'price' => 20000,
                'items_json' => ['Lemper', 'Dadar Gulung', 'Bolu Kukus', 'Onde-onde', 'Teh Kotak'],
            ],
            [
                'name' => 'Snack Box Premium',
                'type' => 'snack_box',
                'price' => 30000,
                'items_json' => ['Risoles Mayo', 'Pastel Ayam', 'Kroket', 'Lumpia', 'Puding', 'Jus Buah'],
            ],
        ];

        foreach ($templates as $template) {
            BoxTemplate::create([
                'name' => $template['name'],
                'type' => $template['type'],
                'price' => $template['price'],
                'items_json' => $template['items_json'],
                'is_active' => true,
            ]);
        }
    }

    /**
     * Seed shop sessions: 3 days of closed sessions + 1 open session
     */
    private function seedShopSessions(): void
    {
        $kasir1 = User::where('email', 'rivaldi@posita.com')->first();
        $kasir2 = User::where('email', 'amar@posita.com')->first();
        $partners = Partner::with('productTemplates')->get();

        // Create closed sessions for past 3 days
        for ($day = 3; $day >= 1; $day--) {
            $kasir = $day % 2 == 0 ? $kasir2 : $kasir1;
            $date = Carbon::now()->subDays($day);

            $openingCash = rand(100, 300) * 1000; // 100k - 300k

            $session = ShopSession::create([
                'user_id' => $kasir->id,
                'opened_at' => $date->copy()->setTime(7, 0),
                'closed_at' => $date->copy()->setTime(17, 0),
                'opening_cash' => $openingCash,
                'status' => 'closed',
            ]);

            // Add consignment items for this session
            $totalIncome = 0;
            $totalProfit = 0;

            // Pick random partners and products
            $selectedPartners = $partners->random(rand(2, 4));

            foreach ($selectedPartners as $partner) {
                $products = $partner->productTemplates->random(rand(1, 3));

                foreach ($products as $product) {
                    $qtyInitial = rand(10, 30);
                    $qtySold = rand(5, $qtyInitial);
                    $qtyRemaining = $qtyInitial - $qtySold;
                    $basePrice = (float) $product->base_price;
                    $sellingPrice = (float) $product->default_selling_price;
                    $subtotalIncome = $qtySold * $sellingPrice;

                    DailyConsignment::create([
                        'shop_session_id' => $session->id,
                        'partner_id' => $partner->id,
                        'product_name' => $product->name,
                        'qty_initial' => $qtyInitial,
                        'qty_sold' => $qtySold,
                        'qty_remaining' => $qtyRemaining,
                        'base_price' => $basePrice,
                        'selling_price' => $sellingPrice,
                        'markup_percent' => 0,
                        'subtotal_income' => $subtotalIncome,
                    ]);

                    $totalIncome += $subtotalIncome;
                    $totalProfit += $qtySold * ($sellingPrice - $basePrice);
                }
            }

            // Update session with closing cash
            $expectedCash = $openingCash + $totalIncome;
            $variance = rand(-10, 10) * 1000; // Random variance
            $actualCash = $expectedCash + $variance;

            $session->update([
                'closing_cash_system' => $expectedCash,
                'closing_cash_actual' => $actualCash,
                'notes' => sprintf(
                    "Total Pendapatan: Rp %s\nTotal Profit: Rp %s\nKas Awal: Rp %s\nKas Akhir Sistem: Rp %s\nKas Akhir Aktual: Rp %s\nSelisih: Rp %s",
                    number_format($totalIncome, 0, ',', '.'),
                    number_format($totalProfit, 0, ',', '.'),
                    number_format($openingCash, 0, ',', '.'),
                    number_format($expectedCash, 0, ',', '.'),
                    number_format($actualCash, 0, ',', '.'),
                    number_format($variance, 0, ',', '.')
                ),
            ]);
        }

        // Create ONE OPEN session for kasir1 (for demo purposes)
        $openingCash = 200000;
        $openSession = ShopSession::create([
            'user_id' => $kasir1->id,
            'opened_at' => Carbon::now()->setTime(7, 0),
            'opening_cash' => $openingCash,
            'status' => 'open',
        ]);

        // Add some initial consignment items for today's open session
        $selectedPartners = $partners->random(3);
        foreach ($selectedPartners as $partner) {
            $products = $partner->productTemplates->random(rand(1, 2));

            foreach ($products as $product) {
                $qtyInitial = rand(15, 25);

                DailyConsignment::create([
                    'shop_session_id' => $openSession->id,
                    'partner_id' => $partner->id,
                    'product_name' => $product->name,
                    'qty_initial' => $qtyInitial,
                    'qty_sold' => 0, // Not sold yet
                    'qty_remaining' => $qtyInitial,
                    'base_price' => $product->base_price,
                    'selling_price' => $product->default_selling_price,
                    'markup_percent' => 0,
                    'subtotal_income' => 0,
                ]);
            }
        }
    }

    /**
     * Seed box orders: completed history + active orders
     */
    private function seedBoxOrders(): void
    {
        $customerNames = [
            'PT Maju Jaya',
            'CV Berkah Abadi',
            'Kantor Kelurahan',
            'SD Negeri 01',
            'Bank BCA Cabang Utama',
            'Bu Rina - Arisan',
            'Pak Ahmad - Pengajian',
            'Ibu Susi - Ulang Tahun',
        ];

        // Completed orders (past)
        for ($i = 0; $i < 5; $i++) {
            $pickupDate = Carbon::now()->subDays(rand(1, 7));

            $order = BoxOrder::create([
                'customer_name' => $customerNames[array_rand($customerNames)],
                'box_template_id' => null, // Custom order
                'quantity' => 1,
                'total_price' => 0,
                'pickup_datetime' => $pickupDate,
                'status' => 'completed',
            ]);

            // Add items
            $items = [
                ['name' => 'Nasi Putih', 'qty' => rand(20, 50), 'price' => 5000],
                ['name' => 'Ayam Goreng', 'qty' => rand(20, 50), 'price' => 15000],
                ['name' => 'Sayur Lodeh', 'qty' => rand(20, 50), 'price' => 5000],
                ['name' => 'Kerupuk', 'qty' => rand(20, 50), 'price' => 2000],
            ];

            $total = 0;
            foreach ($items as $item) {
                $subtotal = $item['qty'] * $item['price'];
                $total += $subtotal;

                BoxOrderItem::create([
                    'box_order_id' => $order->id,
                    'product_name' => $item['name'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_price' => $total]);
        }

        // Active/Pending orders (future)
        $futureOrders = [
            [
                'customer' => 'PT Teknologi Indonesia',
                'days_ahead' => 2,
                'items' => [
                    ['name' => 'Nasi Box Premium', 'qty' => 30, 'price' => 40000],
                    ['name' => 'Air Mineral', 'qty' => 30, 'price' => 5000],
                ],
                'status' => 'pending',
            ],
            [
                'customer' => 'Universitas Siber',
                'days_ahead' => 5,
                'items' => [
                    ['name' => 'Snack Box A', 'qty' => 100, 'price' => 15000],
                ],
                'status' => 'paid',
            ],
            [
                'customer' => 'Bu Rina - Arisan RT',
                'days_ahead' => 1,
                'items' => [
                    ['name' => 'Nasi Uduk', 'qty' => 25, 'price' => 20000],
                    ['name' => 'Ayam Bakar', 'qty' => 25, 'price' => 15000],
                    ['name' => 'Es Teh', 'qty' => 25, 'price' => 3000],
                ],
                'status' => 'paid',
            ],
            [
                'customer' => 'Kantor BPJS',
                'days_ahead' => 3,
                'items' => [
                    ['name' => 'Snack Box B', 'qty' => 50, 'price' => 20000],
                    ['name' => 'Kopi', 'qty' => 50, 'price' => 5000],
                ],
                'status' => 'pending',
            ],
        ];

        foreach ($futureOrders as $orderData) {
            $pickupDate = Carbon::now()->addDays($orderData['days_ahead'])->setTime(10, 0);

            $order = BoxOrder::create([
                'customer_name' => $orderData['customer'],
                'box_template_id' => null,
                'quantity' => 1,
                'total_price' => 0,
                'pickup_datetime' => $pickupDate,
                'status' => $orderData['status'],
            ]);

            $total = 0;
            foreach ($orderData['items'] as $item) {
                $subtotal = $item['qty'] * $item['price'];
                $total += $subtotal;

                BoxOrderItem::create([
                    'box_order_id' => $order->id,
                    'product_name' => $item['name'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_price' => $total]);
        }
    }
}
