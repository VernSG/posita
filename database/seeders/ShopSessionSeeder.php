<?php

namespace Database\Seeders;

use App\Models\DailyConsignment;
use App\Models\Partner;
use App\Models\ShopSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShopSessionSeeder extends Seeder
{
    /**
     * Seed shop sessions: 14 days of closed sessions + 1 open session.
     * Creates realistic transaction data for load testing.
     */
    public function run(): void
    {
        $employees = User::where('role', 'employee')->get();
        $partners = Partner::with('productTemplates')->get();

        if ($employees->isEmpty() || $partners->isEmpty()) {
            $this->command->warn('Skipping ShopSessionSeeder: No employees or partners found.');
            return;
        }

        // Create closed sessions for past 14 days (more history for trends)
        for ($day = 14; $day >= 1; $day--) {
            $employee = $employees->random();
            $date = Carbon::now()->subDays($day);

            $openingCash = rand(100, 300) * 1000; // 100k - 300k

            $session = ShopSession::create([
                'user_id' => $employee->id,
                'opened_at' => $date->copy()->setTime(7, 0),
                'closed_at' => $date->copy()->setTime(17, 0),
                'opening_cash' => $openingCash,
                'status' => 'closed',
            ]);

            // Add consignment items for this session
            $totalIncome = 0;
            $totalProfit = 0;

            // Pick random partners and products
            $selectedPartners = $partners->random(rand(3, 5));

            foreach ($selectedPartners as $partner) {
                if ($partner->productTemplates->isEmpty())
                    continue;

                $products = $partner->productTemplates->random(min(rand(2, 4), $partner->productTemplates->count()));

                foreach ($products as $product) {
                    $qtyInitial = rand(15, 40);
                    $qtySold = rand(8, $qtyInitial);
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

        // Create ONE OPEN session for demo purposes
        $kasir = $employees->first();
        $openingCash = 200000;
        $openSession = ShopSession::create([
            'user_id' => $kasir->id,
            'opened_at' => Carbon::now()->setTime(7, 0),
            'opening_cash' => $openingCash,
            'status' => 'open',
        ]);

        // Add some initial consignment items for today's open session
        $selectedPartners = $partners->random(min(4, $partners->count()));
        foreach ($selectedPartners as $partner) {
            if ($partner->productTemplates->isEmpty())
                continue;

            $products = $partner->productTemplates->random(min(rand(2, 3), $partner->productTemplates->count()));

            foreach ($products as $product) {
                $qtyInitial = rand(15, 25);

                DailyConsignment::create([
                    'shop_session_id' => $openSession->id,
                    'partner_id' => $partner->id,
                    'product_name' => $product->name,
                    'qty_initial' => $qtyInitial,
                    'qty_sold' => 0,
                    'qty_remaining' => $qtyInitial,
                    'base_price' => $product->base_price,
                    'selling_price' => $product->default_selling_price,
                    'markup_percent' => 0,
                    'subtotal_income' => 0,
                ]);
            }
        }
    }
}
