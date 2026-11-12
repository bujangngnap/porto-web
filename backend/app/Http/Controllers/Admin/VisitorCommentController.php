<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorComment;
use App\Support\MediaHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitorCommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status', 'all')->toString();
        $search = trim($request->string('q')->toString());

        $query = VisitorComment::latest('id');

        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'hidden') {
            $query->where('is_approved', false);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $comments = $query->paginate(12)->withQueryString();

        return view('admin.comments.index', compact('comments', 'status', 'search'));
    }

    public function toggleApproval(VisitorComment $comment): RedirectResponse
    {
        $comment->update([
            'is_approved' => ! $comment->is_approved,
        ]);

        return back()->with('success', $comment->is_approved ? 'Komentar ditampilkan di website.' : 'Komentar disembunyikan dari website.');
    }

    public function destroy(VisitorComment $comment): RedirectResponse
    {
        MediaHelper::deleteStoredPublicImage($comment->avatar_path);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
