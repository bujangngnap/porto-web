@extends('admin.layouts.app')

@section('title', 'Kelola Galeri')
@section('page_title', 'Galeri Foto 3D')
@section('page_subtitle', 'Foto yang tampil pada bagian Spiral Photo Gallery portofolio.')

@section('header_actions')
    <a href="{{ route('admin.gallery.create') }}" class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold transition-colors">
        + Tambah Foto
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($photos as $photo)
            <div class="bg-[#18181b] rounded-3xl border border-white/[0.07] overflow-hidden flex flex-col">
                <div class="relative aspect-[4/3] bg-[#121215] overflow-hidden">
                    <img
                        src="{{ \App\Support\MediaHelper::adminPreviewUrl($photo->src) }}"
                        alt="{{ $photo->title }}"
                        class="w-full h-full object-cover"
                        style="object-position: {{ $photo->object_position }}"
                    >
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-black/75 text-zinc-100 border border-white/10 backdrop-blur-sm">
                        #{{ $photo->sort_order }} · {{ $photo->aspect_class }}
                    </span>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[11px] font-medium text-zinc-400">{{ $photo->category }}</div>
                        <h3 class="mt-0.5 font-display font-bold text-sm text-zinc-100">{{ $photo->title }}</h3>
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/[0.06] flex items-center justify-end gap-2">
                        <a href="{{ route('admin.gallery.edit', $photo) }}" class="px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-zinc-100 hover:bg-white text-zinc-950">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.gallery.destroy', $photo) }}" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-[11px] font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-[#18181b] rounded-3xl p-12 border border-white/[0.07] text-center text-sm text-zinc-500">
                Belum ada foto di galeri.
            </div>
        @endforelse
    </div>
@endsection
