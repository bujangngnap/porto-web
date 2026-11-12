<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Ashr Portfolio</title>
    <link rel="icon" type="image/png" href="{{ asset('ashr-icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @include('admin.partials.theme-init')
</head>
<body class="min-h-screen bg-[#0d0d10] flex items-center justify-center p-4 font-sans antialiased text-zinc-100">
    <div class="fixed top-4 right-4 z-30">
        @include('admin.partials.theme-switcher')
    </div>
    <div class="w-full max-w-md bg-[#18181b] rounded-3xl p-8 border border-white/[0.08] shadow-[0_24px_70px_-15px_rgba(0,0,0,0.85)] space-y-6">
        <div class="text-center space-y-2">
            <div class="relative w-24 h-24 rounded-3xl overflow-hidden mx-auto border border-white/10 bg-[#18181b] shadow-lg flex items-center justify-center">
                <canvas id="login-liquid-logo" class="w-full h-full block"></canvas>
                <img
                    id="login-logo-fallback"
                    src="{{ asset('ashr-icon.png') }}"
                    alt="Ashr Logo"
                    class="hidden w-14 h-14 rounded-2xl object-cover"
                >
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Admin Dashboard</h1>
            <p class="text-sm text-zinc-400">Kelola portofolio, pesan masuk, komentar &amp; galeri</p>
        </div>

        <script src="{{ asset('js/liquid-glass-reveal.js') }}"></script>
        <script>
            (function () {
                const canvas = document.getElementById('login-liquid-logo');
                const fallback = document.getElementById('login-logo-fallback');
                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (reduceMotion || !window.AshrLiquidGlassReveal) {
                    canvas.classList.add('hidden');
                    fallback.classList.remove('hidden');
                    return;
                }

                const PALETTES = {
                    dark: { background: '#18181b', plateColor: '#1f1f25', logoColor: '#f4f4f5' },
                    dim: { background: '#1c2938', plateColor: '#243447', logoColor: '#f1f5f9' },
                    light: { background: '#ffffff', plateColor: '#e2e8f0', logoColor: '#0f172a' },
                };

                let controller = null;
                function mountLogo(theme) {
                    if (controller && typeof controller.destroy === 'function') {
                        controller.destroy();
                        controller = null;
                    }
                    const palette = PALETTES[theme] || PALETTES.dark;
                    controller = window.AshrLiquidGlassReveal.mount(canvas, {
                        src: @json(asset('ashr-icon.png')),
                        tileSize: 66,
                        background: palette.background,
                        plateColor: palette.plateColor,
                        logoColor: palette.logoColor,
                        present: 1.0,
                        hold: 1.6,
                        loop: true,
                        onFallback: () => {
                            canvas.classList.add('hidden');
                            fallback.classList.remove('hidden');
                        },
                    });
                }

                mountLogo(document.documentElement.dataset.adminTheme || 'dark');
                window.addEventListener('ashr-theme-change', function (e) {
                    mountLogo((e && e.detail && e.detail.theme) || 'dark');
                });
            })();
        </script>

        @if ($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/25 text-rose-300 text-xs font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Email Admin</label>
                <input
                    type="email"
                    name="email"
                    required
                    autofocus
                    value="{{ old('email') }}"
                    placeholder="example@gmail.com"
                    class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:bg-[#24242a] focus:outline-none text-sm transition-colors"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:bg-[#24242a] focus:outline-none text-sm transition-colors"
                >
            </div>

            <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="rounded border-white/20 bg-[#202025] text-zinc-100 focus:ring-0">
                <span>Ingat sesi saya</span>
            </label>

            <button
                type="submit"
                class="w-full py-3.5 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 font-bold text-sm shadow-sm transition-colors cursor-pointer"
            >
                Masuk Dashboard
            </button>
        </form>

        <div class="text-center">
            <a href="{{ env('FRONTEND_URL', 'http://localhost:5173') }}" class="text-xs font-medium text-zinc-500 hover:text-zinc-200 transition-colors">
                ← Kembali ke Website Portofolio
            </a>
        </div>
    </div>
</body>
</html>
