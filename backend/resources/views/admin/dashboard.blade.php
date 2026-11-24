@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Command Center')
@section('page_subtitle', 'Pantau pengunjung website, pesan masuk, komentar, dan kelola seluruh konten portofolio.')

@section('header_actions')
    <button type="button" id="ashr-export-csv" class="gd-secondary">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 17V3"/><path d="m6 11 6 6 6-6"/><path d="M19 21H5"/></svg>
        <span>Export CSV</span>
    </button>
    <a href="{{ route('admin.portfolio.index') }}" class="gd-primary">
        <span>Edit Portofolio</span>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
    </a>
@endsection

@section('content')
    @php
        $desktopPct = $visitAnalytics['desktop_pct'] ?? 0;
        $mobileOnlyPct = $visitAnalytics['mobile_only_pct'] ?? 0;
        $tabletPct = $visitAnalytics['tablet_pct'] ?? 0;
        $hasVisits = ($visitAnalytics['total_views'] ?? 0) > 0;
        $approvedPct = $stats['comments_total'] > 0
            ? (int) round(($stats['comments_approved'] / $stats['comments_total']) * 100)
            : 100;
    @endphp

    <!-- Command Bento Row (GlassyDashboard CommandView Inspired) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
        <!-- Balance / Visitor Overview Panel -->
        <section class="lg:col-span-4 gd-panel gd-enter flex flex-col justify-between gap-4">
            <div>
                <div class="gd-panel-head">
                    <div>
                        <h2>Total Pengunjung</h2>
                        <p>Rekam jejak tampilan halaman website</p>
                    </div>
                    <span class="gd-unit">30 HARI</span>
                </div>

                <div class="gd-balance-number">{{ number_format($visitAnalytics['total_views']) }}</div>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-[var(--gd-muted)]">
                    <span class="gd-delta">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
                        {{ number_format($visitAnalytics['unique_total']) }} Unik
                    </span>
                    <span>pengunjung teridentifikasi</span>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-4">
                    <a href="{{ route('admin.projects.index') }}" class="gd-primary w-full">
                        <span>Kelola Proyek</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                    </a>
                    <a href="{{ route('admin.gallery.index') }}" class="gd-secondary w-full">
                        <span>Galeri Foto</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <div class="gd-streams" aria-label="Ringkasan periode kunjungan">
                <div>
                    <span>Hari Ini</span>
                    <strong>{{ number_format($visitAnalytics['today_views']) }} views</strong>
                </div>
                <div>
                    <span>7 Hari</span>
                    <strong>{{ number_format($visitAnalytics['views_7d']) }} views</strong>
                </div>
                <div>
                    <span>Unik 7H</span>
                    <strong>{{ number_format($visitAnalytics['unique_7d']) }} orang</strong>
                </div>
            </div>
        </section>

        <!-- 2x2 Summary Bento Grid with Featured Teal Card -->
        <div class="lg:col-span-5 gd-summary gd-enter">
            <article class="gd-metric" data-tone="teal">
                <div class="gd-metric-head">
                    <span>Views Hari Ini</span>
                    <span class="gd-metric-icon" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
                <strong class="gd-metric-value">{{ number_format($visitAnalytics['today_views']) }}</strong>
                <div class="gd-metric-caption">
                    <span class="gd-delta">{{ $visitAnalytics['today_unique'] }} unik</span>
                    <span>hari ini (WIB)</span>
                </div>
            </article>

            <a href="{{ route('admin.messages.index') }}" class="gd-metric">
                <div class="gd-metric-head">
                    <span>Pesan Masuk</span>
                    <span class="gd-metric-icon" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </span>
                </div>
                <strong class="gd-metric-value">{{ $stats['messages_total'] }}</strong>
                <div class="gd-metric-caption">
                    @if ($stats['messages_unread'] > 0)
                        <span class="gd-delta" data-attention="true">{{ $stats['messages_unread'] }} Baru</span>
                    @else
                        <span class="gd-delta">Terbaca</span>
                    @endif
                    <span>form kontak</span>
                </div>
            </a>

            <a href="{{ route('admin.comments.index') }}" class="gd-metric">
                <div class="gd-metric-head">
                    <span>Komentar Live</span>
                    <span class="gd-metric-icon" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                    </span>
                </div>
                <strong class="gd-metric-value">{{ $stats['comments_total'] }}</strong>
                <div class="gd-metric-caption">
                    <span class="gd-delta">{{ $stats['comments_approved'] }} Tampil</span>
                    <span>di portofolio</span>
                </div>
            </a>

            <a href="{{ route('admin.projects.index') }}" class="gd-metric">
                <div class="gd-metric-head">
                    <span>Proyek &amp; Galeri</span>
                    <span class="gd-metric-icon" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    </span>
                </div>
                <strong class="gd-metric-value">{{ $stats['projects_total'] }}</strong>
                <div class="gd-metric-caption">
                    <span>{{ $stats['projects_website'] }}W · {{ $stats['projects_aplikasi'] }}A · {{ $stats['projects_desain'] }}D</span>
                    <span>+ {{ $stats['gallery_total'] }} foto</span>
                </div>
            </a>
        </div>

        <!-- Device Mix Donut Ring Card (matching GlassyDashboard DeviceChart) -->
        <section class="lg:col-span-3 gd-panel gd-enter flex flex-col justify-between">
            <div class="gd-panel-head mb-2">
                <div>
                    <h2>Perangkat</h2>
                    <p>Distribusi akses pengunjung</p>
                </div>
            </div>

            <div>
                <div class="gd-device-ring">
                    <svg viewBox="0 0 124 124" class="w-full h-full block" aria-hidden="true">
                        <circle cx="62" cy="62" r="47" fill="none" stroke="var(--gd-track)" stroke-width="14" />
                        @if ($hasVisits)
                            @php
                                $segments = [
                                    ['key' => 'desktop', 'ratio' => $desktopPct, 'tone' => 'coral'],
                                    ['key' => 'mobile', 'ratio' => $mobileOnlyPct, 'tone' => 'ink'],
                                    ['key' => 'tablet', 'ratio' => $tabletPct, 'tone' => 'teal'],
                                ];
                                $offset = 0;
                            @endphp
                            <g transform="rotate(-90 62 62)">
                                @foreach ($segments as $seg)
                                    @if ($seg['ratio'] > 0)
                                        <circle
                                            cx="62"
                                            cy="62"
                                            r="47"
                                            pathLength="100"
                                            fill="none"
                                            stroke-width="14"
                                            data-tone="{{ $seg['tone'] }}"
                                            data-device-seg="{{ $seg['key'] }}"
                                            class="gd-device-segment"
                                            stroke-dasharray="{{ max(0, $seg['ratio'] - 1.2) }} 100"
                                            stroke-dashoffset="{{ -$offset }}"
                                        />
                                    @endif
                                    @php $offset += $seg['ratio']; @endphp
                                @endforeach
                            </g>
                        @endif
                    </svg>
                    <div class="gd-device-total">
                        <strong id="gd-device-center-val">{{ number_format($visitAnalytics['total_views']) }}</strong>
                        <span id="gd-device-center-label">Views</span>
                    </div>
                </div>

                <div class="gd-device-list">
                    <button type="button" data-device-btn="desktop" data-pct="{{ $desktopPct }}" data-label="Desktop">
                        <span><i data-tone="coral" aria-hidden="true"></i>Desktop</span>
                        <span class="gd-mix-track" aria-hidden="true"><i class="gd-fill" data-tone="coral" style="width: {{ $desktopPct }}%"></i></span>
                        <strong>{{ $desktopPct }}%</strong>
                    </button>
                    <button type="button" data-device-btn="mobile" data-pct="{{ $mobileOnlyPct }}" data-label="Mobile">
                        <span><i data-tone="ink" aria-hidden="true"></i>Mobile</span>
                        <span class="gd-mix-track" aria-hidden="true"><i class="gd-fill" data-tone="ink" style="width: {{ $mobileOnlyPct }}%"></i></span>
                        <strong>{{ $mobileOnlyPct }}%</strong>
                    </button>
                    <button type="button" data-device-btn="tablet" data-pct="{{ $tabletPct }}" data-label="Tablet">
                        <span><i data-tone="teal" aria-hidden="true"></i>Tablet</span>
                        <span class="gd-mix-track" aria-hidden="true"><i class="gd-fill" data-tone="teal" style="width: {{ $tabletPct }}%"></i></span>
                        <strong>{{ $tabletPct }}%</strong>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Website Traffic Chart + Secondary Side Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
        <!-- Website Traffic Analytics Chart (preserving required IDs & data-traffic-range attributes) -->
        <section id="ashr-traffic-card" class="lg:col-span-9 gd-panel gd-enter space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-display font-bold text-sm sm:text-base text-[var(--gd-ink)]">Grafik Pengunjung Website</h2>
                    </div>
                    <p class="text-xs text-[var(--gd-muted)]">Perbandingan total tampilan halaman dan pengunjung unik harian (WIB).</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-4 text-[11px] font-semibold text-[var(--gd-muted)]">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[var(--gd-coral)]"></span>
                            Total Views
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[var(--gd-graph-ink)]"></span>
                            Pengunjung Unik
                        </span>
                    </div>

                    <div class="gd-period" role="tablist" aria-label="Rentang waktu grafik">
                        @foreach ([7 => '7H', 14 => '14H', 30 => '30H'] as $days => $label)
                            <button
                                type="button"
                                data-traffic-range="{{ $days }}"
                                aria-pressed="{{ $days === 14 ? 'true' : 'false' }}"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="relative select-none pt-1">
                <div class="flex items-stretch gap-3">
                    <div id="ashr-traffic-yaxis" class="w-8 flex flex-col justify-between text-right text-[10px] text-[var(--gd-muted)] py-2 flex-shrink-0"></div>
                    <div id="ashr-traffic-stage" class="relative flex-1 h-52 sm:h-56 cursor-crosshair">
                        <svg id="ashr-traffic-svg" viewBox="0 0 800 220" preserveAspectRatio="none" class="w-full h-full overflow-visible"></svg>

                        <div
                            id="ashr-traffic-tooltip"
                            class="hidden pointer-events-none absolute top-2 z-20 px-3 py-2 rounded-xl bg-[var(--gd-surface)] border border-[var(--gd-line)] shadow-xl text-[11px] whitespace-nowrap text-[var(--gd-ink)]"
                        >
                            <div id="ashr-tt-date" class="font-bold mb-1"></div>
                            <div class="flex items-center gap-2 text-[var(--gd-muted)]">
                                <span class="w-2 h-2 rounded-full bg-[var(--gd-coral)]"></span>
                                <span>Views:</span>
                                <strong id="ashr-tt-views" class="text-[var(--gd-ink)] ml-auto pl-2"></strong>
                            </div>
                            <div class="flex items-center gap-2 text-[var(--gd-muted)] mt-0.5">
                                <span class="w-2 h-2 rounded-full bg-[var(--gd-graph-ink)]"></span>
                                <span>Unik:</span>
                                <strong id="ashr-tt-unique" class="text-[var(--gd-ink)] ml-auto pl-2"></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-2.5 pl-11 flex items-center justify-between gap-4">
                    <div id="ashr-traffic-xaxis" class="flex-1 flex items-center justify-between text-[10px] text-[var(--gd-muted)]"></div>
                </div>
            </div>
        </section>

        <!-- Side Capacity & Owner Approvals Panels (GlassyDashboard CommandSide Style) -->
        <div class="lg:col-span-3 flex flex-col gap-3.5">
            <section class="gd-panel gd-enter">
                <div class="gd-panel-head mb-2.5">
                    <div>
                        <h2>Moderasi Komentar</h2>
                        <p>Rasio komentar tampil publik</p>
                    </div>
                </div>
                <div class="gd-capacity-track" role="meter" aria-valuenow="{{ $stats['comments_approved'] }}" aria-valuemin="0" aria-valuemax="{{ max(1, $stats['comments_total']) }}">
                    <span class="gd-fill" style="width: {{ $approvedPct }}%"></span>
                </div>
                <div class="flex justify-between text-[11px] text-[var(--gd-muted)] mt-2.5">
                    <span><strong class="text-[var(--gd-ink)]">{{ $stats['comments_approved'] }}</strong> Live</span>
                    <span>{{ $stats['comments_total'] }} total tersimpan</span>
                </div>
            </section>

            <section class="gd-panel gd-enter flex-1 flex flex-col justify-between gap-3">
                <div class="gd-panel-head mb-0">
                    <div>
                        <h2>Butuh Tinjauan</h2>
                        <p>Inbox &amp; konten cepat</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="gd-list-icon w-10 h-10 rounded-xl text-[var(--gd-teal)] bg-[color-mix(in_srgb,var(--gd-teal)_14%,var(--gd-inner))]" aria-hidden="true">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <strong class="block text-xs font-extrabold leading-snug">
                            {{ $stats['messages_unread'] > 0 ? $stats['messages_unread'] . ' Pesan Belum Dibaca' : 'Inbox Bersih' }}
                        </strong>
                        <small class="block text-[11px] text-[var(--gd-muted)] leading-snug mt-0.5">
                            {{ $stats['projects_total'] }} proyek · {{ $stats['gallery_total'] }} foto
                        </small>
                    </div>
                    <a href="{{ route('admin.messages.index') }}" class="gd-icon-button flex-shrink-0" aria-label="Buka Inbox Pesan Masuk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
                    </a>
                </div>
            </section>
        </div>
    </div>

    <!-- Recent Messages & Recent Comments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3.5">
        <!-- Recent Contact Messages -->
        <section class="gd-panel gd-enter p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--gd-line)] flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="font-display font-extrabold text-sm sm:text-base text-[var(--gd-ink)]">Pesan Masuk Terbaru</h2>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-bold text-[var(--gd-muted)] hover:text-[var(--gd-ink)] transition-colors">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-[var(--gd-line)]">
                @forelse ($recentMessages as $msg)
                    <div class="p-4 sm:p-5 hover:bg-[var(--gd-inner)] transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-bold text-sm text-[var(--gd-ink)] truncate">{{ $msg->name }}</span>
                                    @if (! $msg->is_read)
                                        <span class="gd-status" data-state="coral"><i></i>Baru</span>
                                    @else
                                        <span class="gd-status" data-state="ink"><i></i>Dibaca</span>
                                    @endif
                                </div>
                                <div class="text-xs text-[var(--gd-muted)] mt-0.5 truncate">{{ $msg->email }} · {{ $msg->created_at?->diffForHumans() }}</div>
                            </div>
                            <form method="POST" action="{{ route('admin.messages.toggle-read', $msg) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-bold bg-[var(--gd-inner)] border border-[var(--gd-line)] hover:bg-[var(--gd-raised)] text-[var(--gd-ink)] cursor-pointer transition-colors">
                                    {{ $msg->is_read ? 'Tandai Baru' : 'Sudah Baca' }}
                                </button>
                            </form>
                        </div>
                        <p class="mt-2 text-xs text-[var(--gd-muted)] leading-relaxed line-clamp-2">{{ $msg->message }}</p>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-[var(--gd-muted)]">Belum ada pesan masuk.</div>
                @endforelse
            </div>
        </section>

        <!-- Recent Visitor Comments -->
        <section class="gd-panel gd-enter p-0 overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--gd-line)] flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="font-display font-extrabold text-sm sm:text-base text-[var(--gd-ink)]">Komentar Pengunjung Terbaru</h2>
                </div>
                <a href="{{ route('admin.comments.index') }}" class="text-xs font-bold text-[var(--gd-muted)] hover:text-[var(--gd-ink)] transition-colors">Kelola →</a>
            </div>
            <div class="divide-y divide-[var(--gd-line)]">
                @forelse ($recentComments as $comment)
                    <div class="p-4 sm:p-5 flex items-start gap-3.5 hover:bg-[var(--gd-inner)] transition-colors">
                        @if ($comment->avatar_path)
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($comment->avatar_path) }}" alt="{{ $comment->name }}" class="w-10 h-10 rounded-full object-cover border border-[var(--gd-rim)] flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-[var(--gd-inner)] border border-[var(--gd-rim)] text-[var(--gd-ink)] font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($comment->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="font-bold text-sm text-[var(--gd-ink)] truncate">{{ $comment->name }}</span>
                                    <span class="gd-status" data-state="{{ $comment->is_approved ? 'teal' : 'ink' }}">
                                        <i></i>{{ $comment->is_approved ? 'Live' : 'Hidden' }}
                                    </span>
                                </div>
                                <form method="POST" action="{{ route('admin.comments.toggle', $comment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-bold bg-[var(--gd-inner)] border border-[var(--gd-line)] hover:bg-[var(--gd-raised)] text-[var(--gd-ink)] cursor-pointer transition-colors">
                                        {{ $comment->is_approved ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>
                            </div>
                            <p class="mt-1.5 text-xs text-[var(--gd-muted)] leading-relaxed">{{ $comment->message }}</p>
                            <div class="mt-1 text-[11px] text-[var(--gd-muted)] opacity-75">{{ $comment->formattedDate() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-[var(--gd-muted)]">Belum ada komentar.</div>
                @endforelse
            </div>
        </section>
    </div>

    <script>
        (function () {
            const FULL_SERIES = @json($visitAnalytics['series']);
            const TOTAL_VIEWS = @json($visitAnalytics['total_views']);
            const svg = document.getElementById('ashr-traffic-svg');
            const stage = document.getElementById('ashr-traffic-stage');
            const yAxis = document.getElementById('ashr-traffic-yaxis');
            const xAxis = document.getElementById('ashr-traffic-xaxis');
            const tooltip = document.getElementById('ashr-traffic-tooltip');
            const ttDate = document.getElementById('ashr-tt-date');
            const ttViews = document.getElementById('ashr-tt-views');
            const ttUnique = document.getElementById('ashr-tt-unique');
            const rangeButtons = document.querySelectorAll('[data-traffic-range]');
            const exportBtn = document.getElementById('ashr-export-csv');
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
                    return `<line x1="0" y1="${y}" x2="${W}" y2="${y}" stroke="var(--gd-grid)" stroke-dasharray="3 4" vector-effect="non-scaling-stroke" />`;
                }).join('');

                let hoverMarkup = '';
                if (hoverIndex >= 0 && ptsViews[hoverIndex]) {
                    const pv = ptsViews[hoverIndex];
                    const pu = ptsUnique[hoverIndex];
                    hoverMarkup = `
                        <line x1="${pv.x.toFixed(2)}" y1="${PAD_TOP}" x2="${pv.x.toFixed(2)}" y2="${PAD_TOP + PLOT_H}" stroke="var(--gd-muted)" stroke-dasharray="2 4" vector-effect="non-scaling-stroke" />
                        <circle cx="${pu.x.toFixed(2)}" cy="${pu.y.toFixed(2)}" r="4" fill="var(--gd-graph-ink)" />
                        <circle cx="${pv.x.toFixed(2)}" cy="${pv.y.toFixed(2)}" r="4.5" fill="var(--gd-coral)" />
                    `;
                }

                svg.innerHTML = `
                    <defs>
                        <linearGradient id="ashrViewsFill" x1="0" y1="0" x2="0" y2="1" gradientUnits="objectBoundingBox">
                            <stop offset="0%" stop-color="var(--gd-coral)" stop-opacity="0.30" />
                            <stop offset="100%" stop-color="var(--gd-coral)" stop-opacity="0.025" />
                        </linearGradient>
                    </defs>
                    ${gridLines}
                    <path d="${areaViews}" fill="url(#ashrViewsFill)" />
                    <path d="${lineUnique}" fill="none" stroke="var(--gd-graph-ink)" stroke-width="2" stroke-linecap="round" vector-effect="non-scaling-stroke" />
                    <path d="${lineViews}" fill="none" stroke="var(--gd-coral)" stroke-width="2.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
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
                        b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });
                    render();
                });
            });

            // Export CSV matching GlassyDashboard CSV export
            exportBtn?.addEventListener('click', () => {
                const rows = [
                    ['Tanggal', 'Total Views', 'Pengunjung Unik'],
                    ...activeSlice.map((d) => [d.fullDate, d.views, d.unique]),
                ];
                const csv = rows.map((r) => r.map((c) => `"${String(c).replaceAll('"', '""')}"`).join(',')).join('\r\n');
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `ashr-traffic-${activeDays}hari.csv`;
                a.click();
                setTimeout(() => URL.revokeObjectURL(url), 0);
            });

            // Device Donut Interactive Filtering
            let activeDevice = null;
            const centerVal = document.getElementById('gd-device-center-val');
            const centerLabel = document.getElementById('gd-device-center-label');
            const deviceButtons = document.querySelectorAll('[data-device-btn]');
            const deviceSegs = document.querySelectorAll('[data-device-seg]');

            deviceButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const key = btn.dataset.deviceBtn;
                    activeDevice = activeDevice === key ? null : key;

                    deviceButtons.forEach((b) => {
                        b.setAttribute('aria-pressed', b.dataset.deviceBtn === activeDevice ? 'true' : 'false');
                    });

                    deviceSegs.forEach((seg) => {
                        seg.dataset.muted = (activeDevice !== null && seg.dataset.deviceSeg !== activeDevice) ? 'true' : 'false';
                    });

                    if (activeDevice) {
                        const pct = Number(btn.dataset.pct || 0);
                        centerVal.textContent = Math.round(TOTAL_VIEWS * pct / 100).toLocaleString('en-US');
                        centerLabel.textContent = btn.dataset.label;
                    } else {
                        centerVal.textContent = Number(TOTAL_VIEWS).toLocaleString('en-US');
                        centerLabel.textContent = 'Views';
                    }
                });
            });

            render();
        })();
    </script>
@endsection
