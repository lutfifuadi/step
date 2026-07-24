<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expression;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Expression::count(),
            'pending' => Expression::pending()->count(),
            'approved' => Expression::approved()->count(),
            'flagged' => Expression::flagged()->count(),
            'today' => Expression::whereDate('created_at', today())->count(),
            'users' => User::count(),
        ];

        $byCategory = Category::withCount(['expressions' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        $latestPending = Expression::pending()->with('category')->latest()->take(5)->get();
        $latestFlagged = Expression::flagged()->with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'byCategory', 'latestPending', 'latestFlagged'));
    }
}
