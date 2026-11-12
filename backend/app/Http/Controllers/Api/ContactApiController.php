<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'message' => ['required', 'string', 'min:3', 'max:3000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        ContactMessage::create([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'message' => trim($validated['message']),
            'is_read' => false,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Pesan berhasil dikirim ke dashboard! Terima kasih.',
        ], 201);
    }
}
