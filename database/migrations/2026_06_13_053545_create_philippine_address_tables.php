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
       // database/migrations/xxxx_create_philippine_address_tables.php
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('psgc_code')->nullable();
            $table->text('reg_desc')->nullable();
            $table->string('reg_code')->nullable();
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('psgc_code')->nullable();
            $table->text('prov_desc')->nullable();
            $table->string('reg_code')->nullable();
            $table->string('prov_code')->nullable();
        });

        Schema::create('citymunicipalities', function (Blueprint $table) {
            $table->id();
            $table->string('psgc_code')->nullable();
            $table->text('citymun_desc')->nullable();
            $table->string('reg_desc')->nullable();
            $table->string('prov_code')->nullable();
            $table->string('citymun_code')->nullable();
        });

        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('brgy_code')->nullable();
            $table->text('brgy_desc')->nullable();
            $table->string('reg_code')->nullable();
            $table->string('prov_code')->nullable();
            $table->string('citymun_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('philippine_address_tables');
    }
};
