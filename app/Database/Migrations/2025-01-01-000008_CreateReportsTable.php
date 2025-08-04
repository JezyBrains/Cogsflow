<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'bx-chart',
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'primary',
            ],
            'query_config' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'chart_config' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'filters' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'roles' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            'sort_order' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('category');
        $this->forge->createTable('reports');

        // Insert default reports
        $this->db->table('reports')->insertBatch([
            [
                'name' => 'Stock Summary',
                'slug' => 'stock_summary',
                'description' => 'Overview of incoming and outgoing stock with current inventory levels',
                'category' => 'inventory',
                'icon' => 'bx-package',
                'color' => 'success',
                'query_config' => json_encode([
                    'tables' => ['batches', 'dispatches'],
                    'metrics' => ['total_incoming', 'total_outgoing', 'current_stock'],
                    'group_by' => 'grain_type'
                ]),
                'chart_config' => json_encode([
                    'type' => 'bar',
                    'responsive' => true
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'grain_type' => true,
                    'supplier' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Expense Analysis',
                'slug' => 'expense_analysis',
                'description' => 'Detailed breakdown of expenses by category and time period',
                'category' => 'financial',
                'icon' => 'bx-money',
                'color' => 'warning',
                'query_config' => json_encode([
                    'tables' => ['expenses'],
                    'metrics' => ['total_amount', 'average_amount', 'count'],
                    'group_by' => 'category'
                ]),
                'chart_config' => json_encode([
                    'type' => 'pie',
                    'responsive' => true
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'category' => true,
                    'amount_range' => true
                ]),
                'roles' => json_encode(['admin']),
                'sort_order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Dispatch Performance',
                'slug' => 'dispatch_performance',
                'description' => 'Analysis of dispatch efficiency and delivery performance',
                'category' => 'operations',
                'icon' => 'bx-truck',
                'color' => 'info',
                'query_config' => json_encode([
                    'tables' => ['dispatches'],
                    'metrics' => ['total_dispatches', 'completed_dispatches', 'average_delivery_time'],
                    'group_by' => 'status'
                ]),
                'chart_config' => json_encode([
                    'type' => 'line',
                    'responsive' => true
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'status' => true,
                    'vehicle' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Supplier Performance',
                'slug' => 'supplier_performance',
                'description' => 'Evaluation of supplier reliability and delivery metrics',
                'category' => 'suppliers',
                'icon' => 'bx-group',
                'color' => 'primary',
                'query_config' => json_encode([
                    'tables' => ['batches'],
                    'metrics' => ['total_batches', 'total_quantity', 'average_quality'],
                    'group_by' => 'supplier_name'
                ]),
                'chart_config' => json_encode([
                    'type' => 'radar',
                    'responsive' => true
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'supplier' => true,
                    'grain_type' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Batch Analytics',
                'slug' => 'batch_analytics',
                'description' => 'Comprehensive analysis of batch arrivals and processing',
                'category' => 'inventory',
                'icon' => 'bx-analyse',
                'color' => 'secondary',
                'query_config' => json_encode([
                    'tables' => ['batches'],
                    'metrics' => ['total_batches', 'total_quantity', 'pending_approval'],
                    'group_by' => 'arrival_date'
                ]),
                'chart_config' => json_encode([
                    'type' => 'area',
                    'responsive' => true
                ]),
                'filters' => json_encode([
                    'date_range' => true,
                    'status' => true,
                    'grain_type' => true
                ]),
                'roles' => json_encode(['admin', 'warehouse_staff']),
                'sort_order' => 5,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('reports');
    }
}
