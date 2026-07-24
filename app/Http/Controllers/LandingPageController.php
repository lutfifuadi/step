<?php

namespace App\Http\Controllers;

use App\Models\ProgramContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    /**
     * Helper to get active program contents by section (cached for 10 minutes)
     */
    private function getSectionContents(string $section)
    {
        return Cache::remember("program_contents_{$section}", 600, function () use ($section) {
            return ProgramContent::where('section', $section)
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get()
                ->keyBy('key');
        });
    }

    public function index()
    {
        // Ambil data per section
        $hero = $this->getSectionContents('home_hero');
        $stats = $this->getSectionContents('home_hero_stats');
        $cards = $this->getSectionContents('home_hero_cards');
        $featuresHeader = $this->getSectionContents('home_features_header');
        $bento = $this->getSectionContents('home_bento_cards');
        $cta = $this->getSectionContents('home_cta');

        return view('content.pages.pages-home', compact('hero', 'stats', 'cards', 'featuresHeader', 'bento', 'cta'));
    }

    public function tentang()
    {
        $hero = $this->getSectionContents('about_hero');
        $content = $this->getSectionContents('about_content');
        $missions = $this->getSectionContents('about_missions');

        return view('content.pages.pages-about', compact('hero', 'content', 'missions'));
    }

    public function edukasi()
    {
        $hero = $this->getSectionContents('education_hero');
        $cards = $this->getSectionContents('education_cards');
        $footer = $this->getSectionContents('education_footer');

        return view('content.pages.pages-education', compact('hero', 'cards', 'footer'));
    }

    public function pencegahan()
    {
        $hero = $this->getSectionContents('prevention_hero');
        $main = $this->getSectionContents('prevention_main');
        $steps = $this->getSectionContents('prevention_steps');
        $cards = $this->getSectionContents('prevention_cards');

        return view('content.pages.pages-prevention', compact('hero', 'main', 'steps', 'cards'));
    }
}
