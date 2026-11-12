<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorComment;
use App\Support\MediaHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    public function index(): JsonResponse
    {
        $comments = VisitorComment::approved()
            ->latest('id')
            ->limit(100)
            ->get()
            ->map(fn (VisitorComment $comment) => $comment->toApiArray());

        return response()->json([
            'comments' => $comments,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'message' => ['required', 'string', 'min:2', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'message.required' => 'Komentar wajib diisi.',
            'message.min' => 'Komentar minimal 2 karakter.',
            'avatar.image' => 'File foto profil harus berupa gambar.',
            'avatar.max' => 'Ukuran foto profil maksimal 5MB.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = MediaHelper::storePublicImage($request->file('avatar'), 'avatars');
        }

        $comment = VisitorComment::create([
            'name' => trim($validated['name']),
            'message' => trim($validated['message']),
            'avatar_path' => $avatarPath,
            'is_approved' => true,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Komentar berhasil ditambahkan!',
            'comment' => $comment->toApiArray(),
        ], 201);
    }
}
