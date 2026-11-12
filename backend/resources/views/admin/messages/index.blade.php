@extends('admin.layouts.app')

@section('title', 'Pesan Masuk')
@section('page_title', 'Pesan Masuk (Inbox)')
@section('page_subtitle', 'Semua pesan yang dikirim pengunjung melalui form Hubungi Saya.')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2">
            @foreach (['all' => 'Semua', 'unread' => 'Belum Dibaca', 'read' => 'Sudah Dibaca'] as $key => $label)
                <a
                    href="{{ route('admin.messages.index', array_filter(['status' => $key, 'q' => $search])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-medium transition-colors {{ $status === $key ? 'bg-[#26262b] text-white border border-white/10 shadow-sm' : 'bg-[#18181b] text-zinc-400 border border-white/[0.07] hover:text-zinc-200' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.messages.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari nama, email, isi..."
                class="w-full sm:w-64 px-3.5 py-2 rounded-xl bg-[#18181b] border border-white/[0.08] text-xs text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none"
            >
            <button type="submit" class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold cursor-pointer">
                Cari
            </button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse ($messages as $msg)
            <div class="bg-[#18181b] rounded-2xl p-5 sm:p-6 border {{ $msg->is_read ? 'border-white/[0.07]' : 'border-zinc-500/50 ring-1 ring-white/[0.06]' }}">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="space-y-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-display font-bold text-base text-zinc-100">{{ $msg->name }}</h3>
                            @if (! $msg->is_read)
                                <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-rose-500/15 text-rose-300 border border-rose-500/20">Belum Dibaca</span>
                            @else
                                <span class="px-2.5 py-0.5 text-[11px] font-medium rounded-full bg-[#202025] text-zinc-400 border border-white/[0.07]">Sudah Dibaca</span>
                            @endif
                        </div>
                        <div class="text-xs text-zinc-400 flex flex-wrap items-center gap-2">
                            <a href="mailto:{{ $msg->email }}" class="font-medium text-zinc-200 hover:underline">{{ $msg->email }}</a>
                            <span class="text-zinc-600">•</span>
                            <span class="text-zinc-500">{{ $msg->created_at?->format('d M Y, H:i') }} ({{ $msg->created_at?->diffForHumans() }})</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                        <a
                            href="mailto:{{ $msg->email }}?subject={{ rawurlencode('Balasan dari Muhammad Raihan Al Ashr') }}"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-zinc-100 hover:bg-white text-zinc-950 transition-colors"
                        >
                            Balas Email
                        </a>

                        <form method="POST" action="{{ route('admin.messages.toggle-read', $msg) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-medium bg-[#202025] border border-white/[0.08] hover:bg-[#2a2a30] text-zinc-200 cursor-pointer">
                                {{ $msg->is_read ? 'Tandai Belum Baca' : 'Tandai Dibaca' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Hapus pesan ini permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-xl bg-[#121215] border border-white/[0.06] text-sm text-zinc-300 leading-relaxed whitespace-pre-line">{{ $msg->message }}</div>
            </div>
        @empty
            <div class="bg-[#18181b] rounded-3xl p-12 border border-white/[0.07] text-center text-sm text-zinc-500">
                Belum ada pesan yang cocok dengan filter ini.
            </div>
        @endforelse
    </div>

    @if ($messages->hasPages())
        <div class="pt-2">
            {{ $messages->links() }}
        </div>
    @endif
@endsection
