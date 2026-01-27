<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Dispatch;
use App\Models\Supplier;
use App\Services\LogisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogisticsController extends Controller
{
    protected $logisticsService;

    public function __construct(LogisticsService $logisticsService)
    {
        $this->logisticsService = $logisticsService;
    }

    /**
     * Batch Index - List all grain batches
     */
    public function batches()
    {
        $batches = Batch::with(['supplier', 'purchaseOrder'])->latest()->paginate(15);
        return view('logistics.batches', compact('batches'));
    }

    /**
     * Batch Creation Form
     */
    public function createBatch()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $purchaseOrders = \App\Models\PurchaseOrder::with('supplier')
            ->whereIn('status', ['issued', 'partially_fulfilled'])
            ->get();
        return view('logistics.batches_create', compact('suppliers', 'purchaseOrders'));
    }

    /**
     * Store new batch
     */
    public function storeBatch(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'commodity_type' => 'required|string',
            'bags' => 'required|array|min:1',
            'bags.*.weight_kg' => 'required|numeric|min:0.1',
            'bags.*.moisture' => 'nullable|numeric|min:0|max:100',
        ]);

        $bags = $request->input('bags', []);
        $this->logisticsService->createBatch($request->except('bags'), $bags, Auth::id());

        return redirect()->route('logistics.batches')->with('success', 'Batch protocol initiated successfully.');
    }

    /**
     * Dispatch Index - List all transit nodes
     */
    public function dispatches()
    {
        $dispatches = Dispatch::with(['batch', 'driver'])->latest()->paginate(15);
        return view('logistics.dispatches', compact('dispatches'));
    }

    /**
     * Dispatch Creation Form
     */
    public function createDispatch()
    {
        $availableBatches = Batch::whereIn('status', ['at_gate', 'accepted'])
            ->whereDoesntHave('dispatches', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->get();
        return view('logistics.dispatches_create', compact('availableBatches'));
    }

    /**
     * Store new dispatch
     */
    public function storeDispatch(Request $request)
    {
        $request->validate([
            'vehicle_reg_number' => 'required|string',
            'destination' => 'required|string',
            'batch_id' => 'required|exists:batches,id',
        ]);

        $this->logisticsService->createDispatch($request->all(), Auth::id());

        return redirect()->route('logistics.dispatches')->with('success', 'Dispatch node activated.');
    }

    /**
     * Update dispatch status to delivered
     */
    public function confirmDelivery($id)
    {
        $this->logisticsService->confirmDelivery($id, Auth::id());
        return redirect()->back()->with('success', 'Delivery sequence confirmed.');
    }

    /**
     * Show Batch Details / Inspection Terminal
     */
    public function showBatch($id)
    {
        $batch = Batch::with(['bags', 'supplier', 'attachments', 'dispatches'])->findOrFail($id);
        $this->authorize('view', $batch);

        return view('logistics.batch_details', compact('batch'));
    }

    /**
     * Show physical inspection terminal for a dispatch
     */
    public function showInspection($id)
    {
        $dispatch = Dispatch::with(['batch.bags', 'batch.supplier', 'attachments', 'batch.attachments'])->findOrFail($id);
        $this->authorize('view', $dispatch);

        return view('logistics.inspection', compact('dispatch'));
    }

    /**
     * Process individual bag inspection via AJAX
     */
    public function processBagInspection(Request $request)
    {
        $request->validate([
            'dispatch_id' => 'nullable|exists:dispatches,id',
            'bag_id' => 'required|exists:batch_bags,id',
            'actual_weight' => 'nullable|numeric|min:0',
            'actual_moisture' => 'nullable|numeric|min:0|max:100',
            'condition_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        // Provide defaults for rapid entry if not provided
        if (!isset($data['condition_status']))
            $data['condition_status'] = 'Good';
        if (!isset($data['actual_weight'])) {
            $bag = \App\Models\BatchBag::find($data['bag_id']);
            $data['actual_weight'] = $bag->weight_kg; // Fallback to recorded weight
        }

        $this->logisticsService->recordBagInspection($data, Auth::id());

        return response()->json(['success' => true, 'message' => 'Bag inspection recorded.']);
    }

    public function swapVehicle(Request $request, $id)
    {
        $dispatch = \App\Models\Dispatch::findOrFail($id);

        $data = $request->validate([
            'vehicle_reg_number' => 'required|string|max:50',
            'trailer_number' => 'nullable|string|max:50',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'nullable|string|max:20'
        ]);

        $this->logisticsService->swapVehicle($id, $data);

        return redirect()->back()->with('success', 'Emergency vehicle swap authorized and recorded.');
    }
}
