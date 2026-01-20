<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Batches - Grouping of received bags
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('purchase_order_id')->nullable()->constrained();
            $table->string('commodity_type');
            $table->integer('expected_bags');
            $table->decimal('total_weight_kg', 12, 2)->default(0);
            $table->decimal('average_moisture', 5, 2)->nullable();
            $table->string('quality_grade')->nullable();
            $table->enum('status', ['at_gate', 'in_inspection', 'accepted', 'rejected', 'milled', 'shipped'])->default('at_gate');
            $table->foreignId('received_by')->constrained('users');
            $table->timestamp('received_at');
            $table->timestamps();
        });

        // Individual Bag Tracking (The "Perfect Grain" requirement)
        Schema::create('batch_bags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->string('bag_serial_number')->nullable(); // For future RFID/Barcode support
            $table->decimal('weight_kg', 8, 2);
            $table->decimal('moisture_content', 5, 2)->nullable();
            $table->boolean('is_damaged')->default(false);
            $table->timestamps();
        });

        // Dispatches - Outgoing movements
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('dispatch_number')->unique();
            $table->foreignId('batch_id')->nullable()->constrained(); // Nullable for consolidated dispatches
            $table->string('vehicle_reg_number');
            $table->string('trailer_number')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('users'); // Reference to user (driver role)
            $table->string('driver_name')->nullable(); // For external drivers
            $table->string('driver_phone')->nullable();
            $table->string('destination');
            $table->text('route_plan')->nullable();
            $table->enum('status', ['pending', 'loaded', 'dispatched', 'delivered', 'cancelled'])->default('pending');
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('actual_arrival')->nullable();
            $table->foreignId('dispatcher_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatches');
        Schema::dropIfExists('batch_bags');
        Schema::dropIfExists('batches');
    }
};
