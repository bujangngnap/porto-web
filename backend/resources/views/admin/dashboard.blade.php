@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Pantau pengunjung website, pesan masuk, komentar, dan kelola seluruh konten portofolio.')

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

    <!-- Website Traffic Analytics Chart -->
    <div id="ashr-traffic-card" class="bg-[#18181b] rounded-3xl border border-white/[0.07] p-5 sm:p-6 space-y-5">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <h2 class="font-display font-bold text-sm sm:text-base text-zinc-100">Grafik Pengunjung Website</h2>
                </div>
                <p class="text-xs text-zinc-500">Jumlah tampilan halaman dan pengunjung unik harian (WIB).</p>
            </div>

            <div class="flex flex-wrap items-center justify-between lg:justify-end gap-3">
                <div class="flex items-center gap-4 text-xs">
                    <div>
                        <span class="text-zinc-500 block text-[10px] uppercase tracking-wider font-semibold">Hari Ini</span>
                        <span class="font-display font-bold text-sm text-white">{{ number_format($visitAnalytics['today_views']) }}</span>
                        <span class="text-[11px] text-zinc-400">({{ $visitAnalytics['today_unique'] }} unik)</span>
                    </div>
                    <div class="h-7 w-px bg-white/[0.07]"></div>
                    <div>
                        <span class="text-zinc-500 block text-[10px] uppercase tracking-wider font-semibold">7 Hari</span>
                        <span class="font-display font-bold text-sm text-white">{{ number_format($visitAnalytics['views_7d']) }}</span>
                    </div>
                    <div class="h-7 w-px bg-white/[0.07]"></div>
                    <div>
                        <span class="text-zinc-500 block text-[10px] uppercase tracking-wider font-semibold">Total Semua</span>
                        <span class="font-display font-bold text-sm text-white">{{ number_format($visitAnalytics['total_views']) }}</span>
                    </div>
                    @if ($visitAnalytics['total_views'] > 0)
                        <div class="hidden sm:block h-7 w-px bg-white/[0.07]"></div>
                        <div class="hidden sm:block">
                            <span class="text-zinc-500 block text-[10px] uppercase tracking-wider font-semibold">Perangkat</span>
                            <span class="text-xs font-medium text-zinc-300">{{ $visitAnalytics['desktop_pct'] }}% Desktop · {{ $visitAnalytics['mobile_pct'] }}% Mobile</span>
                        </div>
                    @endif
                </div>

                <div class="inline-flex items-center p-1 rounded-xl bg-[#202025] border border-white/[0.07] gap-1" role="tablist" aria-label="Rentang waktu grafik">
                    @foreach ([7 => '7H', 14 => '14H', 30 => '30H'] as $days => $label)
                        <button
                            type="button"
                            data-traffic-range="{{ $days }}"
                            class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition-colors cursor-pointer {{ $days === 14 ? 'bg-[#26262b] text-white shadow-sm' : 'text-zinc-400 hover:text-zinc-200' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="relative select-none">
            <div class="flex items-stretch gap-3">
                <div id="ashr-traffic-yaxis" class="w-8 flex flex-col justify-between text-right text-[10px] font-mono text-zinc-500 py-2 flex-shrink-0"></div>
                <div id="ashr-traffic-stage" class="relative flex-1 h-52 sm:h-60 cursor-crosshair">
                    <svg id="ashr-traffic-svg" viewBox="0 0 800 220" preserveAspectRatio="none" class="w-full h-full overflow-visible"></svg>

                    <div
                        id="ashr-traffic-tooltip"
                        class="hidden pointer-events-none absolute top-2 z-20 px-3 py-2 rounded-xl bg-[#18181b] border border-white/10 shadow-xl text-xs whitespace-nowrap"
                    >
                        <div id="ashr-tt-date" class="font-semibold text-zinc-100 mb-1"></div>
                        <div class="flex items-center gap-2 text-zinc-300">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Views:</span>
                            <strong id="ashr-tt-views" class="text-white ml-auto pl-2"></strong>
                        </div>
                        <div class="flex items-center gap-2 text-zinc-300 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Unik:</span>
                            <strong id="ashr-tt-unique" class="text-white ml-auto pl-2"></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2 pl-11 flex items-center justify-between gap-4">
                <div id="ashr-traffic-xaxis" class="flex-1 flex items-center justify-between text-[10px] font-mono text-zinc-500"></div>
                <div class="hidden sm:flex items-center gap-4 text-[11px] text-zinc-400 flex-shrink-0">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        Total Views
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        Pengunjung Unik
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const FULL_SERIES = @json($visitAnalytics['series']);
            const svg = document.getElementById('ashr-traffic-svg');
            const stage = document.getElementById('ashr-traffic-stage');
            const yAxis = document.getElementById('ashr-traffic-yaxis');
            const xAxis = document.getElementById('ashr-traffic-xaxis');
            const tooltip = document.getElementById('ashr-traffic-tooltip');
            const ttDate = document.getElementById('ashr-tt-date');
            const ttViews = document.getElementById('ashr-tt-views');
            const ttUnique = document.getElementById('ashr-tt-unique');
            const rangeButtons = document.querySelectorAll('[data-traffic-range]');
            if (!svg || !stage || !Array.isArray(FULL_SERIES) || FULL_SERIES.length === 0) return;

            let activeDays = 14;
            let activeSlice = FULL_SERIES.slice(-14);
            let hoverIndex = -1;

            const W = 800;
            const H = 220;
            const PAD_TOP = 14;
            const PAD_BOTTOM = 10;
            const PLOT_H = H - PAD_TOP - PAD_BOTTOM;

            function smoothPath(pts) {
                if (pts.length === 0) return '';
                if (pts.length === 1) return `M ${pts[0].x} ${pts[0].y}`;
                let d = `M ${pts[0].x.toFixed(2)} ${pts[0].y.toFixed(2)}`;
                for (let i = 0; i < pts.length - 1; i++) {
                    const p0 = pts[i];
                    const p1 = pts[i + 1];
                    const cx = ((p0.x + p1.x) / 2).toFixed(2);
                    d += ` C ${cx} ${p0.y.toFixed(2)}, ${cx} ${p1.y.toFixed(2)}, ${p1.x.toFixed(2)} ${p1.y.toFixed(2)}`;
                }
                return d;
            }

            function render() {
                activeSlice = FULL_SERIES.slice(-activeDays);
                const maxVal = Math.max(4, ...activeSlice.map((d) => Math.max(d.views, d.unique)));
                const ceilVal = Math.ceil(maxVal / 4) * 4;

                yAxis.innerHTML = [ceilVal, Math.round(ceilVal * 0.75), Math.round(ceilVal * 0.5), Math.round(ceilVal * 0.25), 0]
                    .map((v) => `<span>${v}</span>`)
                    .join('');

                const xStep = activeDays <= 7 ? 1 : activeDays <= 14 ? 2 : 5;
                xAxis.innerHTML = activeSlice
                    .map((d, idx) => (idx % xStep === 0 || idx === activeSlice.length - 1) ? `<span>${d.label}</span>` : '')
                    .filter(Boolean)
                    .join('');

                const ptsViews = activeSlice.map((d, idx) => {
                    const x = activeSlice.length === 1 ? W / 2 : (idx / (activeSlice.length - 1)) * W;
                    const y = PAD_TOP + PLOT_H - (d.views / ceilVal) * PLOT_H;
                    return { x, y, ...d };
                });

                const ptsUnique = activeSlice.map((d, idx) => {
                    const x = activeSlice.length === 1 ? W / 2 : (idx / (activeSlice.length - 1)) * W;
                    const y = PAD_TOP + PLOT_H - (d.unique / ceilVal) * PLOT_H;
                    return { x, y, ...d };
                });

                const lineViews = smoothPath(ptsViews);
                const areaViews = ptsViews.length
                    ? `${lineViews} L ${ptsViews[ptsViews.length - 1].x.toFixed(2)} ${PAD_TOP + PLOT_H} L ${ptsViews[0].x.toFixed(2)} ${PAD_TOP + PLOT_H} Z`
                    : '';
                const lineUnique = smoothPath(ptsUnique);

                const gridLines = [0, 0.25, 0.5, 0.75, 1].map((t) => {
                    const y = (PAD_TOP + t * PLOT_H).toFixed(2);
                    return `<line x1="0" y1="${y}" x2="${W}" y2="${y}" stroke="currentColor" stroke-opacity="0.09" stroke-width="1" />`;
                }).join('');

                let hoverMarkup = '';
                if (hoverIndex >= 0 && ptsViews[hoverIndex]) {
                    const pv = ptsViews[hoverIndex];
                    const pu = ptsUnique[hoverIndex];
                    hoverMarkup = `
                        <line x1="${pv.x.toFixed(2)}" y1="${PAD_TOP}" x2="${pv.x.toFixed(2)}" y2="${PAD_TOP + PLOT_H}" stroke="#3b82f6" stroke-opacity="0.45" stroke-width="1.5" stroke-dasharray="4 4" />
                        <circle cx="${pu.x.toFixed(2)}" cy="${pu.y.toFixed(2)}" r="4.5" fill="#10b981" stroke="#ffffff" stroke-width="2" />
                        <circle cx="${pv.x.toFixed(2)}" cy="${pv.y.toFixed(2)}" r="5" fill="#3b82f6" stroke="#ffffff" stroke-width="2" />
                    `;
                }

                svg.innerHTML = `
                    <defs>
                        <linearGradient id="ashrViewsFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.32" />
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0" />
                        </linearGradient>
                    </defs>
                    ${gridLines}
                    <path d="${areaViews}" fill="url(#ashrViewsFill)" />
                    <path d="${lineUnique}" fill="none" stroke="#10b981" stroke-width="2" stroke-dasharray="5 4" vector-effect="non-scaling-stroke" />
                    <path d="${lineViews}" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
                    ${hoverMarkup}
                `;
            }

            function updateHover(clientX) {
                const rect = stage.getBoundingClientRect();
                if (!rect.width) return;
                const rel = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
                const idx = Math.round(rel * (activeSlice.length - 1));
                hoverIndex = idx;
                const pt = activeSlice[idx];
                if (!pt) return;

                ttDate.textContent = pt.fullDate;
                ttViews.textContent = pt.views;
                ttUnique.textContent = pt.unique;
                tooltip.classList.remove('hidden');

                const pct = activeSlice.length === 1 ? 50 : (idx / (activeSlice.length - 1)) * 100;
                if (pct > 72) {
                    tooltip.style.left = 'auto';
                    tooltip.style.right = `${100 - pct}%`;
                } else {
                    tooltip.style.right = 'auto';
                    tooltip.style.left = `${pct}%`;
                }
                render();
            }

            stage.addEventListener('pointermove', (e) => updateHover(e.clientX));
            stage.addEventListener('pointerleave', () => {
                hoverIndex = -1;
                tooltip.classList.add('hidden');
                render();
            });

            rangeButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    activeDays = Number(btn.dataset.trafficRange) || 14;
                    hoverIndex = -1;
                    tooltip.classList.add('hidden');
                    rangeButtons.forEach((b) => {
                        const isActive = Number(b.dataset.trafficRange) === activeDays;
                        b.className = `px-2.5 py-1 rounded-lg text-[11px] font-semibold transition-colors cursor-pointer ${
                            isActive ? 'bg-[#26262b] text-white shadow-sm' : 'text-zinc-400 hover:text-zinc-200'
                        }`;
                    });
                    render();
                });
            });

            render();
        })();
    </script>

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
