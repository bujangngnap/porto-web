<script>
    (function () {
        const VALID = ['light', 'dark', 'dim'];
        let saved = 'dark';
        try {
            const raw = localStorage.getItem('ashr_admin_theme');
            if (VALID.includes(raw)) saved = raw;
        } catch (_) {}
        const root = document.documentElement;
        root.dataset.adminTheme = saved;
        root.classList.toggle('dark', saved !== 'light');
        window.__ASHR_ADMIN_THEME__ = saved;
    })();
</script>
<style id="ashr-admin-theme-styles">
    /* Liquid Glass Theme Switcher tokens */
    :root,
    html[data-admin-theme="dark"] {
        --c-glass: #bbbbbc;
        --c-light: #ffffff;
        --c-dark: #000000;
        --c-content: #a1a1aa;
        --c-action: #f4f4f5;
        --glass-reflex-dark: 2;
        --glass-reflex-light: 0.42;
        --saturation: 150%;
        color-scheme: dark;
    }

    html[data-admin-theme="dim"] {
        --c-glass: #94a3b8;
        --c-light: #e2e8f0;
        --c-dark: #020617;
        --c-content: #94a3b8;
        --c-action: #7dd3fc;
        --glass-reflex-dark: 1.85;
        --glass-reflex-light: 0.55;
        --saturation: 150%;
        color-scheme: dark;
    }

    html[data-admin-theme="light"] {
        --c-glass: #bbbbbc;
        --c-light: #ffffff;
        --c-dark: #0f172a;
        --c-content: #475569;
        --c-action: #0284c7;
        --glass-reflex-dark: 0.85;
        --glass-reflex-light: 1;
        --saturation: 150%;
        color-scheme: light;
    }

    html.ashr-theme-transitions body,
    html.ashr-theme-transitions aside,
    html.ashr-theme-transitions header,
    html.ashr-theme-transitions main > div,
    html.ashr-theme-transitions input,
    html.ashr-theme-transitions textarea,
    html.ashr-theme-transitions select {
        transition: background-color 220ms ease, border-color 220ms ease, color 180ms ease;
    }

    /* Apple Liquid Glass 3-Option Switcher Pill */
    .switcher {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 2px;
        width: 144px;
        height: 42px;
        box-sizing: border-box;
        padding: 4px;
        margin: 0;
        border: none;
        border-radius: 99em;
        flex-shrink: 0;
        background-color: color-mix(in srgb, var(--c-glass) 13%, transparent);
        backdrop-filter: blur(8px) url(#switcher) saturate(var(--saturation));
        -webkit-backdrop-filter: blur(10px) saturate(var(--saturation));
        box-shadow:
            inset 0 0 0 1px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 14%), transparent),
            inset 1.5px 2.5px 0 -1.5px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 85%), transparent),
            inset -1.5px -2px 0 -1.5px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 65%), transparent),
            inset -2px -5px 1px -4px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 50%), transparent),
            inset 0 -1px 3px 1px color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 14%), transparent),
            inset 0 1.5px 2px 0 color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 20%), transparent),
            0 2px 10px -2px color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 18%), transparent);
        transition: background-color 350ms ease, box-shadow 350ms ease;
    }

    .switcher__legend {
        position: absolute;
        width: 1px;
        height: 1px;
        margin: -1px;
        padding: 0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .switcher__filter {
        position: absolute;
        width: 0;
        height: 0;
        overflow: hidden;
        pointer-events: none;
        opacity: 0;
        z-index: -1;
    }

    .switcher__input {
        clip: rect(0 0 0 0);
        clip-path: inset(100%);
        height: 1px;
        overflow: hidden;
        position: absolute;
        white-space: nowrap;
        width: 1px;
    }

    .switcher__option {
        --c: var(--c-content);
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 44px;
        height: 34px;
        border-radius: 99em;
        cursor: pointer;
        user-select: none;
        transition: color 200ms ease;
    }

    .switcher__icon {
        display: block;
        width: 21px;
        height: 21px;
        transition: transform 220ms cubic-bezier(0.5, 0, 0, 1);
    }

    .switcher__option:hover {
        --c: var(--c-action);
    }

    .switcher__option:hover .switcher__icon {
        transform: scale(1.14);
    }

    .switcher__option.is-active,
    .switcher__option:has(input:checked) {
        --c: var(--c-action);
        cursor: default;
    }

    .switcher__option.is-active .switcher__icon,
    .switcher__option:has(input:checked) .switcher__icon {
        transform: scale(1.06);
    }

    .switcher:has(.switcher__input:focus-visible) {
        outline: 2px solid var(--c-action);
        outline-offset: 2px;
    }

    .switcher::after {
        content: "";
        position: absolute;
        left: 4px;
        top: 4px;
        width: 44px;
        height: 34px;
        border-radius: 99em;
        z-index: 1;
        pointer-events: none;
        background-color: color-mix(in srgb, var(--c-glass) 34%, transparent);
        box-shadow:
            inset 0 0 0 1px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 16%), transparent),
            inset 1.5px 2.5px 0 -1.5px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 90%), transparent),
            inset -1.5px -2px 0 -1.5px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 75%), transparent),
            inset -2px -5px 1px -4px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 55%), transparent),
            inset 0 2px 3px 0 color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 14%), transparent),
            0 3px 8px color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 16%), transparent);
        transition:
            background-color 350ms ease,
            box-shadow 350ms ease,
            translate 380ms cubic-bezier(0.32, 1.35, 0.45, 1);
    }

    .switcher[data-active="1"]::after,
    .switcher:has(input[c-option="1"]:checked)::after {
        translate: 0 0;
    }

    .switcher[data-active="2"]::after,
    .switcher:has(input[c-option="2"]:checked)::after {
        translate: 46px 0;
    }

    .switcher[data-active="3"]::after,
    .switcher:has(input[c-option="3"]:checked)::after {
        translate: 92px 0;
    }

    .switcher[data-previous="2"][data-active="1"]::after,
    .switcher[data-previous="3"][data-active="1"]::after,
    .switcher[data-previous="2"]:has(input[c-option="1"]:checked)::after,
    .switcher[data-previous="3"]:has(input[c-option="1"]:checked)::after {
        transform-origin: right center;
        animation: ashrScaleToggle1 380ms ease;
    }

    .switcher[data-previous="1"][data-active="2"]::after,
    .switcher[data-previous="1"]:has(input[c-option="2"]:checked)::after {
        transform-origin: left center;
        animation: ashrScaleToggle2 380ms ease;
    }

    .switcher[data-previous="3"][data-active="2"]::after,
    .switcher[data-previous="3"]:has(input[c-option="2"]:checked)::after {
        transform-origin: right center;
        animation: ashrScaleToggle2 380ms ease;
    }

    .switcher[data-previous="1"][data-active="3"]::after,
    .switcher[data-previous="2"][data-active="3"]::after,
    .switcher[data-previous="1"]:has(input[c-option="3"]:checked)::after,
    .switcher[data-previous="2"]:has(input[c-option="3"]:checked)::after {
        transform-origin: left center;
        animation: ashrScaleToggle3 380ms ease;
    }

    @keyframes ashrScaleToggle1 {
        0% { scale: 1 1; }
        45% { scale: 1.18 0.92; }
        100% { scale: 1 1; }
    }

    @keyframes ashrScaleToggle2 {
        0% { scale: 1 1; }
        45% { scale: 1.22 0.9; }
        100% { scale: 1 1; }
    }

    @keyframes ashrScaleToggle3 {
        0% { scale: 1 1; }
        45% { scale: 1.18 0.92; }
        100% { scale: 1 1; }
    }

    @media (prefers-reduced-motion: reduce) {
        .switcher::after,
        .switcher__icon {
            transition: none;
            animation: none !important;
        }
    }

    /* ============================================================
       DIM THEME (Midnight Slate — Twilight Blue-Charcoal)
       ============================================================ */
    html[data-admin-theme="dim"] .bg-\[\#0d0d10\] {
        background-color: #0f1722;
    }
    html[data-admin-theme="dim"] .bg-\[\#121215\] {
        background-color: #15202b;
    }
    html[data-admin-theme="dim"] .bg-\[\#121215\]\/90 {
        background-color: rgba(21, 32, 43, 0.88);
    }
    html[data-admin-theme="dim"] .bg-\[\#18181b\] {
        background-color: #1c2938;
    }
    html[data-admin-theme="dim"] .bg-\[\#202025\],
    html[data-admin-theme="dim"] .bg-\[\#24242a\],
    html[data-admin-theme="dim"] .bg-zinc-800 {
        background-color: #243447;
    }
    html[data-admin-theme="dim"] .bg-\[\#26262b\],
    html[data-admin-theme="dim"] .hover\:bg-\[\#26262b\]:hover,
    html[data-admin-theme="dim"] .hover\:bg-\[\#2a2a30\]:hover,
    html[data-admin-theme="dim"] .hover\:bg-\[\#323238\]:hover {
        background-color: #2b3f56;
    }
    html[data-admin-theme="dim"] .focus\:bg-\[\#24242a\]:focus {
        background-color: #2a3c52;
    }
    html[data-admin-theme="dim"] .focus\:border-zinc-400:focus {
        border-color: #60a5fa;
    }
    html[data-admin-theme="dim"] .border-white\/\[0\.05\],
    html[data-admin-theme="dim"] .border-white\/\[0\.06\],
    html[data-admin-theme="dim"] .border-white\/\[0\.07\],
    html[data-admin-theme="dim"] .border-white\/\[0\.08\] {
        border-color: rgba(148, 163, 184, 0.14);
    }
    html[data-admin-theme="dim"] .border-white\/10,
    html[data-admin-theme="dim"] .border-white\/15,
    html[data-admin-theme="dim"] .border-white\/20,
    html[data-admin-theme="dim"] .hover\:border-white\/20:hover {
        border-color: rgba(148, 163, 184, 0.28);
    }
    html[data-admin-theme="dim"] .text-zinc-400 {
        color: #94a3b8;
    }
    html[data-admin-theme="dim"] .text-zinc-500,
    html[data-admin-theme="dim"] .text-zinc-600 {
        color: #7c93b0;
    }
    html[data-admin-theme="dim"] .bg-zinc-100 {
        background-color: #e2e8f0;
    }
    html[data-admin-theme="dim"] .bg-zinc-100.text-zinc-950 {
        color: #0f172a;
    }
    html[data-admin-theme="dim"] input[type="file"].text-\[11px\]::file-selector-button {
        background-color: #243447;
        border-color: rgba(148, 163, 184, 0.22);
        color: #e2e8f0;
    }

    /* ============================================================
       LIGHT THEME (Crisp Editorial Daylight)
       ============================================================ */
    html[data-admin-theme="light"] body.bg-\[\#0d0d10\] {
        background-color: #eef2f7;
        color: #0f172a;
    }
    html[data-admin-theme="light"] aside.bg-\[\#0d0d10\] {
        background-color: #ffffff;
    }
    html[data-admin-theme="light"] .bg-\[\#121215\] {
        background-color: #f4f6fb;
    }
    html[data-admin-theme="light"] .bg-\[\#121215\]\/90 {
        background-color: rgba(244, 246, 251, 0.88);
    }
    html[data-admin-theme="light"] .bg-\[\#18181b\] {
        background-color: #ffffff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px -14px rgba(15, 23, 42, 0.08);
    }
    html[data-admin-theme="light"] .bg-\[\#202025\],
    html[data-admin-theme="light"] .bg-\[\#24242a\],
    html[data-admin-theme="light"] .bg-zinc-800 {
        background-color: #f1f5f9;
    }
    html[data-admin-theme="light"] input.bg-\[\#202025\],
    html[data-admin-theme="light"] textarea.bg-\[\#202025\],
    html[data-admin-theme="light"] select.bg-\[\#202025\] {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    html[data-admin-theme="light"] input.bg-\[\#202025\]:focus,
    html[data-admin-theme="light"] textarea.bg-\[\#202025\]:focus,
    html[data-admin-theme="light"] select.bg-\[\#202025\]:focus {
        background-color: #ffffff;
        border-color: #64748b;
    }
    html[data-admin-theme="light"] .bg-\[\#26262b\],
    html[data-admin-theme="light"] .hover\:bg-\[\#26262b\]:hover,
    html[data-admin-theme="light"] .hover\:bg-\[\#2a2a30\]:hover,
    html[data-admin-theme="light"] .hover\:bg-\[\#323238\]:hover {
        background-color: #e2e8f0;
    }
    html[data-admin-theme="light"] .hover\:bg-white\/\[0\.02\]:hover,
    html[data-admin-theme="light"] .hover\:bg-white\/\[0\.04\]:hover,
    html[data-admin-theme="light"] .hover\:bg-white\/\[0\.06\]:hover {
        background-color: rgba(15, 23, 42, 0.045);
    }
    html[data-admin-theme="light"] .bg-white\/10 {
        background-color: rgba(15, 23, 42, 0.08);
    }
    html[data-admin-theme="light"] .bg-white\/\[0\.07\] {
        background-color: rgba(15, 23, 42, 0.1);
    }
    html[data-admin-theme="light"] .bg-zinc-100 {
        background-color: #0f172a;
    }
    html[data-admin-theme="light"] .bg-zinc-100.hover\:bg-white:hover {
        background-color: #1e293b;
    }
    html[data-admin-theme="light"] .bg-zinc-100.text-zinc-950 {
        color: #ffffff;
    }
    /* Keep Featured badge over thumbnail photos bright crisp pill */
    html[data-admin-theme="light"] .relative > .absolute .bg-zinc-100 {
        background-color: #ffffff;
        color: #09090b;
    }
    /* Keep photo dark badges & avatar image wells dark & crisp */
    html[data-admin-theme="light"] .bg-black\/75 {
        background-color: rgba(15, 23, 42, 0.8);
        color: #f8fafc;
    }
    html[data-admin-theme="light"] img.bg-\[\#09090b\] {
        background-color: #0f172a;
    }
    html[data-admin-theme="light"] img.bg-white {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
    }
    html[data-admin-theme="light"] .border-white\/\[0\.05\],
    html[data-admin-theme="light"] .border-white\/\[0\.06\],
    html[data-admin-theme="light"] .border-white\/\[0\.07\],
    html[data-admin-theme="light"] .border-white\/\[0\.08\] {
        border-color: rgba(15, 23, 42, 0.09);
    }
    html[data-admin-theme="light"] .border-white\/10,
    html[data-admin-theme="light"] .border-white\/15,
    html[data-admin-theme="light"] .border-white\/20 {
        border-color: rgba(15, 23, 42, 0.15);
    }
    html[data-admin-theme="light"] .hover\:border-white\/20:hover {
        border-color: rgba(15, 23, 42, 0.28);
    }
    html[data-admin-theme="light"] .text-white:not(.bg-black\/75),
    html[data-admin-theme="light"] .text-zinc-100:not(.bg-black\/75) {
        color: #0f172a;
    }
    html[data-admin-theme="light"] .text-zinc-200,
    html[data-admin-theme="light"] .text-zinc-300 {
        color: #334155;
    }
    html[data-admin-theme="light"] .text-zinc-400 {
        color: #475569;
    }
    html[data-admin-theme="light"] .text-zinc-500,
    html[data-admin-theme="light"] .text-zinc-600 {
        color: #64748b;
    }
    html[data-admin-theme="light"] .hover\:text-white:hover,
    html[data-admin-theme="light"] .hover\:text-zinc-200:hover {
        color: #0f172a;
    }
    html[data-admin-theme="light"] .placeholder\:text-zinc-500::placeholder {
        color: #94a3b8;
    }
    html[data-admin-theme="light"] .text-emerald-300,
    html[data-admin-theme="light"] .text-emerald-400 {
        color: #047857;
    }
    html[data-admin-theme="light"] .hover\:text-emerald-200:hover {
        color: #065f46;
    }
    html[data-admin-theme="light"] .text-rose-300,
    html[data-admin-theme="light"] .text-rose-400 {
        color: #be123c;
    }
    html[data-admin-theme="light"] .hover\:text-rose-400:hover {
        color: #9f1239;
    }
    html[data-admin-theme="light"] input[type="file"].text-xs::file-selector-button {
        background-color: #0f172a;
        color: #ffffff;
    }
    html[data-admin-theme="light"] input[type="file"].text-\[11px\]::file-selector-button {
        background-color: #e2e8f0;
        border: 1px solid #cbd5e1;
        color: #0f172a;
    }
    html[data-admin-theme="light"] .shadow-\[0_24px_70px_-15px_rgba\(0\,0\,0\,0\.85\)\] {
        box-shadow: 0 24px 60px -15px rgba(15, 23, 42, 0.14);
    }

    /* Scrollbar per theme */
    html[data-admin-theme="dark"] ::-webkit-scrollbar { width: 8px; height: 8px; }
    html[data-admin-theme="dark"] ::-webkit-scrollbar-track { background: #121215; }
    html[data-admin-theme="dark"] ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 9999px; }
    html[data-admin-theme="dark"] ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }

    html[data-admin-theme="dim"] ::-webkit-scrollbar { width: 8px; height: 8px; }
    html[data-admin-theme="dim"] ::-webkit-scrollbar-track { background: #15202b; }
    html[data-admin-theme="dim"] ::-webkit-scrollbar-thumb { background: #2b3f56; border-radius: 9999px; }
    html[data-admin-theme="dim"] ::-webkit-scrollbar-thumb:hover { background: #3b5573; }

    html[data-admin-theme="light"] ::-webkit-scrollbar { width: 8px; height: 8px; }
    html[data-admin-theme="light"] ::-webkit-scrollbar-track { background: #f4f6fb; }
    html[data-admin-theme="light"] ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    html[data-admin-theme="light"] ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
