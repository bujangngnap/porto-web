<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Ashr Portfolio</title>
    <link rel="icon" type="image/png" href="{{ asset('ashr-icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @include('admin.partials.theme-init')
</head>
<body class="gd-app font-sans antialiased">
    <!-- Liquid Glass Admin Opening Overlay -->
    <div
        id="admin-opening-overlay"
        class="fixed inset-0 z-[100] hidden bg-[#161210] flex-col items-center justify-center transition-opacity duration-500 select-none"
        aria-hidden="true"
    >
        <div class="relative w-[min(86vw,380px)] aspect-square flex items-center justify-center">
            <canvas id="admin-opening-canvas" class="w-full h-full block"></canvas>
            <img
                id="admin-opening-fallback"
                src="{{ asset('ashr-icon.png') }}"
                alt="Ashr Logo"
                class="hidden w-24 h-24 rounded-3xl object-cover border border-white/15 shadow-2xl"
            >
        </div>
    </div>

    <script src="{{ asset('js/liquid-glass-reveal.js') }}"></script>
    <script>
        (function () {
            const overlay = document.getElementById('admin-opening-overlay');
            const canvas = document.getElementById('admin-opening-canvas');
            const fallback = document.getElementById('admin-opening-fallback');
            const justLoggedIn = @json((bool) session('admin_opening'));
            const forceReplay = new URLSearchParams(window.location.search).get('opening') === '1';
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let seenInTab = false;
            try { seenInTab = sessionStorage.getItem('ashr_admin_intro_seen') === '1'; } catch (_) {}

            if (!reduceMotion && (justLoggedIn || forceReplay || !seenInTab)) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                try { sessionStorage.setItem('ashr_admin_intro_seen', '1'); } catch (_) {}

                let controller = null;
                let closed = false;

                const finish = () => {
                    if (closed) return;
                    closed = true;
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                        overlay.classList.remove('flex');
                        if (controller && controller.destroy) controller.destroy();
                    }, 480);
                };

                overlay.addEventListener('click', finish);
                window.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') finish();
                }, { once: true });

                const safetyTimer = setTimeout(finish, 3400);

                if (window.AshrLiquidGlassReveal) {
                    window.AshrLiquidGlassReveal.mount(canvas, {
                        src: @json(asset('ashr-icon.png')),
                        tileSize: 210,
                        background: '#161210',
                        plateColor: '#251d1a',
                        logoColor: '#fff6e9',
                        speed: 1.05,
                        present: 0.9,
                        hold: 0.25,
                        loop: false,
                        onFallback: () => {
                            canvas.classList.add('hidden');
                            fallback.classList.remove('hidden');
                            setTimeout(finish, 900);
                        },
                        onComplete: () => {
                            clearTimeout(safetyTimer);
                            setTimeout(finish, 260);
                        },
                    }).then((ctrl) => { controller = ctrl; });
                } else {
                    clearTimeout(safetyTimer);
                    finish();
                }
            }
        })();
    </script>

    @php
        $navItems = [
            [
                'route' => 'admin.dashboard',
                'active' => 'admin.dashboard',
                'label' => 'Dashboard',
                'short' => 'Home',
                'desc' => 'Ringkasan kunjungan, grafik trafik & inbox.',
                'prompt' => 'Pantau performa & trafik portofolio.',
                'icon' => 'home',
            ],
            [
                'route' => 'admin.messages.index',
                'active' => 'admin.messages.*',
                'label' => 'Pesan Masuk',
                'short' => 'Pesan',
                'desc' => 'Baca & balas pesan dari form Hubungi Saya.',
                'prompt' => ($unreadMessagesCount ?? 0) > 0 ? ($unreadMessagesCount . ' pesan baru perlu dibaca.') : 'Cek inbox pesan masuk terbaru.',
                'icon' => 'mail',
                'badge' => $unreadMessagesCount ?? 0,
            ],
            [
                'route' => 'admin.comments.index',
                'active' => 'admin.comments.*',
                'label' => 'Komentar',
                'short' => 'Komen',
                'desc' => 'Moderasi komentar & foto pengunjung live.',
                'prompt' => 'Kelola komentar pengunjung website.',
                'icon' => 'message',
            ],
            [
                'route' => 'admin.portfolio.index',
                'active' => 'admin.portfolio.*',
                'label' => 'Edit Portofolio',
                'short' => 'Profil',
                'desc' => 'Ubah hero, bio, pendidikan, pengalaman & sosmed.',
                'prompt' => 'Perbarui profil, pendidikan & medsos.',
                'icon' => 'edit',
            ],
            [
                'route' => 'admin.projects.index',
                'active' => 'admin.projects.*',
                'label' => 'Proyek Portofolio',
                'short' => 'Proyek',
                'desc' => 'Tambah atau atur proyek Website, App & Desain.',
                'prompt' => 'Atur proyek Website, Aplikasi & Desain.',
                'icon' => 'folder',
            ],
            [
                'route' => 'admin.gallery.index',
                'active' => 'admin.gallery.*',
                'label' => 'Galeri Foto',
                'short' => 'Galeri',
                'desc' => 'Kelola foto untuk 3D Spiral Photo Gallery.',
                'prompt' => 'Atur koleksi Spiral Photo Gallery.',
                'icon' => 'image',
            ],
            [
                'route' => 'admin.settings.edit',
                'active' => 'admin.settings.*',
                'label' => 'Pengaturan',
                'short' => 'Akun',
                'desc' => 'Ubah nama profil admin, email & password.',
                'prompt' => 'Kelola keamanan & pengaturan akun.',
                'icon' => 'settings',
            ],
        ];

        $currentPrompt = 'Cari halaman atau aksi cepat...';
        foreach ($navItems as $item) {
            if (request()->routeIs($item['active'])) {
                $currentPrompt = $item['prompt'];
                break;
            }
        }
        $frontendUrl = env('FRONTEND_URL', 'https://portoashr.vercel.app');
    @endphp

    <div class="gd-surround">
        <!-- Floating Top Pill Bar (matching GlassyDashboard gd-top) -->
        <header class="gd-top">
            <a href="{{ route('admin.dashboard') }}" class="gd-brand">
                <span class="gd-logo" aria-hidden="true">
                    <img src="{{ asset('ashr-icon.png') }}" alt="Ashr" class="w-full h-full object-cover">
                </span>
                <div class="min-w-0">
                    <strong class="gd-brand-title truncate">ASHR ADMIN</strong>
                    <small class="gd-brand-sub truncate">Portfolio Workspace</small>
                </div>
            </a>

            <!-- Glowing Command / Search Pill -->
            <button
                type="button"
                id="gd-open-search"
                class="gd-ask"
                aria-label="Buka pencarian navigasi cepat"
            >
                <span class="gd-orb" aria-hidden="true"></span>
                <span class="gd-prompt">{{ $currentPrompt }}</span>
                <svg class="gd-ask-search" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
            </button>

            <!-- Top Action Pills + Theme Switcher -->
            <div class="gd-top-actions">
                <div class="hidden md:flex items-center">
                    @include('admin.partials.theme-switcher')
                </div>

                <button
                    type="button"
                    id="gd-open-notices"
                    class="gd-icon-button gd-notices"
                    aria-label="Notifikasi pesan masuk"
                    title="Notifikasi ({{ $unreadMessagesCount ?? 0 }} baru)"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    @if (($unreadMessagesCount ?? 0) > 0)
                        <i class="gd-notices-dot" aria-hidden="true"></i>
                    @endif
                </button>

                <a
                    href="{{ $frontendUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="gd-icon-button"
                    title="Lihat Website Portofolio"
                    aria-label="Lihat Website Portofolio"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="15 3 21 3 21 9"/>
                        <line stroke-linecap="round" stroke-linejoin="round" x1="10" x2="21" y1="14" y2="3"/>
                    </svg>
                </a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="gd-icon-button"
                        title="Keluar"
                        aria-label="Keluar dari Admin"
                    >
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline stroke-linecap="round" stroke-linejoin="round" points="16 17 21 12 16 7"/>
                            <line stroke-linecap="round" stroke-linejoin="round" x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Glassy Stage + Floating Side Pill Dock -->
        <div class="gd-stage-wrap">
            <div class="gd-dock-anchor">
                <nav class="gd-dock gd-dock-side" data-gd-dock aria-label="Navigasi Workspace">
                    <span class="gd-dock-indicator" aria-hidden="true"></span>
                    @foreach ($navItems as $item)
                        @php $isActive = request()->routeIs($item['active']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            aria-label="{{ $item['label'] }}"
                            @if ($isActive) aria-current="page" @endif
                        >
                            @switch($item['icon'])
                                @case('home')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                                    @break
                                @case('mail')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    @break
                                @case('message')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                    @break
                                @case('edit')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    @break
                                @case('folder')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                                    @break
                                @case('image')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    @break
                                @case('settings')
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                    @break
                            @endswitch
                            @if (!empty($item['badge']))
                                <span class="gd-dock-badge" aria-hidden="true"></span>
                            @endif
                            <span class="gd-dock-tip" aria-hidden="true">
                                {{ $item['label'] }}
                                @if (!empty($item['badge']))
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-300">{{ $item['badge'] }}</span>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Frosted Spatial Glass Stage -->
            <main class="gd-stage">
                <header class="gd-view-head">
                    <div class="min-w-0">
                        <h1>@yield('page_title', 'Dashboard')</h1>
                        @hasSection('page_subtitle')
                            <p>@yield('page_subtitle')</p>
                        @endif
                    </div>

                    <div class="gd-view-actions">
                        <div class="md:hidden">
                            @include('admin.partials.theme-switcher')
                        </div>
                        @yield('header_actions')
                    </div>
                </header>

                <div class="space-y-5">
                    @if (session('success'))
                        <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-emerald-500/15 border text-emerald-300 text-xs sm:text-sm font-semibold">
                            <span>{{ session('success') }}</span>
                            <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold opacity-80 hover:opacity-100 cursor-pointer">Tutup</button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="px-4 py-3 rounded-2xl bg-rose-500/15 border text-rose-300 text-xs sm:text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <div>• {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Mobile Bottom Pill Dock -->
        <nav class="gd-dock gd-dock-bottom" data-gd-dock aria-label="Menu Mobile Admin">
            <span class="gd-dock-indicator" aria-hidden="true"></span>
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['active']); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    aria-label="{{ $item['label'] }}"
                    @if ($isActive) aria-current="page" @endif
                >
                    @switch($item['icon'])
                        @case('home')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            @break
                        @case('mail')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            @break
                        @case('message')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                            @break
                        @case('edit')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                            @break
                        @case('folder')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                            @break
                        @case('image')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            @break
                        @case('settings')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            @break
                    @endswitch
                    <span>{{ $item['short'] }}</span>
                    @if (!empty($item['badge']))
                        <span class="gd-dock-badge" aria-hidden="true"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <!-- Spatial Footer Pills -->
        <footer class="gd-footer">
            <span>{{ auth()->user()->name ?? 'Admin' }} · Ashr Workspace</span>
            <a href="{{ $frontendUrl }}" target="_blank" rel="noopener noreferrer" class="hover:underline">
                <span>Buka Website Live</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
            </a>
        </footer>
    </div>

    <!-- Command Palette / Quick Search Modal -->
    <div
        id="gd-search-modal"
        class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-black/55 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="gd-search-title"
    >
        <div class="w-full max-w-lg gd-dialog-card p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <h2 id="gd-search-title" class="text-xl font-extrabold tracking-tight">Navigasi Cepat</h2>
                <button type="button" data-close-gd-modal="gd-search-modal" class="gd-icon-button" aria-label="Tutup">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <label class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl border border-[var(--gd-line)] bg-[var(--gd-inner)]">
                <svg class="w-4 h-4 text-[var(--gd-muted)] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input
                    id="gd-quick-search-input"
                    type="search"
                    placeholder="Ketik halaman, misal: proyek, komentar, galeri..."
                    class="w-full bg-transparent text-sm text-[var(--gd-ink)] placeholder:text-[var(--gd-muted)] focus:outline-none"
                >
            </label>
            <div id="gd-quick-search-list" class="gd-quick-list space-y-1.5 max-h-72 overflow-y-auto">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" data-search-text="{{ strtolower($item['label'] . ' ' . $item['desc']) }}">
                        <div class="flex-1 min-w-0">
                            <strong class="block text-sm font-bold">{{ $item['label'] }}</strong>
                            <small class="block text-xs text-[var(--gd-muted)] mt-0.5">{{ $item['desc'] }}</small>
                        </div>
                        <svg class="w-4 h-4 text-[var(--gd-muted)] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Notifications Modal -->
    <div
        id="gd-notices-modal"
        class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-black/55 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="gd-notices-title"
    >
        <div class="w-full max-w-md gd-dialog-card p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <h2 id="gd-notices-title" class="text-xl font-extrabold tracking-tight">Notifikasi</h2>
                <button type="button" data-close-gd-modal="gd-notices-modal" class="gd-icon-button" aria-label="Tutup">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="gd-quick-list space-y-2 max-h-80 overflow-y-auto">
                @forelse ($unreadNotifications ?? [] as $notif)
                    <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}">
                        <div class="flex-1 min-w-0">
                            <strong class="block text-sm font-bold truncate">{{ $notif->name }}</strong>
                            <small class="block text-xs text-[var(--gd-muted)] truncate mt-0.5">{{ \Illuminate\Support\Str::limit($notif->message, 65) }}</small>
                        </div>
                        <svg class="w-4 h-4 text-[var(--gd-muted)] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                @empty
                    <div class="py-6 text-center text-sm text-[var(--gd-muted)]">
                        Semua pesan masuk sudah terbaca.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Custom Theme-Aware Delete Confirmation Modal -->
    <div
        id="ashr-confirm-modal"
        class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 transition-opacity duration-200"
        role="dialog"
        aria-modal="true"
        aria-labelledby="ashr-confirm-title"
        aria-describedby="ashr-confirm-message"
    >
        <div
            id="ashr-confirm-dialog"
            class="w-full max-w-md gd-dialog-card p-6 shadow-2xl space-y-5 transform scale-95 transition-transform duration-200"
        >
            <div class="flex items-start gap-3.5">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 border border-rose-500/30 bg-rose-500/15 text-[var(--gd-coral)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1 pt-0.5">
                    <h3 id="ashr-confirm-title" class="font-display font-bold text-base text-[var(--gd-ink)] leading-snug">Konfirmasi Hapus</h3>
                    <p id="ashr-confirm-message" class="mt-1 text-xs text-[var(--gd-muted)] leading-relaxed">Item yang dihapus tidak dapat dikembalikan.</p>
                </div>
            </div>

            <div id="ashr-confirm-item-wrap" class="hidden px-3.5 py-2.5 rounded-xl bg-[var(--gd-inner)] border border-[var(--gd-line)]">
                <div id="ashr-confirm-item" class="text-xs font-semibold text-[var(--gd-ink)] truncate"></div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-1">
                <button
                    type="button"
                    id="ashr-confirm-cancel"
                    class="gd-secondary"
                >
                    Batal
                </button>
                <button
                    type="button"
                    id="ashr-confirm-submit"
                    style="background-color: #e11d48; color: #ffffff;"
                    class="px-4 py-2.5 rounded-full text-xs font-semibold hover:opacity-90 shadow-sm transition-opacity cursor-pointer"
                >
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sliding Pill Indicator on Floating Docks
        (function () {
            document.querySelectorAll('[data-gd-dock]').forEach(function (dock) {
                const indicator = dock.querySelector('.gd-dock-indicator');
                if (!indicator) return;

                function sync() {
                    const activeLink = dock.querySelector('a[aria-current="page"]');
                    if (!activeLink) return;
                    indicator.style.width = activeLink.offsetWidth + 'px';
                    indicator.style.height = activeLink.offsetHeight + 'px';
                    indicator.style.transform = 'translate(' + activeLink.offsetLeft + 'px, ' + activeLink.offsetTop + 'px)';
                    indicator.dataset.ready = 'true';
                }

                if (window.ResizeObserver) {
                    const ro = new ResizeObserver(sync);
                    ro.observe(dock);
                    dock.querySelectorAll('a').forEach((el) => ro.observe(el));
                }
                requestAnimationFrame(sync);
                window.addEventListener('resize', sync);
            });
        })();

        // Command Search & Notifications Modals
        (function () {
            const searchModal = document.getElementById('gd-search-modal');
            const noticesModal = document.getElementById('gd-notices-modal');
            const searchInput = document.getElementById('gd-quick-search-input');
            const searchItems = document.querySelectorAll('#gd-quick-search-list [data-search-text]');

            function showModal(el, focusEl) {
                if (!el) return;
                el.classList.remove('hidden');
                el.classList.add('flex');
                if (focusEl) setTimeout(() => focusEl.focus(), 30);
            }

            function hideModal(el) {
                if (!el) return;
                el.classList.add('hidden');
                el.classList.remove('flex');
            }

            document.getElementById('gd-open-search')?.addEventListener('click', () => showModal(searchModal, searchInput));
            document.getElementById('gd-open-notices')?.addEventListener('click', () => showModal(noticesModal));

            document.querySelectorAll('[data-close-gd-modal]').forEach((btn) => {
                btn.addEventListener('click', () => hideModal(document.getElementById(btn.dataset.closeGdModal)));
            });

            [searchModal, noticesModal].forEach((m) => {
                m?.addEventListener('click', (e) => {
                    if (e.target === m) hideModal(m);
                });
            });

            searchInput?.addEventListener('input', () => {
                const q = searchInput.value.trim().toLowerCase();
                searchItems.forEach((item) => {
                    const hay = item.dataset.searchText || '';
                    item.style.display = hay.includes(q) ? 'flex' : 'none';
                });
            });

            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    showModal(searchModal, searchInput);
                } else if (e.key === 'Escape') {
                    hideModal(searchModal);
                    hideModal(noticesModal);
                }
            });
        })();

        // Delete Confirmation Modal
        (function () {
            const modal = document.getElementById('ashr-confirm-modal');
            const dialog = document.getElementById('ashr-confirm-dialog');
            const titleEl = document.getElementById('ashr-confirm-title');
            const msgEl = document.getElementById('ashr-confirm-message');
            const itemWrap = document.getElementById('ashr-confirm-item-wrap');
            const itemEl = document.getElementById('ashr-confirm-item');
            const cancelBtn = document.getElementById('ashr-confirm-cancel');
            const submitBtn = document.getElementById('ashr-confirm-submit');
            if (!modal || !dialog) return;

            let pendingForm = null;
            let lastFocus = null;

            function closeConfirm() {
                modal.classList.add('opacity-0');
                dialog.classList.add('scale-95');
                setTimeout(function () {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Ya, Hapus';
                    pendingForm = null;
                    if (lastFocus && typeof lastFocus.focus === 'function') {
                        lastFocus.focus();
                    }
                    lastFocus = null;
                }, 160);
            }

            function openConfirm(form) {
                pendingForm = form;
                lastFocus = document.activeElement;
                titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi Hapus';
                msgEl.textContent = form.dataset.confirmMessage || 'Item ini akan dihapus permanen.';
                const itemText = (form.dataset.confirmItem || '').trim();
                if (itemText) {
                    itemEl.textContent = itemText;
                    itemWrap.classList.remove('hidden');
                } else {
                    itemWrap.classList.add('hidden');
                }
                submitBtn.textContent = form.dataset.confirmOk || 'Ya, Hapus';
                submitBtn.disabled = false;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(function () {
                    modal.classList.remove('opacity-0');
                    dialog.classList.remove('scale-95');
                    cancelBtn.focus();
                });
            }

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form || !form.matches('form[data-confirm-message]')) return;
                if (form.dataset.confirmed === 'true') return;
                e.preventDefault();
                openConfirm(form);
            });

            cancelBtn.addEventListener('click', closeConfirm);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeConfirm();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeConfirm();
                }
            });

            submitBtn.addEventListener('click', function () {
                if (!pendingForm) return;
                const formToSubmit = pendingForm;
                formToSubmit.dataset.confirmed = 'true';
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menghapus...';
                formToSubmit.submit();
            });
        })();
    </script>
</body>
</html>
