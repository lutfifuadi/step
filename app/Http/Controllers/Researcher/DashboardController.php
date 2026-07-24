<?php

namespace App\Http\Controllers\Researcher;

use App\Exports\ExpressionsExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expression;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $stats = Category::withCount(['expressions' => function ($q) {
            $q->approved();
        }])->get();

        $expressions = Expression::approved()
            ->with('category')
            ->when($request->category_id, fn ($q) => $q->where('category_id', $request->category_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('researcher.dashboard', compact('categories', 'stats', 'expressions'));
    }

    public function export(Request $request)
    {
        $filename = 'STEP_Ekspresi_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ExpressionsExport(
            categoryId: $request->category_id,
            status: 'approved',
            from: $request->from,
            to: $request->to
        ), $filename);
    }
}
