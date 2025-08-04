<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'slug', 'description', 'category', 'icon', 'color',
        'query_config', 'chart_config', 'filters', 'roles', 
        'is_active', 'sort_order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'slug' => 'required|max_length[100]|is_unique[reports.slug,id,{id}]',
        'category' => 'required|max_length[50]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Report name is required',
            'max_length' => 'Report name cannot exceed 100 characters'
        ],
        'slug' => [
            'required' => 'Report slug is required',
            'is_unique' => 'Report slug must be unique'
        ]
    ];

    /**
     * Get reports accessible by user role
     */
    public function getReportsByRole($userRoles)
    {
        if (!is_array($userRoles)) {
            $userRoles = [$userRoles];
        }

        return $this->where('is_active', true)
                   ->orderBy('sort_order', 'ASC')
                   ->findAll();
    }

    /**
     * Get report by slug
     */
    public function getReportBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get reports grouped by category
     */
    public function getReportsByCategory($userRoles = null)
    {
        $reports = $this->getReportsByRole($userRoles);
        $grouped = [];

        foreach ($reports as $report) {
            // Check if user has access to this report
            if ($userRoles && !empty($report['roles'])) {
                $reportRoles = json_decode($report['roles'], true);
                if (is_array($reportRoles) && !array_intersect($userRoles, $reportRoles)) {
                    continue;
                }
            }

            $category = $report['category'];
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $report;
        }

        return $grouped;
    }

    /**
     * Generate stock summary report data
     */
    public function getStockSummaryData($filters = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('batches b');

        // Base query for incoming stock
        $incomingQuery = $builder->select('
            b.grain_type,
            SUM(b.quantity) as total_incoming,
            COUNT(b.id) as batch_count,
            AVG(b.quantity) as avg_batch_size
        ')->groupBy('b.grain_type');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $incomingQuery->where('b.arrival_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $incomingQuery->where('b.arrival_date <=', $filters['date_to']);
        }
        if (!empty($filters['grain_type'])) {
            $incomingQuery->where('b.grain_type', $filters['grain_type']);
        }
        if (!empty($filters['supplier'])) {
            $incomingQuery->where('b.supplier_name', $filters['supplier']);
        }

        $incomingData = $incomingQuery->get()->getResultArray();

        // Get outgoing stock data
        $outgoingBuilder = $db->table('dispatches d');
        $outgoingQuery = $outgoingBuilder->select('
            b.grain_type,
            SUM(d.quantity) as total_outgoing,
            COUNT(d.id) as dispatch_count
        ')
        ->join('batches b', 'd.batch_id = b.id')
        ->groupBy('b.grain_type');

        // Apply same filters for outgoing
        if (!empty($filters['date_from'])) {
            $outgoingQuery->where('d.dispatch_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $outgoingQuery->where('d.dispatch_date <=', $filters['date_to']);
        }
        if (!empty($filters['grain_type'])) {
            $outgoingQuery->where('b.grain_type', $filters['grain_type']);
        }

        $outgoingData = $outgoingQuery->get()->getResultArray();

        // Combine data
        $combinedData = [];
        foreach ($incomingData as $incoming) {
            $grainType = $incoming['grain_type'];
            $combinedData[$grainType] = $incoming;
            $combinedData[$grainType]['total_outgoing'] = 0;
            $combinedData[$grainType]['dispatch_count'] = 0;
        }

        foreach ($outgoingData as $outgoing) {
            $grainType = $outgoing['grain_type'];
            if (isset($combinedData[$grainType])) {
                $combinedData[$grainType]['total_outgoing'] = $outgoing['total_outgoing'];
                $combinedData[$grainType]['dispatch_count'] = $outgoing['dispatch_count'];
            }
        }

        // Calculate current stock
        foreach ($combinedData as &$data) {
            $data['current_stock'] = $data['total_incoming'] - $data['total_outgoing'];
        }

        return array_values($combinedData);
    }

    /**
     * Generate expense analysis report data
     */
    public function getExpenseAnalysisData($filters = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('expenses');

        $query = $builder->select('
            category,
            SUM(amount) as total_amount,
            AVG(amount) as average_amount,
            COUNT(*) as expense_count,
            MIN(amount) as min_amount,
            MAX(amount) as max_amount
        ')->groupBy('category');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->where('expense_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('expense_date <=', $filters['date_to']);
        }
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['amount_min'])) {
            $query->where('amount >=', $filters['amount_min']);
        }
        if (!empty($filters['amount_max'])) {
            $query->where('amount <=', $filters['amount_max']);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Generate dispatch performance report data
     */
    public function getDispatchPerformanceData($filters = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('dispatches d');

        $query = $builder->select('
            d.status,
            COUNT(*) as total_dispatches,
            SUM(d.quantity) as total_quantity,
            AVG(d.quantity) as avg_quantity,
            DATE(d.dispatch_date) as dispatch_day
        ')
        ->groupBy('d.status, DATE(d.dispatch_date)')
        ->orderBy('d.dispatch_date', 'DESC');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->where('d.dispatch_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('d.dispatch_date <=', $filters['date_to']);
        }
        if (!empty($filters['status'])) {
            $query->where('d.status', $filters['status']);
        }
        if (!empty($filters['vehicle'])) {
            $query->where('d.vehicle_number', $filters['vehicle']);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Generate supplier performance report data
     */
    public function getSupplierPerformanceData($filters = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('batches');

        $query = $builder->select('
            supplier_name,
            COUNT(*) as total_batches,
            SUM(quantity) as total_quantity,
            AVG(quantity) as avg_batch_size,
            AVG(quality_score) as avg_quality_score,
            MIN(arrival_date) as first_delivery,
            MAX(arrival_date) as last_delivery
        ')->groupBy('supplier_name');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->where('arrival_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('arrival_date <=', $filters['date_to']);
        }
        if (!empty($filters['supplier'])) {
            $query->where('supplier_name', $filters['supplier']);
        }
        if (!empty($filters['grain_type'])) {
            $query->where('grain_type', $filters['grain_type']);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Generate batch analytics report data
     */
    public function getBatchAnalyticsData($filters = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('batches');

        $query = $builder->select('
            DATE(arrival_date) as arrival_day,
            grain_type,
            status,
            COUNT(*) as batch_count,
            SUM(quantity) as total_quantity,
            AVG(quality_score) as avg_quality
        ')
        ->groupBy('DATE(arrival_date), grain_type, status')
        ->orderBy('arrival_date', 'DESC');

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->where('arrival_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('arrival_date <=', $filters['date_to']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['grain_type'])) {
            $query->where('grain_type', $filters['grain_type']);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Get unique values for filter dropdowns
     */
    public function getFilterOptions($field, $table = null)
    {
        $db = \Config\Database::connect();
        
        switch ($field) {
            case 'grain_types':
                return $db->table('batches')->select('grain_type')->distinct()->get()->getResultArray();
            case 'suppliers':
                return $db->table('batches')->select('supplier_name')->distinct()->get()->getResultArray();
            case 'expense_categories':
                return $db->table('expenses')->select('category')->distinct()->get()->getResultArray();
            case 'dispatch_statuses':
                return $db->table('dispatches')->select('status')->distinct()->get()->getResultArray();
            case 'vehicles':
                return $db->table('dispatches')->select('vehicle_number')->distinct()->get()->getResultArray();
            default:
                return [];
        }
    }
}
