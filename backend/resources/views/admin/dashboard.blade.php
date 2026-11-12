@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Pantau pesan masuk, komentar pengunjung, dan kelola seluruh konten portofolio.')

@section('header_actions')
    <a href="{{ route('admin.portfolio.index') }}" class="px-4 py-2 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold transition-colors">
        Edit Portofolio
    </a>
@endsection

@section('content')
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        <a href="{{ route('admin.messages.index') }}" class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] hover:border-white/20 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Pesan Masuk</span>
                @if ($stats['messages_unread'] > 0)
                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-rose-500/15 text-rose-300 border border-rose-500/20">
                        {{ $stats['messages_unread'] }} Baru
                    </span>
                @endif
            </div>
            <div class="mt-3 text-3xl font-display font-extrabold text-white">{{ $stats['messages_total'] }}</div>
            <p class="mt-1 text-xs text-zinc-500">Dari form Hubungi Saya</p>
        </a>

        <a href="{{ route('admin.comments.index') }}" class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] hover:border-white/20 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Komentar Live</span>
                <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/20">
                    {{ $stats['comments_approved'] }} Tampil
                </span>
            </div>
            <div class="mt-3 text-3xl font-display font-extrabold text-white">{{ $stats['comments_total'] }}</div>
            <p class="mt-1 text-xs text-zinc-500">Total komentar tersimpan</p>
        </a>

        <a href="{{ route('admin.projects.index') }}" class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] hover:border-white/20 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Total Proyek</span>
                <span class="text-[11px] font-medium text-zinc-400">
                    {{ $stats['projects_website'] }} Web · {{ $stats['projects_aplikasi'] }} App · {{ $stats['projects_desain'] }} Desain
                </span>
            </div>
            <div class="mt-3 text-3xl font-display font-extrabold text-white">{{ $stats['projects_total'] }}</div>
            <p class="mt-1 text-xs text-zinc-500">Sinkron otomatis ke React</p>
        </a>

        <a href="{{ route('admin.gallery.index') }}" class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] hover:border-white/20 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Galeri Foto</span>
                <span class="text-[11px] font-medium text-zinc-500">3D Spiral</span>
            </div>
            <div class="mt-3 text-3xl font-display font-extrabold text-white">{{ $stats['gallery_total'] }}</div>
            <p class="mt-1 text-xs text-zinc-500">Foto momen pribadi &amp; tim</p>
        </a>
    </div>

    <!-- Recent Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Contact Messages -->
        <div class="bg-[#18181b] rounded-3xl border border-white/[0.07] overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between">
                <h2 class="font-display font-bold text-sm sm:text-base text-zinc-100">Pesan Masuk Terbaru</h2>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-medium text-zinc-400 hover:text-white transition-colors">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-white/[0.06]">
                @forelse ($recentMessages as $msg)
                    <div class="p-5 hover:bg-white/[0.02] transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-sm text-zinc-100 truncate">{{ $msg->name }}</span>
                                    @if (! $msg->is_read)
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-rose-500/15 text-rose-300 border border-rose-500/20">Baru</span>
                                    @endif
                                </div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $msg->email }} · {{ $msg->created_at?->diffForHumans() }}</div>
                            </div>
                            <form method="POST" action="{{ route('admin.messages.toggle-read', $msg) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-medium bg-[#202025] border border-white/[0.08] hover:bg-[#2a2a30] text-zinc-300 cursor-pointer">
                                    {{ $msg->is_read ? 'Tandai Baru' : 'Sudah Baca' }}
                                </button>
                            </form>
                        </div>
                        <p class="mt-2.5 text-xs text-zinc-400 leading-relaxed line-clamp-2">{{ $msg->message }}</p>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">Belum ada pesan masuk.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Visitor Comments -->
        <div class="bg-[#18181b] rounded-3xl border border-white/[0.07] overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between">
                <h2 class="font-display font-bold text-sm sm:text-base text-zinc-100">Komentar Pengunjung Terbaru</h2>
                <a href="{{ route('admin.comments.index') }}" class="text-xs font-medium text-zinc-400 hover:text-white transition-colors">Kelola →</a>
            </div>
            <div class="divide-y divide-white/[0.06]">
                @forelse ($recentComments as $comment)
                    <div class="p-5 flex items-start gap-3.5 hover:bg-white/[0.02] transition-colors">
                        @if ($comment->avatar_path)
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($comment->avatar_path) }}" alt="{{ $comment->name }}" class="w-10 h-10 rounded-full object-cover border border-white/10 flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-[#26262b] border border-white/10 text-zinc-200 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($comment->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="font-semibold text-sm text-zinc-100 truncate">{{ $comment->name }}</span>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $comment->is_approved ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/20' : 'bg-zinc-800 text-zinc-400 border border-white/[0.06]' }}">
                                        {{ $comment->is_approved ? 'Live' : 'Hidden' }}
                                    </span>
                                </div>
                                <form method="POST" action="{{ route('admin.comments.toggle', $comment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-medium bg-[#202025] border border-white/[0.08] hover:bg-[#2a2a30] text-zinc-300 cursor-pointer">
                                        {{ $comment->is_approved ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>
                            </div>
                            <p class="mt-1.5 text-xs text-zinc-400 leading-relaxed">{{ $comment->message }}</p>
                            <div class="mt-1 text-[11px] text-zinc-500">{{ $comment->formattedDate() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-zinc-500">Belum ada komentar.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
