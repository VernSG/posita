<?php

namespace App\Http\Controllers;

use App\Models\DailyConsignment;
use App\Models\Partner;
use App\Actions\Consignment\StartDailyShopAction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class PosController extends Controller
{

    public function createOpen(): Response
    {
        return Inertia::render('Pos/OpenShop', [
            'partners' => Partner::where('is_active', true)
                ->select(['id', 'name'])
                ->get()
        ]);
    }

    /**
     * Menyimpan data produk konsinyasi baru.
     * Route: pos.store
     */
    public function storeOpen(Request $request, StartDailyShopAction $startDailyShopAction): RedirectResponse
    {
        // Validasi input dari form Vue
        $validated = $request->validate([
            'partner_id'    => 'required|exists:partners,id',
            'product_name'  => 'required|string|max:255',
            'initial_stock' => 'required|integer|min:1',
            'base_price'    => 'required|numeric|min:0',
            'markup'        => 'required|integer|min:0',
        ]);

        try {
            // Menjalankan Action untuk menyimpan data
            $startDailyShopAction->execute($request->user(), $validated);

            // Redirect kembali ke halaman yang sama (atau dashboard) dengan pesan sukses
            // Menggunakan back() memudahkan jika Anda ingin input banyak barang berturut-turut
            return redirect()->back()->with('success', 'Produk konsinyasi berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            // Mengirim pesan error jika logika bisnis di Action gagal
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Method index dan close tetap ada namun dibuat lebih sederhana 
     * jika Anda tidak mengurus bagian penutupan toko.
     */
    public function index(): Response
    {
        return Inertia::render('Pos/Dashboard');
    }
}