<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // category: purchase | expense
    // type for purchase: tela, paper, ink, needle, thread, sorted, lining, others
    // type for expense: salary, transport, upload_load, snacks_meal, rent, utilities, commission, reports, others
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('added_by');
            $table->string('category', 20)->comment('purchase, expense');
            $table->string('type', 50)->comment('tela,paper,ink,needle,thread,sorted,lining,salary,transport,upload_load,snacks_meal,rent,utilities,commission,reports,others');
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('expense_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            $table->foreign('added_by')->references('id')->on('users')->restrictOnDelete();

            $table->index('branch_id');
            $table->index('added_by');
            $table->index('category');
            $table->index('type');
            $table->index('expense_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
