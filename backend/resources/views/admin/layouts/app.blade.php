<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Ashr Portfolio</title>
    <link rel="icon" type="image/png" href="{{ asset('ashr-icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @include('admin.partials.theme-init')
</head>
<body class="bg-[#121215] text-zinc-100 font-sans antialiased selection:bg-zinc-100 selection:text-zinc-950">
    <!-- Liquid Glass Admin Opening Overlay -->
    <div
        id="admin-opening-overlay"
        class="fixed inset-0 z-[100] hidden bg-[#0d0d10] flex-col items-center justify-center transition-opacity duration-500 select-none"
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
                        background: '#0d0d10',
                        plateColor: '#131318',
                        logoColor: '#f4f4f5',
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
        $navGroups = [
            [
                'heading' => null,
                'items' => [
                    ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
                    ['route' => 'admin.messages.index', 'active' => 'admin.messages.*', 'label' => 'Pesan Masuk', 'icon' => 'mail', 'badge' => $unreadMessagesCount ?? 0],
                    ['route' => 'admin.comments.index', 'active' => 'admin.comments.*', 'label' => 'Komentar', 'icon' => 'message'],
                ],
            ],
            [
                'heading' => 'WORKSPACE',
                'items' => [
                    ['route' => 'admin.portfolio.index', 'active' => 'admin.portfolio.*', 'label' => 'Edit Portofolio', 'icon' => 'edit'],
                    ['route' => 'admin.projects.index', 'active' => 'admin.projects.*', 'label' => 'Proyek Portofolio', 'icon' => 'folder'],
                    ['route' => 'admin.gallery.index', 'active' => 'admin.gallery.*', 'label' => 'Galeri Foto', 'icon' => 'image'],
                ],
            ],
            [
                'heading' => 'SYSTEM',
                'items' => [
                    ['route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'label' => 'Pengaturan', 'icon' => 'settings'],
                ],
            ],
        ];
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
    @endphp

    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar(false)"></div>

        <!-- Dark Matte Sidebar matching reference -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0d0d10] border-r border-white/[0.07] flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out">
            <!-- Brand Header -->
            <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
                    <img
                        src="{{ asset('ashr-icon.png') }}"
                        alt="Ashr Logo"
                        class="w-9 h-9 rounded-xl object-cover border border-white/15 flex-shrink-0 shadow-sm"
                    >
                    <div class="min-w-0">
                        <div class="font-display font-bold text-sm text-zinc-100 leading-tight truncate">Ashr Admin</div>
                        <div class="text-[11px] text-zinc-500 font-medium">Portfolio CMS</div>
                    </div>
                </a>
                <button type="button" onclick="toggleSidebar(false)" class="lg:hidden p-1.5 rounded-lg text-zinc-400 hover:bg-white/[0.06]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Grouped Nav Items -->
            <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
                @foreach ($navGroups as $group)
                    <div class="space-y-1">
                        @if ($group['heading'])
                            <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-[0.12em] text-zinc-500">
                                {{ $group['heading'] }}
                            </div>
                        @endif

                        @foreach ($group['items'] as $item)
                            @php $isActive = request()->routeIs($item['active']); @endphp
                            <a
                                href="{{ route($item['route']) }}"
                                class="flex items-center justify-between px-3 py-2 rounded-xl text-[13px] font-medium transition-all {{ $isActive ? 'bg-[#26262b] text-white border border-white/[0.07] shadow-sm' : 'text-zinc-400 hover:bg-white/[0.04] hover:text-zinc-200 border border-transparent' }}"
                            >
                                <span class="flex items-center gap-3 min-w-0">
                                    @switch($item['icon'])
                                        @case('home')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                                            @break
                                        @case('mail')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                            @break
                                        @case('message')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                            @break
                                        @case('edit')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                            @break
                                        @case('folder')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                                            @break
                                        @case('image')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            @break
                                        @case('settings')
                                            <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-zinc-500' }}" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                            @break
                                    @endswitch
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </span>
                                @if (!empty($item['badge']))
                                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-white/10 text-zinc-200">
                                        {{ $item['badge'] }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endforeach

                <div class="px-3 pt-1">
                    @include('admin.partials.theme-switcher')
                </div>
            </nav>

            <!-- Sidebar Footer Actions -->
            <div class="p-3.5 border-t border-white/[0.06] space-y-2">
                <a
                    href="{{ $frontendUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="w-full flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-zinc-100 hover:bg-white text-zinc-950 transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                    <span>Lihat Website</span>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium text-zinc-400 hover:text-rose-400 hover:bg-white/[0.04] border border-white/[0.07] transition-colors cursor-pointer"
                    >
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Column -->
        <div class="flex-1 lg:pl-64 flex flex-col min-w-0">
            <!-- Top Breadcrumb Bar -->
            <header class="sticky top-0 z-30 bg-[#121215]/90 backdrop-blur-md border-b border-white/[0.07] px-4 sm:px-8 py-3 flex flex-wrap items-center justify-between gap-2.5 sm:gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" onclick="toggleSidebar(true)" class="lg:hidden p-2 rounded-xl border border-white/[0.08] bg-[#18181b] text-zinc-300 hover:bg-[#26262b]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
                    </button>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2 text-xs text-zinc-500">
                            <span class="hidden sm:inline">Ashr Admin</span>
                            <span class="hidden sm:inline text-zinc-600">/</span>
                            <span class="text-zinc-100 font-semibold text-sm sm:text-base truncate">@yield('page_title', 'Dashboard')</span>
                        </div>
                        @hasSection('page_subtitle')
                            <p class="text-[11px] text-zinc-500 truncate hidden sm:block mt-0.5">@yield('page_subtitle')</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0">
                    @yield('header_actions')
                    <div class="hidden sm:flex items-center gap-2.5 @hasSection('header_actions') pl-3 border-l border-white/[0.08] @endif">
                        <div class="w-8 h-8 rounded-full bg-[#26262b] border border-white/10 text-zinc-100 font-bold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-semibold text-zinc-200">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="text-[11px] text-zinc-500">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto space-y-6">
                @if (session('success'))
                    <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-sm font-medium">
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-xs font-semibold">Tutup</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="px-4 py-3 rounded-2xl bg-rose-500/10 border border-rose-500/25 text-rose-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar(show) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
