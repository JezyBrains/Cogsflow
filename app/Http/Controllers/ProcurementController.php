<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Services\ProcurementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    protected $procurement;

    public function __construct(ProcurementService $procurement)
    {
        $this->procurement = $procurement;
    }

    public function index()
    {
        $pos = PurchaseOrder::with(['supplier', 'creator'])->latest()->paginate(10);
        return view('procurement.index', compact('pos'));
    }

    public function suppliers()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('procurement.suppliers', compact('suppliers'));
    }

    public function showSupplier($id)
    {
        $supplier = Supplier::with(['purchaseOrders.creator', 'batches.receiver', 'attachments'])->findOrFail($id);
        // Authorization Gate
        if (!Auth::user()->can('view', $supplier)) {
            abort(403, 'Unauthorized access to this supplier record.');
        }

        return view('procurement.suppliers_show', compact('supplier'));
    }

    public function storeSupplier(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:suppliers',
            'contact_person' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string'
        ]);

        $this->procurement->createSupplier($data);

        return redirect()->back()->with('success', 'Supplier integrated successfully.');
    }

    public function storePO(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'commodity_type' => 'required|string',
            'total_quantity_kg' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0.01',
            'delivery_deadline' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $this->procurement->createPurchaseOrder($data, Auth::id());

        return redirect()->route('procurement.index')->with('success', 'Purchase Order issued.');
    }
}
