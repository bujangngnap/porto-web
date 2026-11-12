@extends('admin.layouts.app')

@section('title', 'Kelola Proyek')
@section('page_title', 'Proyek Portofolio')
@section('page_subtitle', 'Tambah, edit, atau atur urutan proyek Website, Aplikasi, dan Desain.')

@section('header_actions')
    <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold transition-colors">
        + Tambah Proyek
    </a>
@endsection

@section('content')
    <div class="flex flex-wrap items-center gap-2">
        @foreach (['all' => 'Semua', 'website' => 'Website', 'aplikasi' => 'Aplikasi', 'desain' => 'Desain'] as $key => $label)
            <a
                href="{{ route('admin.projects.index', $key === 'all' ? [] : ['tab' => $key]) }}"
                class="px-4 py-2 rounded-xl text-xs font-medium transition-colors {{ $tab === $key ? 'bg-[#26262b] text-white border border-white/10 shadow-sm' : 'bg-[#18181b] text-zinc-400 border border-white/[0.07] hover:text-zinc-200' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($projects as $project)
            <div class="bg-[#18181b] rounded-3xl border border-white/[0.07] overflow-hidden flex flex-col">
                <div class="relative aspect-[16/10] bg-[#121215] overflow-hidden">
                    <img
                        src="{{ \App\Support\MediaHelper::adminPreviewUrl($project->img) }}"
                        alt="{{ $project->title }}"
                        class="w-full h-full object-cover"
                        style="object-position: {{ $project->img_object_position }}"
                    >
                    <div class="absolute top-3 left-3 flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase bg-black/75 text-zinc-100 border border-white/10 backdrop-blur-sm">
                            {{ $project->tab }}
                        </span>
                        @if ($project->featured)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-zinc-100 text-zinc-950">
                                Featured
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <div class="text-[11px] font-medium text-zinc-400 mb-1">{{ $project->category }} · Urutan #{{ $project->sort_order }}</div>
                    <h3 class="font-display font-bold text-base text-zinc-100 line-clamp-1">{{ $project->title }}</h3>
                    <p class="mt-1.5 text-xs text-zinc-400 leading-relaxed line-clamp-2">{{ $project->description }}</p>

                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach (array_slice($project->tags ?? [], 0, 3) as $tag)
                            <span class="px-2 py-0.5 rounded-md bg-[#202025] border border-white/[0.06] text-zinc-300 text-[11px] font-medium">{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-4 border-t border-white/[0.06] flex items-center justify-between gap-2">
                        <form method="POST" action="{{ route('admin.projects.toggle-featured', $project) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1.5 rounded-lg text-[11px] font-medium bg-[#202025] border border-white/[0.08] hover:bg-[#2a2a30] text-zinc-300 cursor-pointer">
                                {{ $project->featured ? 'Unfeature' : 'Jadikan Featured' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-zinc-100 hover:bg-white text-zinc-950">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Hapus proyek {{ $project->title }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-[11px] font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-[#18181b] rounded-3xl p-12 border border-white/[0.07] text-center text-sm text-zinc-500">
                Belum ada proyek di kategori ini.
            </div>
        @endforelse
    </div>
@endsection
