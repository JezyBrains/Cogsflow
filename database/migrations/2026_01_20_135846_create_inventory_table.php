<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('grain_type');
            $table->string('description')->nullable();
            $table->decimal('current_stock_mt', 12, 4)->default(0);
            $table->decimal('minimum_level_mt', 12, 4)->default(10);
            $table->decimal('unit_cost', 18, 2)->default(0);
            $table->string('location')->default('Main Warehouse');
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
