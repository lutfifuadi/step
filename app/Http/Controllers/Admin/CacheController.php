<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function clear()
    {
        // Clear various caches
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('event:clear');
        Artisan::call('optimize:clear');

        // Re-cache config and routes
        Artisan::call('config:cache');
        Artisan::call('route:cache');

        return back()->with('success', 'Cache aplikasi berhasil dibersihkan! Semua pengaturan dan CSS sudah diperbarui.');
    }
}
