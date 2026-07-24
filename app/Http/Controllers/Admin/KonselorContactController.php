<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KonselorContact;
use App\Http\Requests\StoreKonselorContactRequest;
use App\Http\Requests\UpdateKonselorContactRequest;
use Illuminate\Http\Request;

class KonselorContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = KonselorContact::orderBy('sort_order')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('admin.konselor.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.konselor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKonselorContactRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        KonselorContact::create($data);

        return redirect()
            ->route('admin.konselor.index')
            ->with('success', 'Kontak konselor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KonselorContact $konselor)
    {
        return view('admin.konselor.show', compact('konselor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KonselorContact $konselor)
    {
        return view('admin.konselor.edit', compact('konselor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKonselorContactRequest $request, KonselorContact $konselor)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $konselor->update($data);

        return redirect()
            ->route('admin.konselor.index')
            ->with('success', 'Kontak konselor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KonselorContact $konselor)
    {
        $konselor->delete();

        return redirect()
            ->route('admin.konselor.index')
            ->with('success', 'Kontak konselor berhasil dihapus permanen.');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggle(KonselorContact $konselor)
    {
        $konselor->update([
            'is_active' => !$konselor->is_active
        ]);

        return redirect()
            ->route('admin.konselor.index')
            ->with('success', 'Status keaktifan kontak konselor berhasil diubah.');
    }
}
