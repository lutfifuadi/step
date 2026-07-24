<?php

namespace App\Services;

use App\Models\Expression;

class ExpressionModerationService
{
    protected array $riskyKeywords = [
        'bunuh diri', 'mati aja', 'tidak mau hidup', 'pengen mati',
        'nyakitin diri', 'nyakiti diri', 'menyakiti diri',
        'tidak ada yang peduli', 'nggak ada yang peduli', 'ga ada yang peduli',
        'ingin menghilang', 'mau kabur', 'putus asa',
        'dipukul', 'disakiti', 'kekerasan', 'diancam',
    ];

    public function checkRiskyContent(Expression $expression): void
    {
        $content = strtolower($expression->content);
        $foundWords = [];

        foreach ($this->riskyKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $foundWords[] = $keyword;
            }
        }

        if (! empty($foundWords)) {
            $expression->update([
                'is_risky' => true,
                'risk_keywords' => $foundWords,
                'status' => 'flagged',
            ]);
        }
    }
}
