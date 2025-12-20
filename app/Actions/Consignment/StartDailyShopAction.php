<?php

namespace App\Actions\Consignment;

use App\Models\DailyConsignment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class StartDailyShopAction
{
    /**
     * Memproses input produk konsinyasi baru ke database.
     */
    public function execute(User $user, array $data): DailyConsignment
    {
        // Validasi Stok Dasar
        if ($data['initial_stock'] <= 0) {
            throw new Exception("Stok awal harus lebih besar dari 0.");
        }

        return DB::transaction(function () use ($user, $data) {
            // Logika Perhitungan Markup
            $basePrice = (float) $data['base_price'];
            $markupPercent = (int) $data['markup']; 
            $sellingPrice = $basePrice + ($basePrice * ($markupPercent / 100));

            // Simpan ke database sesuai skema migration
            return DailyConsignment::create([
                'date'              => now()->toDateString(),
                'partner_id'        => $data['partner_id'],
                'input_by_user_id'  => $user->id,
                'product_name'      => $data['product_name'],
                'initial_stock'     => (int) $data['initial_stock'],
                'remaining_stock'   => (int) $data['initial_stock'],
                'base_price'        => $basePrice,
                'markup_percentage' => $markupPercent,
                'selling_price'     => $sellingPrice,
                'status'            => 'open',
            ]);
        });
    }
}