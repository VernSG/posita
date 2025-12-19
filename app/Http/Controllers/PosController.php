<?php

namespace App\Http\Controllers;

use App\Models\DailyConsignment;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PosController extends Controller
{
    /**
     * Render the POS dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Pos/Dashboard', new \App\ViewModels\PosDashboardViewModel());
    }

    /**
     * Show form to open shop (start daily session).
     */
    public function createOpen(): Response
    {
        return Inertia::render('Pos/OpenShop');
    }

    /**
     * Store new daily shop session (Start Shop).
     */
    public function store(Request $request, \App\Actions\Consignment\StartDailyShopAction $startDailyShopAction)
    {
        $validated = $request->validate([
            'start_cash' => 'required|numeric|min:0',
        ]);

        try {
            $startDailyShopAction->execute($request->user(), $validated['start_cash']);
            return redirect()->route('pos.dashboard')->with('success', 'Shop opened successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show form to close shop (reconcile daily session).
     */
    public function createClose(): Response
    {
        // We might want to pass current session data here using ViewModel or direct query
        // For now preventing closing if not open is handled by UI or middleware ideally.
        $session = DailyConsignment::where('input_by_user_id', Auth::id())
            ->whereNull('closed_at')
            ->whereNotNull('start_cash')
            ->firstOrFail();

        return Inertia::render('Pos/CloseShop', [
            'session' => $session,
        ]);
    }

    /**
     * Update (Close) daily shop session.
     */
    public function update(Request $request, DailyConsignment $dailyConsignment, \App\Actions\Consignment\CloseDailyShopAction $closeDailyShopAction)
    {
        // Ensure we are closing the session record
        if ($dailyConsignment->start_cash === null) {
            abort(404, 'Not a shop session.');
        }

        $validated = $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);

        $closeDailyShopAction->execute($dailyConsignment, $validated['actual_cash']);

        return redirect()->route('pos.dashboard')->with('success', 'Shop closed successfully.');
    }
}
