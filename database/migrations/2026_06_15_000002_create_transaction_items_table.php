<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // item_type values: tshirt, jersey_upper, jersey_lower, polo_shirt, jogging_pants,
    //                   jacket, long_sleeves, dtf, bags, tarpaulin, others
    // size values: XS, S, M, L, XL, XXL, KIDS, NO_SIZE, CUSTOM
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->string('item_type', 50);
            $table->string('size', 30)->nullable()->comment('XS,S,M,L,XL,XXL,KIDS,NO_SIZE,CUSTOM');
            $table->string('material', 100)->nullable();
            $table->decimal('width', 8, 2)->nullable()->comment('for tarpaulin width in ft');
            $table->decimal('height', 8, 2)->nullable()->comment('for tarpaulin height in ft');
            $table->decimal('sqft', 8, 2)->nullable()->comment('computed width x height');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
            $table->index('transaction_id');
            $table->index('item_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
