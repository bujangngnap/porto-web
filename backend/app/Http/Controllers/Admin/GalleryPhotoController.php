<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Support\MediaHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryPhotoController extends Controller
{
    public function index(): View
    {
        $photos = GalleryPhoto::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.gallery.index', compact('photos'));
    }

    public function create(): View
    {
        return view('admin.gallery.form', [
            'photo' => new GalleryPhoto([
                'aspect_class' => 'aspect-[4/5]',
                'width_scale' => 1,
                'object_position' => '50% 50%',
                'sort_order' => (GalleryPhoto::max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['src'] = MediaHelper::storePublicImage($request->file('image'), 'gallery');
        } else {
            $data['src'] = $data['src_url'];
        }
        unset($data['src_url'], $data['image']);

        GalleryPhoto::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(GalleryPhoto $gallery): View
    {
        return view('admin.gallery.form', ['photo' => $gallery]);
    }

    public function update(Request $request, GalleryPhoto $gallery): RedirectResponse
    {
        $data = $this->validatedData($request, isUpdate: true);

        if ($request->hasFile('image')) {
            MediaHelper::deleteStoredPublicImage($gallery->src);
            $data['src'] = MediaHelper::storePublicImage($request->file('image'), 'gallery');
        } elseif (! empty($data['src_url'])) {
            $data['src'] = $data['src_url'];
        } else {
            $data['src'] = $gallery->src;
        }
        unset($data['src_url'], $data['image']);

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(GalleryPhoto $gallery): RedirectResponse
    {
        MediaHelper::deleteStoredPublicImage($gallery->src);
        $gallery->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    private function validatedData(Request $request, bool $isUpdate = false): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:100'],
            'aspect_class' => ['required', Rule::in(['aspect-[4/5]', 'aspect-[4/3]', 'aspect-square'])],
            'width_scale' => ['nullable', 'numeric', 'min:0.8', 'max:1.6'],
            'object_position' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'src_url' => ['nullable', 'string', 'max:500'],
            'image' => [$isUpdate ? 'nullable' : 'required_without:src_url', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'image.required_without' => 'Upload file gambar atau isi path/URL gambar.',
        ]);

        return [
            'title' => trim($validated['title']),
            'category' => trim($validated['category']),
            'aspect_class' => $validated['aspect_class'],
            'width_scale' => $validated['aspect_class'] === 'aspect-[4/3]'
                ? (float) ($validated['width_scale'] ?? 1.28)
                : (float) ($validated['width_scale'] ?? 1.0),
            'object_position' => trim((string) ($validated['object_position'] ?? '')) ?: '50% 50%',
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'src_url' => trim((string) ($validated['src_url'] ?? '')),
        ];
    }
}
