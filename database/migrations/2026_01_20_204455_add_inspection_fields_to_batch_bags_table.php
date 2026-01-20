<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('batch_bags', function (Blueprint $table) {
            $table->decimal('actual_weight', 10, 2)->nullable();
            $table->decimal('actual_moisture', 5, 2)->nullable();
            $table->decimal('weight_discrepancy', 10, 2)->nullable();
            $table->decimal('moisture_discrepancy', 5, 2)->nullable();
            $table->string('condition_status')->default('Pending');
            $table->text('inspection_notes')->nullable();
            $table->foreignId('inspected_by')->nullable()->constrained('users');
            $table->timestamp('inspected_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('batch_bags', function (Blueprint $table) {
            $table->dropForeign(['inspected_by']);
            $table->dropColumn([
                'actual_weight',
                'actual_moisture',
                'weight_discrepancy',
                'moisture_discrepancy',
                'condition_status',
                'inspection_notes',
                'inspected_by',
                'inspected_at'
            ]);
        });
    }
};
