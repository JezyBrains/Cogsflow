<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierReports extends Migration
{
    public function up()
    {
        // Insert additional supplier-related reports
        $this->db->table('reports')->insertBatch([
            [
                'name' => 'Supplier Financial Summary',
                'slug' => 'supplier_financial_summary',
                'description' => 'Financial overview of supplier transactions including purchase orders and payments',
                'category' => 'suppliers',
                'icon' => 'bx-money-withdraw',
                'color' => 'success',
                'query_config' => json_encode([
                    'tables' => ['suppliers', 'purchase_orders', 'batches'],
                    'metrics' => ['total_purchase_value', 'total_payments', 'outstanding_balance', 'total_batches'],
                    'group_by' => 'supplier_id'
                ]),
                'chart_config' => json_encode([
                    'type' => 'bar',
                    'responsive' => true,
                    'options' => [
                        'scales' => [
                            'y' => [
                                'beginAtZero' => true,
                                'ticks' => [
                                    'callback' => 'function(value) { return "TSH " + value.toLocaleString(); }'
                                ]
                            ]
                        ]
                    ]
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'supplier' => true,
                    'supplier_type' => true,
                    'status' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Supplier Quality Metrics',
                'slug' => 'supplier_quality_metrics',
                'description' => 'Analysis of grain quality metrics by supplier including moisture content and rejection rates',
                'category' => 'suppliers',
                'icon' => 'bx-award',
                'color' => 'warning',
                'query_config' => json_encode([
                    'tables' => ['suppliers', 'batches', 'batch_bags'],
                    'metrics' => ['average_moisture', 'rejection_rate', 'quality_score', 'total_quantity'],
                    'group_by' => 'supplier_id'
                ]),
                'chart_config' => json_encode([
                    'type' => 'radar',
                    'responsive' => true,
                    'options' => [
                        'scales' => [
                            'r' => [
                                'beginAtZero' => true,
                                'max' => 100
                            ]
                        ]
                    ]
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'supplier' => true,
                    'grain_type' => true,
                    'quality_threshold' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Supplier Delivery Performance',
                'slug' => 'supplier_delivery_performance',
                'description' => 'Tracking supplier delivery timeliness and dispatch efficiency',
                'category' => 'suppliers',
                'icon' => 'bx-time-five',
                'color' => 'info',
                'query_config' => json_encode([
                    'tables' => ['suppliers', 'batches', 'dispatches'],
                    'metrics' => ['on_time_deliveries', 'total_deliveries', 'average_delivery_time', 'dispatch_efficiency'],
                    'group_by' => 'supplier_id'
                ]),
                'chart_config' => json_encode([
                    'type' => 'line',
                    'responsive' => true,
                    'options' => [
                        'scales' => [
                            'y' => [
                                'beginAtZero' => true,
                                'max' => 100,
                                'ticks' => [
                                    'callback' => 'function(value) { return value + "%"; }'
                                ]
                            ]
                        ]
                    ]
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'supplier' => true,
                    'delivery_status' => true,
                    'time_threshold' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Supplier Comparison Analysis',
                'slug' => 'supplier_comparison_analysis',
                'description' => 'Comparative analysis of suppliers across multiple performance metrics',
                'category' => 'suppliers',
                'icon' => 'bx-stats',
                'color' => 'primary',
                'query_config' => json_encode([
                    'tables' => ['suppliers', 'batches', 'purchase_orders', 'dispatches'],
                    'metrics' => ['total_volume', 'average_price', 'quality_score', 'delivery_performance', 'reliability_index'],
                    'group_by' => 'supplier_id'
                ]),
                'chart_config' => json_encode([
                    'type' => 'scatter',
                    'responsive' => true,
                    'options' => [
                        'scales' => [
                            'x' => [
                                'title' => [
                                    'display' => true,
                                    'text' => 'Quality Score'
                                ]
                            ],
                            'y' => [
                                'title' => [
                                    'display' => true,
                                    'text' => 'Delivery Performance (%)'
                                ]
                            ]
                        ]
                    ]
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'supplier_type' => true,
                    'minimum_volume' => true,
                    'performance_threshold' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Supplier Activity Timeline',
                'slug' => 'supplier_activity_timeline',
                'description' => 'Timeline view of supplier activities including batches, orders, and payments',
                'category' => 'suppliers',
                'icon' => 'bx-history',
                'color' => 'secondary',
                'query_config' => json_encode([
                    'tables' => ['suppliers', 'batches', 'purchase_orders', 'dispatches'],
                    'metrics' => ['activity_count', 'transaction_volume', 'frequency_score'],
                    'group_by' => 'activity_date'
                ]),
                'chart_config' => json_encode([
                    'type' => 'area',
                    'responsive' => true,
                    'options' => [
                        'fill' => true,
                        'tension' => 0.4
                    ]
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'supplier' => true,
                    'activity_type' => true,
                    'volume_threshold' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function down()
    {
        // Remove the supplier reports we added
        $this->db->table('reports')->whereIn('slug', [
            'supplier_financial_summary',
            'supplier_quality_metrics', 
            'supplier_delivery_performance',
            'supplier_comparison_analysis',
            'supplier_activity_timeline'
        ])->delete();
    }
}
