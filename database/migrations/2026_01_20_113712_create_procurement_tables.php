<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Suppliers - The source of commodities
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // For ERP integration
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00); // Supplier performance tracking
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Purchase Orders (v2 enhancement: linked to specific internal departments/projects)
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->string('commodity_type'); // Moved from ENUM to string for flexibility
            $table->decimal('total_quantity_kg', 12, 2); // Standardized unit: KG
            $table->decimal('unit_price', 15, 2); // Higher precision for currency
            $table->decimal('total_amount', 18, 2);
            $table->date('delivery_deadline')->nullable();
            $table->enum('status', ['draft', 'issued', 'partially_fulfilled', 'fulfilled', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('suppliers');
    }
};
