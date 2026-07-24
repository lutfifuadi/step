<?php

namespace Database\Seeders;

use App\Models\Expression;
use Illuminate\Database\Seeder;

class FeaturedExpressionSeeder extends Seeder
{
    public function run(): void
    {
        $expressions = [
            [
                'category_id' => 1,
                'is_anonymous' => true,
                'display_name' => 'Anonim',
                'origin' => 'Bandung',
                'content' => 'Aku berharap suatu hari ayah bisa meluangkan waktu untuk mendengarkan cerita-ceritaku yang mungkin terlihat sepele baginya, tapi sangat berarti untukku.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'category_id' => 3,
                'is_anonymous' => true,
                'display_name' => 'Anonim',
                'origin' => 'Bandung',
                'content' => 'Kadang aku merasa sepi walau ayah ada di rumah. Kami satu atap tapi rasanya jauh sekali. Entah bagaimana cara menjembataninya.',
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'category_id' => 4,
                'is_anonymous' => true,
                'display_name' => 'Anonim',
                'origin' => 'Bandung',
                'content' => 'Mungkin program ini bisa mengadakan sesi khusus yang bisa diikuti bareng-bareng sama ayah. Supaya kita bisa belajar ngobrol lagi.',
                'status' => 'approved',
                'is_featured' => true,
            ],
        ];

        foreach ($expressions as $expression) {
            Expression::create($expression);
        }
    }
}
