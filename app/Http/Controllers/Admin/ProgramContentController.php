<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramContent;
use App\Http\Requests\UpdateProgramContentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProgramContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProgramContent::query();

        if ($request->has('section') && !empty($request->section)) {
            $query->where('section', $request->section);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $sections = ProgramContent::select('section')->distinct()->pluck('section');

        $contents = $query->orderBy('section')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.program_contents.index', compact('contents', 'sections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramContent $programContent)
    {
        return view('admin.program_contents.edit', compact('programContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgramContentRequest $request, ProgramContent $programContent)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $data['updated_by'] = auth()->id();

        $programContent->update($data);

        // Invalidate Cache for this section
        Cache::forget("program_contents_{$programContent->section}");

        return redirect()
            ->route('admin.program-contents.index', ['section' => $programContent->section])
            ->with('success', 'Konten Landing Page berhasil diperbarui.');
    }
}
