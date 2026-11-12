@extends('admin.layouts.app')

@section('title', 'Komentar Pengunjung')
@section('page_title', 'Moderasi Komentar Pengunjung')
@section('page_subtitle', 'Tampilkan, sembunyikan, atau hapus komentar beserta foto profil pengunjung.')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            @foreach (['all' => 'Semua', 'approved' => 'Ditampilkan (Live)', 'hidden' => 'Disembunyikan'] as $key => $label)
                <a
                    href="{{ route('admin.comments.index', array_filter(['status' => $key, 'q' => $search])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-medium transition-colors {{ $status === $key ? 'bg-[#26262b] text-white border border-white/10 shadow-sm' : 'bg-[#18181b] text-zinc-400 border border-white/[0.07] hover:text-zinc-200' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.comments.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari nama atau isi komentar..."
                class="w-full sm:w-64 px-3.5 py-2 rounded-xl bg-[#18181b] border border-white/[0.08] text-xs text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
            >
            <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold cursor-pointer">
                Cari
            </button>
        </form>
    </div>

    <div class="space-y-3.5">
        @forelse ($comments as $comment)
            <div class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4 min-w-0">
                    @if ($comment->avatar_path)
                        <img
                            src="{{ \App\Support\MediaHelper::adminPreviewUrl($comment->avatar_path) }}"
                            alt="{{ $comment->name }}"
                            class="w-12 h-12 rounded-full object-cover border border-white/15 flex-shrink-0"
                        >
                    @else
                        <div class="w-12 h-12 rounded-full bg-[#26262b] border border-white/10 text-zinc-100 font-bold text-sm flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($comment->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-display font-bold text-sm text-zinc-100">{{ $comment->name }}</span>
                            <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $comment->is_approved ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/20' : 'bg-[#202025] text-zinc-400 border border-white/[0.07]' }}">
                                {{ $comment->is_approved ? 'Live di Web' : 'Disembunyikan' }}
                            </span>
                            <span class="text-xs text-zinc-500">{{ $comment->created_at?->format('d M Y H:i') }}</span>
                        </div>
                        <p class="text-sm text-zinc-300 leading-relaxed">{{ $comment->message }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.comments.toggle', $comment) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="px-3.5 py-2 rounded-xl text-xs font-medium bg-[#202025] border border-white/[0.08] hover:bg-[#2a2a30] text-zinc-200 cursor-pointer"
                        >
                            {{ $comment->is_approved ? 'Sembunyikan' : 'Tampilkan' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Hapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="px-3.5 py-2 rounded-xl text-xs font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer"
                        >
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-[#18181b] rounded-3xl p-12 border border-white/[0.07] text-center text-sm text-zinc-500">
                Belum ada komentar yang ditemukan.
            </div>
        @endforelse
    </div>

    @if ($comments->hasPages())
        <div class="pt-2">
            {{ $comments->links() }}
        </div>
    @endif
@endsection
