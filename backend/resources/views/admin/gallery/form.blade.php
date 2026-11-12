@extends('admin.layouts.app')

@section('title', $photo->exists ? 'Edit Foto Galeri' : 'Tambah Foto Galeri')
@section('page_title', $photo->exists ? 'Edit Foto: ' . $photo->title : 'Tambah Foto ke Galeri')

@section('content')
    <div class="max-w-2xl bg-[#18181b] rounded-3xl p-6 sm:p-8 border border-white/[0.07]">
        <form
            method="POST"
            action="{{ $photo->exists ? route('admin.gallery.update', $photo) : route('admin.gallery.store') }}"
            enctype="multipart/form-data"
            class="space-y-5"
        >
            @csrf
            @if ($photo->exists)
                @method('PUT')
            @endif

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Judul Foto</label>
                <input
                    type="text"
                    name="title"
                    required
                    value="{{ old('title', $photo->title) }}"
                    placeholder="Contoh: Kebersamaan Tim di BTM AMMAN"
                    class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Kategori</label>
                    <input
                        type="text"
                        name="category"
                        required
                        value="{{ old('category', $photo->category) }}"
                        placeholder="Teamwork & Moments"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Urutan Tampil</label>
                    <input
                        type="number"
                        name="sort_order"
                        min="0"
                        value="{{ old('sort_order', $photo->sort_order) }}"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Rasio Kartu</label>
                    <select name="aspect_class" class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none">
                        <option value="aspect-[4/5]" @selected(old('aspect_class', $photo->aspect_class) === 'aspect-[4/5]')>Portrait (4:5)</option>
                        <option value="aspect-[4/3]" @selected(old('aspect_class', $photo->aspect_class) === 'aspect-[4/3]')>Landscape (4:3)</option>
                        <option value="aspect-square" @selected(old('aspect_class', $photo->aspect_class) === 'aspect-square')>Square (1:1)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Skala Lebar (0.8 – 1.6)</label>
                    <input
                        type="number"
                        step="0.02"
                        min="0.8"
                        max="1.6"
                        name="width_scale"
                        value="{{ old('width_scale', $photo->width_scale ?: 1) }}"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Fokus Posisi Crop</label>
                    <input
                        type="text"
                        name="object_position"
                        value="{{ old('object_position', $photo->object_position ?: '50% 50%') }}"
                        placeholder="50% 50%"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Upload File Foto (Max 5MB)</label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="w-full px-3 py-2.5 rounded-xl bg-[#202025] border border-white/[0.08] text-xs text-zinc-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-zinc-100 file:text-zinc-950 file:font-semibold cursor-pointer"
                    >
                    @if ($photo->src)
                        <div class="mt-2 flex items-center gap-3">
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($photo->src) }}" alt="Preview" class="w-16 h-12 rounded-lg object-cover border border-white/10">
                            <span class="text-[11px] text-zinc-500 truncate">{{ $photo->src }}</span>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Atau URL / Path Foto</label>
                    <input
                        type="text"
                        name="src_url"
                        value="{{ old('src_url', $photo->src) }}"
                        placeholder="/gallery/nama-foto.jpg"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                    >
                </div>
            </div>

            <div class="pt-4 border-t border-white/[0.06] flex items-center gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-sm font-semibold cursor-pointer">
                    {{ $photo->exists ? 'Simpan Perubahan' : 'Simpan Foto' }}
                </button>
                <a href="{{ route('admin.gallery.index') }}" class="px-5 py-3 rounded-xl border border-white/[0.08] text-sm font-medium text-zinc-400 hover:bg-white/[0.04] hover:text-zinc-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
