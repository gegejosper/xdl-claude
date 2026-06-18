<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('cashier_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('or_number', 50)->unique();
            $table->string('transaction_number', 50)->unique();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('payment_status', 20)->comment('0=unpaid,1=partially-paid,2=paid');
            $table->string('claim_status', 20)->comment('in-queue,canceled,claimed','ready')->default('in-queue');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            $table->foreign('cashier_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->restrictOnDelete();

            $table->index('branch_id');
            $table->index('cashier_id');
            $table->index('customer_id');
            $table->index('payment_status');
            $table->index('claim_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
