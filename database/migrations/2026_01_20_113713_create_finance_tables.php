<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Finance Categories (Vast improvement over v1's enum)
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'income' or 'expense'
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Transactions - The unified financial record
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // e.g., INV-001, EXP-001
            $table->foreignId('category_id')->constrained('finance_categories');
            $table->decimal('amount', 18, 2);
            $table->string('currency', 3)->default('TZS');
            $table->date('transaction_date');

            // Polymorphic relation to link to anything (Batch, Dispatch, PO)
            $table->nullableMorphs('recordable');

            $table->string('payment_method'); // cash, bank_transfer, mobile_money
            $table->string('payee_payer_name')->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'approved', 'declined', 'reconciled'])->default('pending');
            $table->foreignId('recorded_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });

        // Audit Log for financial changes
        Schema::create('finance_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->string('action'); // created, status_change, amount_edit
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_audit_logs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('finance_categories');
    }
};
