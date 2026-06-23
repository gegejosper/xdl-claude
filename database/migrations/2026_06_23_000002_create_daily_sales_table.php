<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->date('sales_date');
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_payments', 12, 2)->default(0);
            $table->unsignedBigInteger('transaction_count')->default(0);
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('close_type', ['manual', 'auto'])->default('manual');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'sales_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};
