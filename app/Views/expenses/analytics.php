<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Expense Analytics<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold">Expense Analytics</h4>
        <p class="text-muted mb-0">Comprehensive expense analysis and trends</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('expenses') ?>" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Expenses
        </a>
    </div>
</div>

<!-- Year Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="year" class="form-label">Select Year</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-9">
                <div class="d-flex gap-2 justify-content-end">
                    <span class="badge bg-label-primary p-2">
                        <i class="bx bx-calendar"></i> Viewing: <?= $year ?>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">Total Expenses</span>
                        <h3 class="card-title mb-1"><?= format_currency($stats['total_amount'] ?? 0) ?></h3>
                        <small class="text-muted"><?= $stats['total_expenses'] ?? 0 ?> transactions</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-wallet bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">This Year</span>
                        <h3 class="card-title mb-1"><?= format_currency($stats['this_year_amount'] ?? 0) ?></h3>
                        <small class="text-info"><?= $stats['this_year_expenses'] ?? 0 ?> expenses</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">This Month</span>
                        <h3 class="card-title mb-1"><?= format_currency($stats['this_month_amount'] ?? 0) ?></h3>
                        <small class="text-success"><?= $stats['this_month_expenses'] ?? 0 ?> expenses</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-calendar-alt bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">Average/Month</span>
                        <h3 class="card-title mb-1">
                            <?php 
                            $avgPerMonth = ($stats['this_year_amount'] ?? 0) / max(1, date('n'));
                            echo format_currency($avgPerMonth);
                            ?>
                        </h3>
                        <small class="text-warning">Current year</small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-bar-chart-alt bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trend -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Monthly Expense Trend - <?= $year ?></h5>
                <span class="badge bg-label-primary">12 Months</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Expenses Count</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end">Average</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $months = [
                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                            ];
                            
                            // Create indexed array for monthly data
                            $monthlyIndexed = [];
                            if (!empty($monthlyData)) {
                                foreach ($monthlyData as $data) {
                                    $monthlyIndexed[$data['month']] = $data;
                                }
                            }
                            
                            $yearTotal = 0;
                            $yearCount = 0;
                            
                            foreach ($months as $monthNum => $monthName):
                                $data = $monthlyIndexed[$monthNum] ?? null;
                                $count = $data['expense_count'] ?? 0;
                                $amount = $data['total_amount'] ?? 0;
                                $avg = $count > 0 ? $amount / $count : 0;
                                
                                $yearTotal += $amount;
                                $yearCount += $count;
                            ?>
                            <tr>
                                <td>
                                    <i class="bx bx-calendar me-1"></i>
                                    <strong><?= $monthName ?></strong>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-secondary"><?= $count ?></span>
                                </td>
                                <td class="text-end">
                                    <strong><?= format_currency($amount) ?></strong>
                                </td>
                                <td class="text-end text-muted">
                                    <?= format_currency($avg) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-border-bottom-0">
                            <tr class="fw-bold">
                                <td>TOTAL</td>
                                <td class="text-end">
                                    <span class="badge bg-primary"><?= $yearCount ?></span>
                                </td>
                                <td class="text-end text-primary">
                                    <?= format_currency($yearTotal) ?>
                                </td>
                                <td class="text-end text-muted">
                                    <?= format_currency($yearCount > 0 ? $yearTotal / $yearCount : 0) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Expenses by Category - <?= $year ?></h5>
                <span class="badge bg-label-info"><?= count($categoryData ?? []) ?> Categories</span>
            </div>
            <div class="card-body">
                <?php if (!empty($categoryData)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Expenses Count</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end">% of Total</th>
                                <th width="200">Distribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalAmount = array_sum(array_column($categoryData, 'total_amount'));
                            foreach ($categoryData as $cat): 
                                $percentage = $totalAmount > 0 ? ($cat['total_amount'] / $totalAmount) * 100 : 0;
                            ?>
                            <tr>
                                <td>
                                    <i class="bx bx-category me-2"></i>
                                    <strong><?= esc($cat['category'] ?? 'Uncategorized') ?></strong>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-secondary"><?= $cat['expense_count'] ?></span>
                                </td>
                                <td class="text-end">
                                    <strong><?= format_currency($cat['total_amount']) ?></strong>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-label-primary"><?= number_format($percentage, 1) ?>%</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?= $percentage ?>%" 
                                             aria-valuenow="<?= $percentage ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($percentage, 1) ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-border-bottom-0">
                            <tr class="fw-bold">
                                <td>TOTAL</td>
                                <td class="text-end">
                                    <span class="badge bg-primary">
                                        <?= array_sum(array_column($categoryData, 'expense_count')) ?>
                                    </span>
                                </td>
                                <td class="text-end text-primary">
                                    <?= format_currency($totalAmount) ?>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary">100%</span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bx bx-bar-chart-alt bx-lg text-muted mb-3"></i>
                    <p class="text-muted">No expense data available for <?= $year ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
