@extends('admin.layouts.app')

@section('title', $project->exists ? 'Edit Proyek' : 'Tambah Proyek')
@section('page_title', $project->exists ? 'Edit Proyek: ' . $project->title : 'Tambah Proyek Baru')

@section('content')
    <div class="max-w-3xl bg-[#18181b] rounded-3xl p-6 sm:p-8 border border-white/[0.07]">
        <form
            method="POST"
            action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
            enctype="multipart/form-data"
            class="space-y-5"
        >
            @csrf
            @if ($project->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Tab Kategori</label>
                    <select name="tab" class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none">
                        @foreach (['website' => 'Website', 'aplikasi' => 'Aplikasi', 'desain' => 'Desain'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('tab', $project->tab) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Label Kategori</label>
                    <input
                        type="text"
                        name="category"
                        required
                        value="{{ old('category', $project->category) }}"
                        placeholder="misal: Corporate & Agency Web"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Urutan Tampil</label>
                    <input
                        type="number"
                        name="sort_order"
                        min="0"
                        value="{{ old('sort_order', $project->sort_order) }}"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Judul Proyek</label>
                <input
                    type="text"
                    name="title"
                    required
                    value="{{ old('title', $project->title) }}"
                    placeholder="Contoh: SSB Garuda Muda Website"
                    class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Deskripsi Singkat (Kartu)</label>
                <textarea
                    name="description"
                    rows="3"
                    required
                    class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                >{{ old('description', $project->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Deskripsi Lengkap (Halaman Detail, Opsional)</label>
                <textarea
                    name="long_description"
                    rows="4"
                    class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                >{{ old('long_description', $project->long_description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Teknologi / Tags (pisahkan dengan koma)</label>
                    <input
                        type="text"
                        name="tags_input"
                        value="{{ old('tags_input', implode(', ', $project->tags ?? [])) }}"
                        placeholder="Laravel, React.js, Tailwind CSS, MySQL"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Link Website Live (Opsional)</label>
                    <input
                        type="text"
                        name="link"
                        value="{{ old('link', $project->link) }}"
                        placeholder="https://..."
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Upload Gambar Proyek (Max 5MB)</label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="w-full px-3 py-2.5 rounded-xl bg-[#202025] border border-white/[0.08] text-xs text-zinc-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-zinc-100 file:text-zinc-950 file:font-semibold cursor-pointer"
                    >
                    @if ($project->img)
                        <div class="mt-2 flex items-center gap-3">
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($project->img) }}" alt="Preview" class="w-16 h-12 rounded-lg object-cover border border-white/10">
                            <span class="text-[11px] text-zinc-500 truncate">{{ $project->img }}</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Atau URL / Path Gambar</label>
                        <input
                            type="text"
                            name="img_url"
                            value="{{ old('img_url', $project->img) }}"
                            placeholder="/projects/nama-file.jpg"
                            class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Posisi Crop (CSS object-position)</label>
                        <input
                            type="text"
                            name="img_object_position"
                            value="{{ old('img_object_position', $project->img_object_position ?: '50% 50%') }}"
                            placeholder="50% 50%"
                            class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                        >
                    </div>
                </div>
            </div>

            <label class="inline-flex items-center gap-2.5 text-sm font-medium text-zinc-300 cursor-pointer">
                <input type="checkbox" name="featured" value="1" @checked(old('featured', $project->featured)) class="rounded border-white/20 bg-[#202025] text-zinc-100">
                <span>Tampilkan sebagai Proyek Unggulan (Featured)</span>
            </label>

            <div class="pt-4 border-t border-white/[0.06] flex items-center gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-sm font-semibold cursor-pointer">
                    {{ $project->exists ? 'Simpan Perubahan' : 'Simpan Proyek' }}
                </button>
                <a href="{{ route('admin.projects.index') }}" class="px-5 py-3 rounded-xl border border-white/[0.08] text-sm font-medium text-zinc-400 hover:bg-white/[0.04] hover:text-zinc-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
