<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Inventory Hub - The "Stock Tiles" Workspace
     */
    public function index()
    {
        $inventory = $this->inventoryService->getStockSummary();
        return view('inventory.index', compact('inventory'));
    }

    /**
     * Manual Stock Correction View
     */
    public function showAdjust()
    {
        $inventory = $this->inventoryService->getStockSummary();
        return view('inventory.adjust', compact('inventory'));
    }

    /**
     * Manual Stock Correction Process
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'grain_type' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'adjustment_type' => 'required|string',
            'reason' => 'required|string',
            'reference' => 'nullable|string'
        ]);

        $this->inventoryService->adjustStock($request->all(), Auth::id());

        return redirect()->route('inventory.index')->with('success', 'Stock matrix recalibrated successfully.');
    }
}
