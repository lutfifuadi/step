<?php

namespace Database\Seeders;

use App\Models\Expression;
use Illuminate\Database\Seeder;

class FeaturedExpressionSeeder extends Seeder
{
    public function run(): void
    {
        $harapan = \App\Models\Category::where('slug', 'harapan')->first();
        $perasaan = \App\Models\Category::where('slug', 'perasaan')->first();
        $saran = \App\Models\Category::where('slug', 'saran')->first();

        if (!$harapan || !$perasaan || !$saran) {
            $this->command->warn('Kategori yang diperlukan belum ada. Pastikan CategorySeeder sudah dijalankan.');
            return;
        }

        $expressions = [
            [
                'category_id' => $harapan->id,
                'is_anonymous' => true,
                'display_name' => 'Anonim',
                'origin' => 'Bandung',
                'content' => 'Aku berharap suatu hari ayah bisa meluangkan waktu untuk mendengarkan cerita-ceritaku yang mungkin terlihat sepele baginya, tapi sangat berarti untukku.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'category_id' => $perasaan->id,
                'is_anonymous' => true,
                'display_name' => 'Anonim',
                'origin' => 'Bandung',
                'content' => 'Kadang aku merasa sepi walau ayah ada di rumah. Kami satu atap tapi rasanya jauh sekali. Entah bagaimana cara menjembataninya.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'category_id' => $saran->id,
                'is_anonymous' => true,
                'display_name' => 'Anonim',
                'origin' => 'Bandung',
                'content' => 'Mungkin program ini bisa mengadakan sesi khusus yang bisa diikuti bareng-bareng sama ayah. Supaya kita bisa belajar ngobrol lagi.',
                'status' => 'approved',
                'is_featured' => true,
            ],
        ];

        foreach ($expressions as $expression) {
            Expression::firstOrCreate([
                'content' => $expression['content']
            ], $expression);
        }
    }
}
