<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Batch;
use App\Models\Dispatch;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    /**
     * Get real-time stock summary from the inventory ledger.
     */
    public function getStockSummary()
    {
        return Inventory::select('grain_type', DB::raw('SUM(current_stock_mt) as total_weight'))
            ->groupBy('grain_type')
            ->get();
    }

    /**
     * Get financial summary for a given date range.
     */
    public function getFinanceSummary(?string $startDate = null, ?string $endDate = null)
    {
        $query = Transaction::with('category')
            ->where('status', 'approved');

        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $income = $transactions->filter(fn($t) => $t->category && $t->category->type === 'income')->sum('amount');
        $expense = $transactions->filter(fn($t) => $t->category && $t->category->type === 'expense')->sum('amount');

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'net_balance' => $income - $expense,
            'transaction_count' => $transactions->count(),
            'transactions' => $transactions
        ];
    }

    /**
     * Get logistics performance metrics.
     */
    public function getLogisticsPerformance()
    {
        $totalBatches = Batch::count();
        $totalDispatches = Dispatch::count();
        $deliveredDispatches = Dispatch::where('status', 'delivered')->count();

        return [
            'total_batches' => $totalBatches,
            'total_dispatches' => $totalDispatches,
            'fulfillment_rate' => $totalDispatches > 0 ? ($deliveredDispatches / $totalDispatches) * 100 : 0,
            'recent_batches' => Batch::with('supplier')->latest()->limit(5)->get()
        ];
    }

    /**
     * Get supplier analytics.
     */
    public function getSupplierAnalytics()
    {
        return Supplier::withCount(['purchaseOrders', 'batches'])
            ->get()
            ->map(function ($supplier) {
                return [
                    'name' => $supplier->name,
                    'po_count' => $supplier->purchase_orders_count,
                    'batch_count' => $supplier->batches_count,
                    'status' => $supplier->is_active ? 'Active' : 'Inactive'
                ];
            });
    }

    /**
     * Get system-wide quick stats for the dashboard.
     */
    public function getGlobalQuickStats()
    {
        return [
            'inventory' => $this->getStockSummary(),
            'finance' => $this->getFinanceSummary(now()->startOfMonth(), now()->endOfMonth()),
            'logistics' => [
                'active_dispatches' => Dispatch::whereIn('status', ['pending', 'in_transit'])->count()
            ],
            'procurement' => [
                'open_pos' => PurchaseOrder::where('status', 'pending')->count()
            ]
        ];
    }

    /**
     * Get advanced financial analytics (Legacy Parity)
     */
    public function getFinancialAnalytics()
    {
        // Monthly Trends (Last 6 Months)
        $trends = Transaction::select(
            DB::raw("TO_CHAR(transaction_date, 'YYYY-MM') as month"),
            DB::raw("SUM(CASE WHEN finance_categories.type = 'income' THEN amount ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN finance_categories.type = 'expense' THEN amount ELSE 0 END) as expense")
        )
            ->join('finance_categories', 'transactions.category_id', '=', 'finance_categories.id')
            ->where('status', 'approved')
            ->where('transaction_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Category Distribution
        $distribution = Transaction::select(
            'finance_categories.name',
            DB::raw("SUM(amount) as total_amount")
        )
            ->join('finance_categories', 'transactions.category_id', '=', 'finance_categories.id')
            ->where('finance_categories.type', 'expense')
            ->where('status', 'approved')
            ->where('transaction_date', '>=', now()->startOfYear())
            ->groupBy('finance_categories.name')
            ->get();

        return [
            'trends' => $trends,
            'distribution' => $distribution,
            'summary' => $this->getFinanceSummary(now()->startOfYear()->toDateString(), now()->toDateString())
        ];
    }
}
