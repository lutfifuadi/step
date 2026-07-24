<?php

namespace Database\Seeders;

use App\Models\KonselorContact;
use Illuminate\Database\Seeder;

class KonselorContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding data awal konselor BK MAN 1 Kota Bandung
        KonselorContact::create([
            'name' => 'Dra. Hj. Siti Aminah, M.Pd.',
            'role' => 'Koordinator BK / Konselor Utama',
            'phone' => '081234567890',
            'email' => 'siti.aminah@man1bandung.sch.id',
            'institusi' => 'MAN 1 Kota Bandung',
            'room' => 'Ruang BK Lantai 1',
            'availability' => 'Senin - Jumat, 07:00 - 15:00',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        KonselorContact::create([
            'name' => 'Budi Raharjo, S.Pd., Kons.',
            'role' => 'Konselor BK Kelas XII',
            'phone' => '087766554433',
            'email' => 'budi.raharjo@man1bandung.sch.id',
            'institusi' => 'MAN 1 Kota Bandung',
            'room' => 'Ruang BK Lantai 1',
            'availability' => 'Senin - Jumat, 07:00 - 15:00',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
