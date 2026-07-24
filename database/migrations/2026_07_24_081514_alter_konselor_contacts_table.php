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
        Schema::table('konselor_contacts', function (Blueprint $table) {
            // Kita ubah/sesuaikan dengan kebutuhan PRD
            // PRD: nama, jabatan, institusi, telepon, email, jam_layanan, sort_order, is_active
            // Di tabel existing:
            // - id
            // - name (ini dipetakan ke nama)
            // - role (ini dipetakan ke jabatan, nullable)
            // - phone (ini dipetakan ke telepon, nullable)
            // - email (nullable)
            // - room (nullable)
            // - availability (ini dipetakan ke jam_layanan, nullable)
            // - is_active (default true)
            // - timestamps
            
            // Kita perlu menambahkan:
            // - institusi (nullable / default 'MAN 1 Kota Bandung')
            // - sort_order (int default 0)
            
            if (!Schema::hasColumn('konselor_contacts', 'institusi')) {
                $table->string('institusi')->nullable()->after('email');
            }
            if (!Schema::hasColumn('konselor_contacts', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konselor_contacts', function (Blueprint $table) {
            if (Schema::hasColumn('konselor_contacts', 'institusi')) {
                $table->dropColumn('institusi');
            }
            if (Schema::hasColumn('konselor_contacts', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
