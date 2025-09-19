<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Inventory Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Inventory Status</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('inventory/adjust') ?>" class="btn btn-outline-primary">
            <i class="bx bx-edit me-1"></i> Adjust Inventory
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Inventory Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Total Stock</h6>
                <h3><?= isset($inventory_summary['total_stock_mt']) ? number_format($inventory_summary['total_stock_mt'] * 1000, 0) : 0 ?> kg</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Available</h6>
                <h3><?= isset($inventory_summary['total_stock_mt']) ? number_format($inventory_summary['total_stock_mt'] * 1000, 0) : 0 ?> kg</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Reserved</h6>
                <h3>0 kg</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6 class="card-title">Low Stock</h6>
                <h3><?= isset($inventory_summary['low_stock_count']) ? $inventory_summary['low_stock_count'] : 0 ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Details Table -->
<div class="card">
    <div class="card-header">
        <h6>Inventory Details by Grain Type</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Grain Type</th>
                        <th>Total Stock (kg)</th>
                        <th>Available (kg)</th>
                        <th>Reserved (kg)</th>
                        <th>Minimum Level (kg)</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($active_inventory) && !empty($active_inventory)): ?>
                        <?php foreach ($active_inventory as $item): ?>
                        <tr>
                            <td><?= esc($item['grain_type']) ?></td>
                            <td><?= number_format($item['current_stock_mt'] * 1000, 0) ?></td>
                            <td><?= number_format($item['current_stock_mt'] * 1000, 0) ?></td>
                            <td>0</td>
                            <td><?= number_format($item['minimum_level_mt'] * 1000, 0) ?></td>
                            <td>
                                <?php if ($item['current_stock_mt'] <= $item['minimum_level_mt']): ?>
                                    <span class="badge bg-danger">Low Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-success">In Stock</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($item['updated_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No inventory data found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Stock Movements -->
<div class="card mt-4">
    <div class="card-header">
        <h6>Recent Stock Movements</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Grain Type</th>
                        <th>Quantity (kg)</th>
                        <th>Reference</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($recent_adjustments) && !empty($recent_adjustments)): ?>
                        <?php foreach ($recent_adjustments as $adjustment): ?>
                        <tr>
                            <td><?= date('Y-m-d', strtotime($adjustment['adjustment_date'])) ?></td>
                            <td>
                                <?php
                                $typeClass = match($adjustment['adjustment_type']) {
                                    'Stock In' => 'bg-success',
                                    'Stock Out' => 'bg-warning',
                                    'Damage/Loss' => 'bg-danger',
                                    'Stock Correction' => 'bg-info',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $typeClass ?>"><?= esc($adjustment['adjustment_type']) ?></span>
                            </td>
                            <td><?= esc($adjustment['grain_type']) ?></td>
                            <td><?= number_format($adjustment['quantity'] * 1000, 0) ?></td>
                            <td><?= esc($adjustment['reference'] ?? '-') ?></td>
                            <td><?= esc(substr($adjustment['reason'], 0, 50)) ?><?= strlen($adjustment['reason']) > 50 ? '...' : '' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No recent movements found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Inventory specific scripts can be added here
</script>
<?= $this->endSection() ?>
