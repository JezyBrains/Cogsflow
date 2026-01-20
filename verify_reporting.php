<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\ReportingService;
use App\Models\Inventory;
use App\Models\Transaction;

echo "--- STARTING REPORTING VERIFICATION ---\n";

$reportingService = app(ReportingService::class);

// 1. Verify Stock Summary
echo "Checking Stock Summary...\n";
$stock = $reportingService->getStockSummary();
echo "Commodities found: " . $stock->count() . "\n";
foreach ($stock as $s) {
    echo "- {$s->grain_type}: " . number_format($s->total_weight, 2) . " MT\n";
}

// 2. Verify Finance Summary
echo "\nChecking Finance Summary (Current Month)...\n";
$finance = $reportingService->getFinanceSummary(now()->startOfMonth(), now()->endOfMonth());
echo "- Total Income: " . number_format($finance['total_income'], 2) . "\n";
echo "- Total Expense: " . number_format($finance['total_expense'], 2) . "\n";
echo "- Net Balance: " . number_format($finance['net_balance'], 2) . "\n";

// 3. Verify Logistics Performance
echo "\nChecking Logistics Performance...\n";
$logistics = $reportingService->getLogisticsPerformance();
echo "- Total Batches: " . $logistics['total_batches'] . "\n";
echo "- Total Dispatches: " . $logistics['total_dispatches'] . "\n";
echo "- Fulfillment Rate: " . number_format($logistics['fulfillment_rate'], 2) . "%\n";

// 4. Verify Global Stats
echo "\nChecking Global Dashboard Stats...\n";
$global = $reportingService->getGlobalQuickStats();
echo "- Open POs: " . $global['procurement']['open_pos'] . "\n";
echo "- Active Dispatches: " . $global['logistics']['active_dispatches'] . "\n";

echo "\nVERIFICATION COMPLETED ✅\n";