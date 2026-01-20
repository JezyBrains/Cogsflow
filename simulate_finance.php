<?php

use App\Models\User;
use App\Models\FinanceCategory;
use App\Services\FinanceService;
use Illuminate\Support\Facades\Hash;

echo "--- STARTING FINANCE SIMULATION ---\n";

// 1. Ensure User
$user = User::first();
if (!$user) {
    $user = User::create([
        'name' => 'Finance Admin',
        'email' => 'finance@nipo.com',
        'password' => Hash::make('password')
    ]);
}
echo "User: {$user->name}\n";

// 2. Setup Categories
$incomeCat = FinanceCategory::firstOrCreate(
    ['name' => 'Sales Revenue'],
    ['type' => 'income', 'description' => 'Sales from commodities']
);
$expenseCat = FinanceCategory::firstOrCreate(
    ['name' => 'Operational Expense'],
    ['type' => 'expense', 'description' => 'Day to day running costs']
);
echo "Categories Ready: {$incomeCat->name} / {$expenseCat->name}\n";

// 3. Record Transactions
$financeService = app(FinanceService::class);

echo "Recording Income...\n";
$income = $financeService->recordTransaction([
    'category_id' => $incomeCat->id,
    'amount' => 5000000, // 5M TZS
    'transaction_date' => now(),
    'payment_method' => 'bank_transfer',
    'payee_payer_name' => 'Mega Market Ltd',
    'notes' => 'Q1 Bulk Sale'
], $user->id);
echo "Income Recorded: {$income->reference_number} (Pending)\n";

echo "Recording Expense...\n";
$expense = $financeService->recordTransaction([
    'category_id' => $expenseCat->id,
    'amount' => 150000, // 150k TZS
    'transaction_date' => now(),
    'payment_method' => 'cash',
    'payee_payer_name' => 'Office Supplies Co',
    'notes' => 'Stationery'
], $user->id);
echo "Expense Recorded: {$expense->reference_number} (Pending)\n";

// 4. Approve Transactions
echo "Approving Transactions...\n";
$financeService->approveTransaction($income->id, $user->id);
$financeService->approveTransaction($expense->id, $user->id);

// 5. Check Balance
$summary = $financeService->getLedgerSummary();
echo "\n--- LEDGER SUMMARY ---\n";
echo "Total Income: " . number_format($summary['total_income'], 2) . "\n";
echo "Total Expenses: " . number_format($summary['total_expenses'], 2) . "\n";
echo "Net Balance: " . number_format($summary['net_balance'], 2) . "\n";

if ($summary['net_balance'] == 4850000) {
    echo "VERIFICATION PASSED ✅\n";
} else {
    echo "VERIFICATION FAILED ❌\n";
}
