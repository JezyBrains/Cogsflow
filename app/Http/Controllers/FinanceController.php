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
     * Show form to edit a transaction
     */
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $categories = FinanceCategory::where('is_active', true)->get();
        return view('finance.edit', compact('transaction', 'categories'));
    }

    /**
     * Update a transaction
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:finance_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'payee_payer_name' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $transaction = Transaction::findOrFail($id);
            // We could also add a check to only allow editing if it was not approved yet,
            // or if the user has specific permissions.
            $transaction->update($data);
            return redirect()->route('finance.index')->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

    /**
     * Remove a transaction (Void)
     */
    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();
            return redirect()->back()->with('success', 'Transaction voided/deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
