<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDriverIdToDispatches extends Migration
{
    public function up()
    {
        // Add driver_id_number column to dispatches table
        $this->forge->addColumn('dispatches', [
            'driver_id_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'driver_phone',
                'comment' => 'Driver license or national ID number'
            ]
        ]);
        
        log_message('info', 'Added driver_id_number column to dispatches table');
    }

    public function down()
    {
        // Remove driver_id_number column
        $this->forge->dropColumn('dispatches', 'driver_id_number');
        
        log_message('info', 'Removed driver_id_number column from dispatches table');
    }
}
