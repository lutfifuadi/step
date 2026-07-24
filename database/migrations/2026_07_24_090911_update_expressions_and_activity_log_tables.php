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
        // 1. Cek & Tambah moderated_by, moderated_at, catatan_moderasi di expressions
        Schema::table('expressions', function (Blueprint $table) {
            if (!Schema::hasColumn('expressions', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('expressions', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable();
            }
            if (!Schema::hasColumn('expressions', 'catatan_moderasi')) {
                // Sesuai instruksi: "kolom moderated_by, moderated_at, dan catatan_moderasi"
                $table->text('catatan_moderasi')->nullable();
            }
        });

        // 2. Tambah index created_at dan causer_id di activity_log jika belum terindeks
        try {
            Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
                // Gunakan raw SQL or catch error if index already exists
                $table->index('created_at');
            });
        } catch (\Exception $e) {
            // Index already exists
        }

        try {
            Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
                $table->index('causer_id');
            });
        } catch (\Exception $e) {
            // Index already exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expressions', function (Blueprint $table) {
            if (Schema::hasColumn('expressions', 'catatan_moderasi')) {
                $table->dropColumn('catatan_moderasi');
            }
        });

        try {
            Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
                $table->dropIndex(['created_at']);
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
                $table->dropIndex(['causer_id']);
            });
        } catch (\Exception $e) {
        }
    }
};
