<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('note')->nullable()->after('customer_id');
            $table->string('material', 100)->nullable()->after('note');
            $table->date('deadline')->nullable()->after('material');
            $table->boolean('has_file_upload')->default(false)->after('deadline');
            $table->text('remarks')->nullable()->after('has_file_upload');
            $table->unsignedBigInteger('approved_by')->nullable()->after('remarks');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->boolean('is_finalized')->default(false)->after('approved_at');
            $table->timestamp('finalized_at')->nullable()->after('is_finalized');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->index('approved_by');
            $table->index('deadline');
            $table->index('is_finalized');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropIndex(['approved_by']);
            $table->dropIndex(['deadline']);
            $table->dropIndex(['is_finalized']);
            $table->dropColumn([
                'note', 'material', 'deadline', 'has_file_upload',
                'remarks', 'approved_by', 'approved_at', 'is_finalized', 'finalized_at',
            ]);
        });
    }
};
