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
        KonselorContact::firstOrCreate([
            'email' => 'siti.aminah@man1bandung.sch.id'
        ], [
            'name' => 'Dra. Hj. Siti Aminah, M.Pd.',
            'role' => 'Koordinator BK / Konselor Utama',
            'phone' => '081234567890',
            'institusi' => 'MAN 1 Kota Bandung',
            'room' => 'Ruang BK Lantai 1',
            'availability' => 'Senin - Jumat, 07:00 - 15:00',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        KonselorContact::firstOrCreate([
            'email' => 'budi.raharjo@man1bandung.sch.id'
        ], [
            'name' => 'Budi Raharjo, S.Pd., Kons.',
            'role' => 'Konselor BK Kelas XII',
            'phone' => '087766554433',
            'institusi' => 'MAN 1 Kota Bandung',
            'room' => 'Ruang BK Lantai 1',
            'availability' => 'Senin - Jumat, 07:00 - 15:00',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
