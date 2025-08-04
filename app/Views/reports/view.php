<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="bx <?= $report['icon'] ?> me-2"></i>
                            <?= $report['name'] ?>
                        </h5>
                        <p class="text-muted mb-0"><?= $report['description'] ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="generateReport()">
                            <i class="bx bx-refresh me-1"></i>Generate Report
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bx bx-download me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= site_url('reports/export-pdf/' . $report['slug']) ?>?<?= http_build_query($_GET) ?>">
                                    <i class="bx bx-file-blank me-2"></i>Export PDF
                                </a></li>
                                <li><a class="dropdown-item" href="<?= site_url('reports/export-excel/' . $report['slug']) ?>?<?= http_build_query($_GET) ?>">
                                    <i class="bx bx-spreadsheet me-2"></i>Export Excel
                                </a></li>
                            </ul>
                        </div>
                        <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Back to Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bx bx-filter me-2"></i>Report Filters
                    </h6>
                </div>
                <div class="card-body">
                    <form id="reportFilters" class="row g-3">
                        <!-- Date Range Filter -->
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from" id="dateFrom">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to" id="dateTo">
                        </div>

                        <!-- Dynamic Filters -->
                        <?php if (isset($filter_options['grain_types']) && !empty($filter_options['grain_types'])): ?>
                        <div class="col-md-3">
                            <label class="form-label">Grain Type</label>
                            <select class="form-select" name="grain_type" id="grainType">
                                <option value="">All Grain Types</option>
                                <?php foreach ($filter_options['grain_types'] as $type): ?>
                                    <option value="<?= $type['grain_type'] ?>"><?= $type['grain_type'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($filter_options['suppliers']) && !empty($filter_options['suppliers'])): ?>
                        <div class="col-md-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" name="supplier" id="supplier">
                                <option value="">All Suppliers</option>
                                <?php foreach ($filter_options['suppliers'] as $supplier): ?>
                                    <option value="<?= $supplier['supplier_name'] ?>"><?= $supplier['supplier_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($filter_options['categories']) && !empty($filter_options['categories'])): ?>
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" id="category">
                                <option value="">All Categories</option>
                                <?php foreach ($filter_options['categories'] as $category): ?>
                                    <option value="<?= $category['category'] ?>"><?= $category['category'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($filter_options['statuses']) && !empty($filter_options['statuses'])): ?>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="">All Statuses</option>
                                <?php foreach ($filter_options['statuses'] as $status): ?>
                                    <option value="<?= $status['status'] ?>"><?= $status['status'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($filter_options['vehicles']) && !empty($filter_options['vehicles'])): ?>
                        <div class="col-md-3">
                            <label class="form-label">Vehicle</label>
                            <select class="form-select" name="vehicle" id="vehicle">
                                <option value="">All Vehicles</option>
                                <?php foreach ($filter_options['vehicles'] as $vehicle): ?>
                                    <option value="<?= $vehicle['vehicle_number'] ?>"><?= $vehicle['vehicle_number'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="generateReport()">
                                <i class="bx bx-search me-1"></i>Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="bx bx-x me-1"></i>Clear Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i>Visual Analysis
                    </h6>
                </div>
                <div class="card-body">
                    <div id="reportChart" style="height: 400px;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <i class="bx bx-chart fs-1 text-muted mb-3"></i>
                                <p class="text-muted">Click "Generate Report" to view chart</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bx bx-calculator me-2"></i>Summary Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div id="reportSummary">
                        <div class="text-center text-muted">
                            <i class="bx bx-data fs-1 mb-3"></i>
                            <p>Generate report to view summary</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bx bx-table me-2"></i>Detailed Data
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="exportTableData('csv')">
                            <i class="bx bx-download me-1"></i>CSV
                        </button>
                        <button class="btn btn-outline-primary" onclick="exportTableData('json')">
                            <i class="bx bx-code me-1"></i>JSON
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reportTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th colspan="100%" class="text-center text-muted">
                                        Generate report to view data table
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let reportChart = null;
let reportData = null;

// Generate report
function generateReport() {
    const formData = new FormData(document.getElementById('reportFilters'));
    const params = new URLSearchParams(formData);
    
    // Show loading state
    showLoading();
    
    fetch(`<?= site_url('reports/generate/' . $report['slug']) ?>?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                reportData = data.data;
                updateChart(data.data, data.chart_config);
                updateSummary(data.data);
                updateTable(data.data);
                updateUrl(params);
            } else {
                showError(data.error || 'Failed to generate report');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while generating the report');
        })
        .finally(() => {
            hideLoading();
        });
}

// Update chart
function updateChart(data, chartConfig) {
    const ctx = document.getElementById('reportChart');
    
    if (reportChart) {
        reportChart.destroy();
    }
    
    if (!data || data.length === 0) {
        ctx.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><p class="text-muted">No data available</p></div>';
        return;
    }
    
    // Prepare chart data based on report type
    const chartData = prepareChartData(data, chartConfig);
    
    reportChart = new Chart(ctx, {
        type: chartConfig.type || 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: '<?= $report['name'] ?>'
                }
            },
            scales: chartConfig.type !== 'pie' && chartConfig.type !== 'doughnut' ? {
                y: {
                    beginAtZero: true
                }
            } : {}
        }
    });
}

// Prepare chart data
function prepareChartData(data, chartConfig) {
    const labels = [];
    const datasets = [];
    
    if (data.length === 0) return { labels, datasets };
    
    // Get all unique keys for datasets
    const keys = Object.keys(data[0]).filter(key => 
        !['grain_type', 'supplier_name', 'category', 'status', 'arrival_day', 'dispatch_day'].includes(key)
    );
    
    // Prepare labels
    data.forEach(item => {
        const label = item.grain_type || item.supplier_name || item.category || item.status || item.arrival_day || item.dispatch_day || 'Unknown';
        if (!labels.includes(label)) {
            labels.push(label);
        }
    });
    
    // Prepare datasets
    keys.forEach((key, index) => {
        const values = labels.map(label => {
            const item = data.find(d => 
                (d.grain_type === label) || 
                (d.supplier_name === label) || 
                (d.category === label) || 
                (d.status === label) ||
                (d.arrival_day === label) ||
                (d.dispatch_day === label)
            );
            return item ? parseFloat(item[key]) || 0 : 0;
        });
        
        const colors = ['#696cff', '#71dd37', '#ff3e1d', '#ffab00', '#8592a3', '#233446'];
        
        datasets.push({
            label: key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
            data: values,
            backgroundColor: colors[index % colors.length] + '20',
            borderColor: colors[index % colors.length],
            borderWidth: 2
        });
    });
    
    return { labels, datasets };
}

// Update summary
function updateSummary(data) {
    const summaryDiv = document.getElementById('reportSummary');
    
    if (!data || data.length === 0) {
        summaryDiv.innerHTML = '<p class="text-muted text-center">No data available</p>';
        return;
    }
    
    // Calculate summary statistics
    const summary = calculateSummary(data);
    
    let html = '';
    Object.entries(summary).forEach(([key, value]) => {
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}:</span>
                <strong>${formatValue(value)}</strong>
            </div>
        `;
    });
    
    summaryDiv.innerHTML = html;
}

// Calculate summary statistics
function calculateSummary(data) {
    const summary = {
        'Total Records': data.length
    };
    
    // Calculate sums and averages for numeric fields
    const numericFields = Object.keys(data[0]).filter(key => {
        return !isNaN(parseFloat(data[0][key])) && isFinite(data[0][key]);
    });
    
    numericFields.forEach(field => {
        const values = data.map(item => parseFloat(item[field]) || 0);
        const sum = values.reduce((a, b) => a + b, 0);
        const avg = sum / values.length;
        
        if (field.includes('total') || field.includes('sum')) {
            summary[`Total ${field.replace(/total_|_total/g, '').replace(/_/g, ' ')}`] = sum;
        } else {
            summary[`Average ${field.replace(/_/g, ' ')}`] = avg.toFixed(2);
        }
    });
    
    return summary;
}

// Update table
function updateTable(data) {
    const table = document.getElementById('reportTable');
    
    if (!data || data.length === 0) {
        table.innerHTML = `
            <thead><tr><th class="text-center text-muted">No data available</th></tr></thead>
            <tbody></tbody>
        `;
        return;
    }
    
    // Create table headers
    const headers = Object.keys(data[0]);
    let headerHtml = '<tr>';
    headers.forEach(header => {
        headerHtml += `<th>${header.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</th>`;
    });
    headerHtml += '</tr>';
    
    // Create table rows
    let bodyHtml = '';
    data.forEach(row => {
        bodyHtml += '<tr>';
        headers.forEach(header => {
            bodyHtml += `<td>${formatValue(row[header])}</td>`;
        });
        bodyHtml += '</tr>';
    });
    
    table.innerHTML = `<thead>${headerHtml}</thead><tbody>${bodyHtml}</tbody>`;
}

// Format value for display
function formatValue(value) {
    if (value === null || value === undefined) return '-';
    if (typeof value === 'number') {
        if (value % 1 === 0) return value.toLocaleString();
        return value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return value;
}

// Clear filters
function clearFilters() {
    document.getElementById('reportFilters').reset();
    generateReport();
}

// Export table data
function exportTableData(format) {
    if (!reportData || reportData.length === 0) {
        alert('No data to export. Please generate a report first.');
        return;
    }
    
    if (format === 'csv') {
        exportToCSV(reportData);
    } else if (format === 'json') {
        exportToJSON(reportData);
    }
}

// Export to CSV
function exportToCSV(data) {
    const headers = Object.keys(data[0]);
    const csvContent = [
        headers.join(','),
        ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `<?= strtolower(str_replace(' ', '_', $report['name'])) ?>_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Export to JSON
function exportToJSON(data) {
    const jsonContent = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonContent], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `<?= strtolower(str_replace(' ', '_', $report['name'])) ?>_${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Show loading state
function showLoading() {
    document.getElementById('reportChart').innerHTML = `
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    document.getElementById('reportSummary').innerHTML = `
        <div class="text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <p class="text-muted mt-2">Loading...</p>
        </div>
    `;
}

// Hide loading state
function hideLoading() {
    // Loading states will be replaced by actual content
}

// Show error
function showError(message) {
    document.getElementById('reportChart').innerHTML = `
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-center text-danger">
                <i class="bx bx-error fs-1 mb-3"></i>
                <p>${message}</p>
            </div>
        </div>
    `;
}

// Update URL with current filters
function updateUrl(params) {
    const url = new URL(window.location);
    params.forEach((value, key) => {
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
    });
    window.history.replaceState({}, '', url);
}

// Load filters from URL on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Set filter values from URL
    urlParams.forEach((value, key) => {
        const element = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
        if (element) {
            element.value = value;
        }
    });
    
    // Auto-generate report if filters are present
    if (urlParams.toString()) {
        generateReport();
    }
});
</script>

<?= $this->endSection() ?>
