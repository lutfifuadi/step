<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpressionRequest;
use App\Models\Category;
use App\Models\Expression;
use App\Models\KonselorContact;
use App\Services\ExpressionModerationService;
use Illuminate\Http\Request;

class ExpressionController extends Controller
{
    public function __construct(private ExpressionModerationService $moderationService)
    {
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $featured = Expression::approved()->featured()->with('category')->latest()->take(3)->get();
        $konselorContacts = KonselorContact::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'asc')->get();

        return view('ekspresi.create', compact('categories', 'featured', 'konselorContacts'));
    }

    public function store(ExpressionRequest $request)
    {
        $data = $request->validated();

        $isAnonymous = $request->boolean('is_anonymous');
        $displayName = $isAnonymous ? 'Anonim' : ($data['real_name'] ?? 'Anonim');

        $expression = Expression::create([
            'category_id' => $data['category_id'],
            'user_id' => auth()->id(),
            'is_anonymous' => $isAnonymous,
            'display_name' => $displayName,
            'real_name' => $isAnonymous ? null : ($data['real_name'] ?? null),
            'origin' => $data['origin'] ?? null,
            'content' => $data['content'],
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_agreed_at' => now(),
        ]);

        $this->moderationService->checkRiskyContent($expression);

        return redirect()->route('ekspresi.success')->with('success', 'Ekspresimu berhasil terkirim!');
    }

    public function success()
    {
        $konselorContacts = KonselorContact::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'asc')->get();
        return view('ekspresi.success', compact('konselorContacts'));
    }
}
