<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expression;
use Illuminate\Http\Request;

class ExpressionController extends Controller
{
    public function index(Request $request)
    {
        $query = Expression::with('category', 'moderator')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $expressions = $query->paginate(20)->withQueryString();

        return view('admin.expressions.index', compact('expressions'));
    }

    public function show(Expression $expression)
    {
        $realName = $expression->real_name_decrypted;

        return view('admin.expressions.show', compact('expression', 'realName'));
    }

    public function approve(Expression $expression)
    {
        $expression->update([
            'status' => 'approved',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        activity()->causedBy(auth()->user())->performedOn($expression)->log('Ekspresi disetujui');

        return back()->with('success', 'Ekspresi berhasil disetujui.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|max:50',
            'ids.*' => 'exists:expressions,id',
        ]);

        $ids = $request->input('ids');

        \Illuminate\Support\Facades\DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $expression = Expression::find($id);
                if ($expression) {
                    $expression->update([
                        'status' => 'approved',
                        'moderated_by' => auth()->id(),
                        'moderated_at' => now(),
                    ]);

                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($expression)
                        ->log('Ekspresi disetujui');
                }
            }
        });

        return back()->with('success', 'Ekspresi yang dipilih berhasil disetujui.');
    }

    public function flag(Expression $expression, Request $request)
    {
        $request->validate([
            'note' => 'required|string|min:10',
        ]);

        $expression->update([
            'status' => 'flagged',
            'moderation_note' => $request->input('note'),
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        activity()->causedBy(auth()->user())->performedOn($expression)->log('Ekspresi diflag: ' . $request->input('note'));

        return redirect()->route('admin.expressions.show', $expression)->with('success', 'Ekspresi berhasil diflag.');
    }

    public function destroy(Expression $expression)
    {
        $expression->delete();

        activity()->causedBy(auth()->user())->log('Ekspresi dihapus ID: ' . $expression->id);

        return redirect()->route('admin.expressions.index')->with('success', 'Ekspresi berhasil dihapus.');
    }
}
