<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\DB;
use App\Services\AuditService;

class FinanceService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Record a new financial transaction (Income or Expense)
     */
    public function recordTransaction(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $transaction = Transaction::create([
                'reference_number' => $this->generateReferenceNumber($data['category_id']),
                'category_id' => $data['category_id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'TZS',
                'transaction_date' => $data['transaction_date'],
                'recordable_type' => $data['recordable_type'] ?? null,
                'recordable_id' => $data['recordable_id'] ?? null,
                'payment_method' => $data['payment_method'],
                'payee_payer_name' => $data['payee_payer_name'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'recorded_by' => $userId
            ]);

            $this->auditService->log('transaction_created', $transaction);

            return $transaction;
        });
    }

    /**
     * Approve a pending transaction
     */
    public function approveTransaction(int $transactionId, int $userId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->status !== 'pending') {
            throw new \Exception("Transaction is not pending approval.");
        }

        $transaction->update([
            'status' => 'approved',
            'approved_by' => $userId
        ]);

        $this->auditService->log('transaction_approved', $transaction);

        return $transaction;
    }

    /**
     * Get Ledger Summary (Income vs Expenses)
     */
    public function getLedgerSummary()
    {
        $income = Transaction::whereHas('category', function ($q) {
            $q->where('type', 'income');
        })->where('status', 'approved')->sum('amount');

        $expenses = Transaction::whereHas('category', function ($q) {
            $q->where('type', 'expense');
        })->where('status', 'approved')->sum('amount');

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'net_balance' => $income - $expenses
        ];
    }

    /**
     * Generate a unique reference number based on category type
     */
    private function generateReferenceNumber($categoryId)
    {
        $category = FinanceCategory::find($categoryId);
        $prefix = $category && $category->type === 'income' ? 'INC' : 'EXP';
        return $prefix . '-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
    }
}
