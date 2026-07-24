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
        Schema::table('program_contents', function (Blueprint $table) {
            // Composite index untuk pencarian per section & key, dan pengecekan keaktifan
            $table->index(['section', 'key', 'is_active']);
            $table->index(['section', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_contents', function (Blueprint $table) {
            $table->dropIndex(['section', 'key', 'is_active']);
            $table->dropIndex(['section', 'sort_order']);
        });
    }
};
