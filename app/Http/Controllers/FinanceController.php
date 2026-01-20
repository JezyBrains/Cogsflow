<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\FinanceCategory;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Display the financial ledger
     */
    public function index()
    {
        $transactions = Transaction::with(['category', 'recorder', 'approver'])->latest()->paginate(15);
        $summary = $this->financeService->getLedgerSummary();

        return view('finance.index', compact('transactions', 'summary'));
    }

    /**
     * Show form to create a new transaction
     */
    public function create()
    {
        $categories = FinanceCategory::where('is_active', true)->get();
        return view('finance.create', compact('categories'));
    }

    /**
     * Store a new transaction
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:finance_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'payee_payer_name' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $this->financeService->recordTransaction($data, Auth::id());

        return redirect()->route('finance.index')->with('success', 'Transaction recorded successfully.');
    }

    /**
     * Approve a transaction
     */
    public function approve($id)
    {
        try {
            $this->financeService->approveTransaction($id, Auth::id());
            return redirect()->back()->with('success', 'Transaction approved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
