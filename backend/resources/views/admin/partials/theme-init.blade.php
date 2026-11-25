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
    /* ============================================================
       GLASSY SPATIAL WORKSPACE TOKENS (Light / Dark / Dim)
       ============================================================ */
    :root,
    html[data-admin-theme="dark"] {
        /* Warm Espresso Spatial Glass (GlassyDashboard Dark) */
        --gd-ink: #fff6e9;
        --gd-muted: #e5d5c7;
        --gd-line: rgb(243 221 198 / 19%);
        --gd-grid: rgb(243 221 198 / 19%);
        --gd-rim: rgb(255 236 212 / 31%);
        --gd-edge-light: rgb(255 242 225 / 49%);
        --gd-edge-shade: rgb(11 6 5 / 30%);
        --gd-gloss: rgb(255 237 213 / 13%);
        --gd-frame: rgb(42 31 29 / 52%);
        --gd-rail: rgb(45 32 30 / 59%);
        --gd-card: rgb(47 36 34 / 53%);
        --gd-card-light: rgb(106 88 81 / 30%);
        --gd-raised: rgb(80 67 61 / 82%);
        --gd-inner: rgb(78 61 56 / 49%);
        --gd-surface: #3b302c;
        --gd-track: rgb(245 226 212 / 16%);
        --gd-primary: #f3ddca;
        --gd-primary-ink: #231a16;
        --gd-pick: #f9eee0;
        --gd-pick-ink: #241b17;
        --gd-coral: #ff9177;
        --gd-teal: #75dac0;
        --gd-ochre: #f1be58;
        --gd-graph-ink: #e7d9cd;
        --gd-focus: #f9bf9b;
        --gd-dark-shadow: 10 4 3;
        --gd-backdrop-bg: #171210;
        --gd-overlay-grad:
            radial-gradient(ellipse 75% 55% at 15% 10%, rgb(156 96 62 / 16%), transparent 60%),
            radial-gradient(ellipse 60% 50% at 88% 85%, rgb(30 116 97 / 13%), transparent 58%);

        /* Liquid Glass Switcher Tokens */
        --c-glass: #d6c5b8;
        --c-light: #fff6e9;
        --c-dark: #140c09;
        --c-content: #d6c5b8;
        --c-action: #fff6e9;
        --glass-reflex-dark: 2;
        --glass-reflex-light: 0.42;
        --saturation: 150%;
        color-scheme: dark;
    }

    html[data-admin-theme="light"] {
        /* Warm Cream & Terracotta Studio Glass (GlassyDashboard Light) */
        --gd-ink: #25201e;
        --gd-muted: #423731;
        --gd-line: rgb(74 49 33 / 15%);
        --gd-grid: rgb(74 49 33 / 19%);
        --gd-rim: rgb(255 253 246 / 51%);
        --gd-edge-light: rgb(255 254 247 / 84%);
        --gd-edge-shade: rgb(57 40 28 / 19%);
        --gd-gloss: rgb(255 251 242 / 45%);
        --gd-frame: rgb(247 234 219 / 23%);
        --gd-rail: rgb(241 222 206 / 48%);
        --gd-card: rgb(250 242 232 / 46%);
        --gd-card-light: rgb(255 251 245 / 34%);
        --gd-raised: rgb(255 249 243 / 81%);
        --gd-inner: rgb(255 250 245 / 37%);
        --gd-surface: #f6ece2;
        --gd-track: rgb(81 49 32 / 14%);
        --gd-primary: #261e1b;
        --gd-primary-ink: #fffaf5;
        --gd-pick: #fff9f3;
        --gd-pick-ink: #241b17;
        --gd-coral: #a43c27;
        --gd-teal: #09644f;
        --gd-ochre: #996300;
        --gd-graph-ink: #40332d;
        --gd-focus: #194b48;
        --gd-dark-shadow: 35 21 14;
        --gd-backdrop-bg: #e4d5c6;
        --gd-overlay-grad:
            radial-gradient(ellipse 75% 55% at 18% 12%, rgb(255 249 242 / 58%), transparent 60%),
            radial-gradient(ellipse 65% 55% at 86% 86%, rgb(164 114 82 / 14%), transparent 60%);

        --c-glass: #d7c0ab;
        --c-light: #fffdf6;
        --c-dark: #25201e;
        --c-content: #4e4038;
        --c-action: #241b17;
        --glass-reflex-dark: 0.85;
        --glass-reflex-light: 1;
        --saturation: 150%;
        color-scheme: light;
    }

    html[data-admin-theme="dim"] {
        /* Twilight Slate Studio Glass */
        --gd-ink: #f1f5f9;
        --gd-muted: #cbd5e1;
        --gd-line: rgb(186 210 238 / 18%);
        --gd-grid: rgb(186 210 238 / 18%);
        --gd-rim: rgb(226 238 255 / 26%);
        --gd-edge-light: rgb(241 248 255 / 36%);
        --gd-edge-shade: rgb(2 6 23 / 36%);
        --gd-gloss: rgb(226 240 255 / 11%);
        --gd-frame: rgb(19 30 44 / 58%);
        --gd-rail: rgb(22 35 50 / 64%);
        --gd-card: rgb(28 42 59 / 56%);
        --gd-card-light: rgb(71 96 125 / 26%);
        --gd-raised: rgb(42 61 84 / 82%);
        --gd-inner: rgb(37 54 74 / 52%);
        --gd-surface: #1e2d3d;
        --gd-track: rgb(203 213 225 / 16%);
        --gd-primary: #e2e8f0;
        --gd-primary-ink: #0f172a;
        --gd-pick: #f1f5f9;
        --gd-pick-ink: #0f172a;
        --gd-coral: #fb7185;
        --gd-teal: #5eead4;
        --gd-ochre: #fbbf24;
        --gd-graph-ink: #cbd5e1;
        --gd-focus: #7dd3fc;
        --gd-dark-shadow: 2 6 16;
        --gd-backdrop-bg: #0f1722;
        --gd-overlay-grad:
            radial-gradient(ellipse 75% 55% at 16% 10%, rgb(56 108 168 / 18%), transparent 60%),
            radial-gradient(ellipse 60% 50% at 86% 88%, rgb(20 118 110 / 12%), transparent 58%);

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

    html.ashr-theme-transitions .gd-app,
    html.ashr-theme-transitions .gd-top,
    html.ashr-theme-transitions .gd-dock,
    html.ashr-theme-transitions .gd-stage,
    html.ashr-theme-transitions .gd-panel,
    html.ashr-theme-transitions .gd-metric,
    html.ashr-theme-transitions .gd-summary {
        transition: background-color 220ms ease, border-color 220ms ease, color 220ms ease, filter 280ms ease, box-shadow 220ms ease;
    }

    /* ============================================================
       GLASSY SPATIAL SHELL & COMPONENT GRAMMAR
       ============================================================ */
    .gd-app {
        position: relative;
        isolation: isolate;
        min-block-size: 100svh;
        background: var(--gd-backdrop-bg);
        color: var(--gd-ink);
        font-family: "Manrope", "Inter", system-ui, sans-serif;
        font-variant-numeric: tabular-nums;
    }

    .gd-app::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        inline-size: 100%;
        block-size: 100%;
        pointer-events: none;
        background: var(--gd-overlay-grad);
    }

    .gd-surround {
        inline-size: min(calc(100% - 96px), 1380px);
        margin-inline: auto;
        padding-block: 20px 18px;
    }

    .gd-top,
    .gd-dock {
        border: 1px solid var(--gd-rim);
        background: linear-gradient(165deg, var(--gd-gloss), transparent 32% 73%, var(--gd-gloss)), var(--gd-rail);
        box-shadow:
            inset 1px 1px 1px var(--gd-edge-light),
            inset -1px -1px 1px var(--gd-edge-shade),
            inset 0 -2px 5px var(--gd-gloss),
            0 7px 20px rgb(var(--gd-dark-shadow) / 17%);
        backdrop-filter: blur(15px) saturate(1.35);
        -webkit-backdrop-filter: blur(15px) saturate(1.35);
    }

    .gd-top {
        position: sticky;
        top: 14px;
        z-index: 35;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(220px, 390px) auto;
        align-items: center;
        gap: 12px;
        min-block-size: 60px;
        border-radius: 999px;
        padding: 6px 10px 6px 14px;
    }

    .gd-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        min-inline-size: 0;
        padding-inline-start: 2px;
    }

    .gd-logo {
        inline-size: 36px;
        block-size: 36px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--gd-pick);
        color: var(--gd-pick-ink);
        box-shadow: inset 0 0 0 1px var(--gd-gloss), 0 2px 7px rgb(var(--gd-dark-shadow) / 14%);
        overflow: hidden;
        flex-shrink: 0;
    }

    .gd-brand-title {
        display: block;
        font-size: 12px;
        letter-spacing: 0.16em;
        line-height: 1.1;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--gd-ink);
    }

    .gd-brand-sub {
        display: block;
        margin-block-start: 2px;
        font-size: 10.5px;
        color: var(--gd-muted);
    }

    .gd-ask {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        min-block-size: 42px;
        max-inline-size: 100%;
        border: 1px solid var(--gd-line);
        border-radius: 999px;
        background: var(--gd-inner);
        box-shadow: inset 0 1px 0 var(--gd-gloss);
        padding: 4px 13px 4px 9px;
        text-align: start;
        color: var(--gd-ink);
        cursor: pointer;
        transition: background-color 160ms ease, border-color 160ms ease;
    }

    .gd-ask:hover,
    .gd-icon-button:hover {
        background: var(--gd-raised);
    }

    .gd-orb {
        flex: none;
        inline-size: 25px;
        block-size: 25px;
        border-radius: 50%;
        background:
            radial-gradient(ellipse at 32% 24%, #faf7fe 5%, #72ecf1 25%, transparent 58%),
            radial-gradient(ellipse at 67% 54%, #e1b7f9, #815add 55%, #353467 79%);
        box-shadow:
            inset 0 0 0 .75px rgb(246 245 255 / 75%),
            inset 0 -2px 5px #604db4,
            0 0 0 3px rgb(91 70 115 / 13%),
            0 2px 8px rgb(71 46 113 / 35%);
    }

    .gd-prompt {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: 12px;
        font-weight: 700;
        color: var(--gd-ink);
        animation: gd-prompt-in .74s steps(28, end) both;
    }

    @keyframes gd-prompt-in {
        from { clip-path: inset(0 100% 0 0); }
    }

    .gd-ask-search {
        margin-inline-start: auto;
        width: 15px;
        height: 15px;
        color: var(--gd-muted);
        flex-shrink: 0;
    }

    .gd-top-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 7px;
    }

    .gd-icon-button {
        position: relative;
        min-inline-size: 42px;
        min-block-size: 42px;
        display: inline-grid;
        place-items: center;
        border-radius: 999px;
        border: 1px solid var(--gd-line);
        padding: 0;
        background: var(--gd-inner);
        color: var(--gd-ink);
        cursor: pointer;
        transition: background-color 160ms ease, transform 140ms ease;
    }

    .gd-icon-button:active {
        transform: scale(0.96);
    }

    .gd-icon-button svg {
        width: 17px;
        height: 17px;
        stroke-width: 1.8;
    }

    .gd-notices {
        background: var(--gd-raised);
    }

    .gd-notices-dot {
        position: absolute;
        block-size: 7px;
        inline-size: 7px;
        inset: 8px 8px auto auto;
        border-radius: 50%;
        background: var(--gd-coral);
        box-shadow: 0 0 0 2px var(--gd-surface);
    }

    /* Stage Container */
    .gd-stage-wrap {
        position: relative;
        margin-block: 13px 0;
        display: flex;
        align-items: flex-start;
    }

    .gd-dock-anchor {
        width: 0;
        position: sticky;
        top: 102px;
        z-index: 25;
        flex-shrink: 0;
    }

    .gd-stage {
        flex: 1 1 100%;
        min-inline-size: 0;
        color: var(--gd-ink);
        border: 1px solid var(--gd-rim);
        border-radius: 34px;
        background:
            linear-gradient(148deg, var(--gd-gloss), transparent 19% 77%, color-mix(in srgb, var(--gd-gloss) 48%, transparent)),
            var(--gd-frame);
        box-shadow:
            inset 1px 1px 1px var(--gd-edge-light),
            inset -1px -1px 1px var(--gd-edge-shade),
            inset 0 0 7px var(--gd-gloss),
            0 1px 3px rgb(var(--gd-dark-shadow) / 17%),
            0 22px 72px rgb(var(--gd-dark-shadow) / 22%);
        backdrop-filter: blur(14px) saturate(1.24);
        -webkit-backdrop-filter: blur(14px) saturate(1.24);
        padding: clamp(20px, 2.3vw, 34px) clamp(22px, 3.2vw, 48px) 38px;
        min-block-size: calc(100svh - 156px);
    }

    .gd-view-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px 22px;
        margin-block-end: 20px;
    }

    .gd-view-head h1 {
        font-size: clamp(26px, 3vw, 42px);
        font-weight: 800;
        line-height: 1.06;
        letter-spacing: -0.045em;
        color: var(--gd-ink);
    }

    .gd-view-head p {
        color: var(--gd-muted);
        font-size: 13px;
        margin-block-start: 6px;
    }

    .gd-view-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    /* Segmented Period Pills */
    .gd-period {
        display: inline-flex;
        align-items: center;
        padding: 3px;
        border-radius: 999px;
        border: 1px solid var(--gd-rim);
        background: var(--gd-inner);
        box-shadow: inset 0 1px 0 var(--gd-gloss);
    }

    .gd-period button {
        min-block-size: 32px;
        min-inline-size: 48px;
        border: 0;
        border-radius: 999px;
        background: transparent;
        color: var(--gd-ink);
        padding: 0 11px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
        cursor: pointer;
        transition: background-color 160ms ease, color 160ms ease;
    }

    .gd-period button[aria-pressed="true"] {
        background: var(--gd-primary);
        color: var(--gd-primary-ink);
    }

    .gd-period button[aria-pressed="false"]:hover {
        background: var(--gd-card);
    }

    /* Primary & Secondary Pills */
    .gd-primary,
    .gd-secondary {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        min-block-size: 38px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 780;
        line-height: 1.2;
        border-radius: 999px;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: filter 150ms ease, background-color 150ms ease, transform 140ms ease;
    }

    .gd-primary {
        background: var(--gd-primary);
        color: var(--gd-primary-ink);
    }

    .gd-secondary {
        background: var(--gd-raised);
        border-color: var(--gd-rim);
        color: var(--gd-ink);
    }

    .gd-primary:hover {
        filter: brightness(1.1);
    }

    .gd-secondary:hover {
        background: var(--gd-surface);
    }

    /* Glassy Panels & Metrics */
    .gd-panel,
    .gd-metric,
    .gd-summary {
        min-inline-size: 0;
        border: 1px solid var(--gd-rim);
        border-radius: 22px;
        background: linear-gradient(144deg, var(--gd-card-light), transparent 66%), var(--gd-card);
        box-shadow:
            inset 0 1px 1px var(--gd-edge-light),
            inset 0 -1px 1px var(--gd-edge-shade),
            0 6px 18px rgb(var(--gd-dark-shadow) / 9%);
    }

    .gd-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        padding: 7px;
        border-radius: 24px;
        background: var(--gd-inner);
    }

    .gd-summary .gd-metric {
        border-radius: 17px;
    }

    .gd-panel {
        padding: 18px 20px;
    }

    .gd-panel-head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 8px 12px;
        margin-block-end: 14px;
    }

    .gd-panel-head h2 {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--gd-ink);
    }

    .gd-panel-head p {
        font-size: 11.5px;
        color: var(--gd-muted);
        margin-block-start: 3px;
    }

    .gd-metric {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-block-size: 120px;
        padding: 13px 14px;
        color: var(--gd-ink);
        text-decoration: none;
        transition: transform 160ms ease, border-color 160ms ease, color 220ms ease;
    }

    a.gd-metric:hover {
        transform: translateY(-2px);
        border-color: var(--gd-edge-light);
    }

    .gd-metric[data-tone="teal"] {
        --gd-coral: #ffc8b8;
        --gd-teal: #b8f1dd;
        --gd-muted: #e4f5ef;
        color: #fff;
        border-color: rgb(196 248 231 / 55%);
        background: radial-gradient(ellipse at 5% -20%, #3a9381, transparent 68%), linear-gradient(145deg, #1e7461, #08443a);
        box-shadow: inset 0 1px 2px rgb(214 252 240 / 45%), 0 6px 14px rgb(19 78 64 / 19%);
    }

    .gd-metric-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 740;
        color: inherit;
    }

    .gd-metric-icon,
    .gd-list-icon {
        display: grid;
        place-items: center;
        flex: none;
        color: inherit;
        inline-size: 30px;
        block-size: 30px;
        border-radius: 10px;
        background: var(--gd-inner);
        box-shadow: inset 0 1px 0 var(--gd-gloss);
    }

    .gd-metric[data-tone="teal"] .gd-metric-icon {
        background: rgb(235 255 247 / 16%);
    }

    .gd-metric-value {
        display: block;
        font-size: clamp(24px, 2.35vw, 36px);
        line-height: 1.05;
        margin-block: 4px 6px;
        letter-spacing: -0.05em;
        font-weight: 820;
    }

    .gd-metric-caption {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 5px;
        color: var(--gd-muted);
        font-size: 10.5px;
        font-weight: 680;
    }

    .gd-delta {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        color: var(--gd-teal);
        font-weight: 800;
        border-radius: 6px;
        padding: 2px 5px;
        background: color-mix(in srgb, var(--gd-teal) 14%, transparent);
    }

    .gd-delta[data-attention="true"] {
        color: var(--gd-coral);
        background: color-mix(in srgb, var(--gd-coral) 13%, transparent);
    }

    .gd-unit {
        border-radius: 7px;
        padding: 4px 7px;
        background: var(--gd-raised);
        color: var(--gd-ink);
        font-size: 10px;
        font-weight: 800;
    }

    .gd-balance-number {
        font-size: clamp(32px, 3.8vw, 52px);
        font-weight: 820;
        letter-spacing: -0.06em;
        line-height: 1.02;
        color: var(--gd-ink);
    }

    .gd-streams {
        display: flex;
        border-radius: 14px;
        background: var(--gd-inner);
        border: 1px solid var(--gd-line);
        padding: 9px 4px;
    }

    .gd-streams > div {
        flex: 1;
        min-inline-size: 0;
        padding: 0 10px;
    }

    .gd-streams > div + div {
        border-inline-start: 1px solid var(--gd-line);
    }

    .gd-streams span {
        display: block;
        font-size: 10px;
        color: var(--gd-muted);
    }

    .gd-streams strong {
        display: block;
        font-size: 13px;
        font-weight: 800;
        color: var(--gd-ink);
        margin-block-start: 3px;
    }

    /* Capacity Progress Bar & Device Donut */
    .gd-capacity-track,
    .gd-mix-track {
        display: block;
        height: 7px;
        border-radius: 999px;
        overflow: hidden;
        background: var(--gd-track);
    }

    .gd-capacity-track .gd-fill,
    .gd-mix-track .gd-fill {
        display: block;
        block-size: 100%;
        border-radius: inherit;
        background: var(--gd-teal);
    }

    [data-tone="ink"] { --gd-series: var(--gd-graph-ink); }
    [data-tone="coral"] { --gd-series: var(--gd-coral); }
    [data-tone="teal"] { --gd-series: var(--gd-teal); }
    [data-tone="ochre"] { --gd-series: var(--gd-ochre); }

    .gd-device-ring {
        position: relative;
        inline-size: min(100%, 138px);
        aspect-ratio: 1;
        margin-inline: auto;
    }

    .gd-device-segment {
        stroke: var(--gd-series);
        transition: opacity 200ms ease;
    }

    .gd-device-segment[data-muted="true"] {
        opacity: 0.25;
    }

    .gd-device-total {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        pointer-events: none;
    }

    .gd-device-total strong {
        font-size: 21px;
        line-height: 1.05;
        letter-spacing: -0.05em;
        font-weight: 850;
        color: var(--gd-ink);
    }

    .gd-device-total > span {
        font-size: 10px;
        margin-top: 3px;
        color: var(--gd-muted);
    }

    .gd-device-list {
        display: grid;
        gap: 3px;
        margin-top: 10px;
    }

    .gd-device-list button {
        display: grid;
        grid-template-columns: 70px minmax(0, 1fr) 36px;
        gap: 7px;
        align-items: center;
        min-block-size: 30px;
        text-align: start;
        background: transparent;
        border: 0;
        border-radius: 9px;
        padding: 3px 6px;
        color: var(--gd-ink);
        cursor: pointer;
    }

    .gd-device-list button:is(:hover, [aria-pressed="true"]) {
        background: var(--gd-inner);
    }

    .gd-device-list button span:first-child {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .gd-device-list button span:first-child i {
        inline-size: 7px;
        block-size: 7px;
        border-radius: 50%;
        background: var(--gd-series);
    }

    .gd-mix-track { height: 4px; }
    .gd-mix-track .gd-fill { background: var(--gd-series); }

    /* Status Pill Badges */
    .gd-status {
        --gd-status-color: var(--gd-graph-ink);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 780;
        background: color-mix(in srgb, var(--gd-status-color) 13%, var(--gd-inner));
        color: var(--gd-status-color);
        white-space: nowrap;
        border: 1px solid color-mix(in srgb, var(--gd-status-color) 20%, transparent);
    }

    .gd-status[data-state="teal"] { --gd-status-color: var(--gd-teal); }
    .gd-status[data-state="coral"] { --gd-status-color: var(--gd-coral); }
    .gd-status i {
        inline-size: 5px;
        block-size: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Floating Side & Bottom Docks */
    .gd-dock {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 5px;
        border-radius: 999px;
    }

    .gd-dock-side {
        position: relative;
        width: max-content;
        left: -26px;
        flex-direction: column;
    }

    .gd-dock-bottom {
        position: fixed;
        inset: auto 8px calc(10px + env(safe-area-inset-bottom));
        z-index: 40;
        justify-content: space-between;
        gap: 2px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .gd-dock-bottom::-webkit-scrollbar {
        display: none;
    }

    .gd-dock a {
        z-index: 1;
        position: relative;
        display: flex;
        gap: 4px;
        justify-content: center;
        align-items: center;
        min-inline-size: 42px;
        min-block-size: 42px;
        border-radius: 999px;
        color: var(--gd-ink);
        font-size: 11px;
        font-weight: 760;
        white-space: nowrap;
        text-decoration: none;
        transition: color 180ms ease;
    }

    .gd-dock a svg {
        width: 17px;
        height: 17px;
        stroke-width: 1.9;
        flex-shrink: 0;
    }

    .gd-dock a[aria-current="page"] {
        color: var(--gd-pick-ink);
    }

    .gd-dock a:not([aria-current="page"]):hover {
        background: var(--gd-inner);
    }

    .gd-dock-indicator {
        position: absolute;
        inset: 0 auto auto 0;
        pointer-events: none;
        visibility: hidden;
        border-radius: 999px;
        background: var(--gd-pick);
        box-shadow: inset 0 1px 0 white, 0 2px 7px rgb(var(--gd-dark-shadow) / 18%);
    }

    .gd-dock-indicator[data-ready="true"] {
        visibility: visible;
        transition: transform .36s cubic-bezier(.24, .89, .26, 1), width .36s cubic-bezier(.24, .89, .26, 1), height .36s cubic-bezier(.24, .89, .26, 1);
    }

    .gd-dock-tip {
        display: none;
        position: absolute;
        left: calc(100% + 10px);
        z-index: 30;
        pointer-events: none;
        color: var(--gd-ink);
        background: var(--gd-surface);
        padding: 6px 10px;
        border: 1px solid var(--gd-rim);
        border-radius: 9px;
        font-size: 11px;
        font-weight: 700;
        box-shadow: 0 5px 14px rgb(var(--gd-dark-shadow) / 22%);
        white-space: nowrap;
    }

    .gd-dock-side a:is(:hover, :focus-visible) .gd-dock-tip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .gd-dock-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        min-inline-size: 7px;
        block-size: 7px;
        border-radius: 999px;
        background: var(--gd-coral);
        box-shadow: 0 0 0 2px var(--gd-surface);
    }

    .gd-dock-bottom a {
        flex: 1 1 0%;
        flex-direction: column;
        padding: 4px 3px;
        min-inline-size: 38px;
        min-block-size: 46px;
        font-size: 9px;
    }

    .gd-footer {
        padding: 18px 4px 4px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .gd-footer > * {
        border-radius: 999px;
        padding: 6px 12px;
        color: var(--gd-ink);
        font-size: 11px;
        font-weight: 700;
        background: var(--gd-raised);
        border: 1px solid var(--gd-rim);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(10px);
    }

    /* Dialogs (Quick Command, Notifications, Delete Confirm) */
    .gd-dialog-card {
        border-radius: 24px;
        border: 1px solid var(--gd-rim);
        color: var(--gd-ink);
        background: var(--gd-surface);
        box-shadow: 0 20px 80px rgb(var(--gd-dark-shadow) / 42%);
    }

    .gd-quick-list button,
    .gd-quick-list a {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 10px 12px;
        text-align: start;
        border: 1px solid var(--gd-line);
        border-radius: 12px;
        background: var(--gd-inner);
        color: var(--gd-ink);
        transition: background-color 150ms ease;
    }

    .gd-quick-list button:hover,
    .gd-quick-list a:hover {
        background: var(--gd-raised);
    }

    /* Subtle entrance */
    @keyframes gd-fade-rise {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .gd-enter {
        animation: gd-fade-rise .38s cubic-bezier(.22, 1, .36, 1) both;
    }

    @media (prefers-reduced-motion: reduce) {
        .gd-enter, .gd-prompt {
            animation: none !important;
        }
    }

    @media (min-width: 761px) {
        .gd-dock-bottom { display: none; }
    }

    @media (max-width: 1060px) {
        .gd-surround { inline-size: calc(100% - 64px); }
        .gd-stage { padding-inline: 26px; }
    }

    @media (max-width: 760px) {
        .gd-surround {
            inline-size: calc(100% - 20px);
            padding-top: 12px;
            padding-bottom: calc(82px + env(safe-area-inset-bottom));
        }
        .gd-top {
            grid-template-columns: minmax(0, 1fr) auto;
            border-radius: 22px;
            padding: 8px 10px;
            gap: 8px 10px;
        }
        .gd-ask {
            grid-area: 2 / 1 / 3 / -1;
            min-block-size: 40px;
        }
        .gd-top-actions {
            grid-area: 1 / 2;
        }
        .gd-dock-anchor { display: none; }
        .gd-stage {
            border-radius: 24px;
            padding: 16px 14px 22px;
            min-block-size: auto;
        }
    }

    /* ============================================================
       LIQUID GLASS 3-MODE THEME SWITCHER
       ============================================================ */
    .switcher {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 2px;
        width: 132px;
        height: 40px;
        box-sizing: border-box;
        padding: 4px;
        margin: 0;
        border: 1px solid var(--gd-line);
        border-radius: 99em;
        flex-shrink: 0;
        background-color: var(--gd-inner);
        backdrop-filter: blur(8px) url(#switcher) saturate(var(--saturation));
        -webkit-backdrop-filter: blur(10px) saturate(var(--saturation));
        box-shadow:
            inset 0 0 0 1px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 14%), transparent),
            inset 1.5px 2px 0 -1.5px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 65%), transparent),
            inset 0 1.5px 2px 0 color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 16%), transparent);
        transition: background-color 350ms ease, box-shadow 350ms ease;
    }

    .switcher__legend,
    .switcher__input {
        position: absolute;
        width: 1px;
        height: 1px;
        margin: -1px;
        padding: 0;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        clip-path: inset(100%);
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

    .switcher__option {
        --c: var(--c-content);
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 30px;
        border-radius: 99em;
        cursor: pointer;
        user-select: none;
        transition: color 200ms ease;
    }

    .switcher__icon {
        display: block;
        width: 19px;
        height: 19px;
        transition: transform 220ms cubic-bezier(0.5, 0, 0, 1);
    }

    .switcher__option:hover,
    .switcher__option.is-active,
    .switcher__option:has(input:checked) {
        --c: var(--c-action);
    }

    .switcher__option:hover .switcher__icon {
        transform: scale(1.12);
    }

    .switcher::after {
        content: "";
        position: absolute;
        left: 4px;
        top: 4px;
        width: 40px;
        height: 30px;
        border-radius: 99em;
        z-index: 1;
        pointer-events: none;
        background-color: color-mix(in srgb, var(--c-glass) 34%, transparent);
        box-shadow:
            inset 0 0 0 1px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 20%), transparent),
            inset 1.5px 2px 0 -1.5px color-mix(in srgb, var(--c-light) calc(var(--glass-reflex-light) * 85%), transparent),
            0 2px 6px color-mix(in srgb, var(--c-dark) calc(var(--glass-reflex-dark) * 18%), transparent);
        transition:
            background-color 350ms ease,
            box-shadow 350ms ease,
            translate 360ms cubic-bezier(0.32, 1.35, 0.45, 1);
    }

    .switcher[data-active="1"]::after,
    .switcher:has(input[c-option="1"]:checked)::after { translate: 0 0; }
    .switcher[data-active="2"]::after,
    .switcher:has(input[c-option="2"]:checked)::after { translate: 42px 0; }
    .switcher[data-active="3"]::after,
    .switcher:has(input[c-option="3"]:checked)::after { translate: 84px 0; }

    /* ============================================================
       SUBPAGE UTILITY MAPPING TO GLASSY DASHBOARD TOKENS
       ============================================================ */
    .gd-stage .bg-\[\#18181b\] {
        background: linear-gradient(144deg, var(--gd-card-light), transparent 66%), var(--gd-card) !important;
        border-color: var(--gd-rim) !important;
        box-shadow:
            inset 0 1px 1px var(--gd-edge-light),
            inset 0 -1px 1px var(--gd-edge-shade),
            0 6px 18px rgb(var(--gd-dark-shadow) / 9%) !important;
    }

    .gd-stage .bg-\[\#121215\],
    .gd-stage .bg-\[\#202025\],
    .gd-stage .bg-\[\#24242a\],
    .gd-stage .bg-zinc-800 {
        background-color: var(--gd-inner) !important;
    }

    .gd-stage .bg-\[\#26262b\],
    .gd-stage .hover\:bg-\[\#26262b\]:hover,
    .gd-stage .hover\:bg-\[\#2a2a30\]:hover,
    .gd-stage .hover\:bg-\[\#323238\]:hover {
        background-color: var(--gd-raised) !important;
    }

    .gd-stage .bg-white\/10,
    .gd-stage .hover\:bg-white\/\[0\.02\]:hover,
    .gd-stage .hover\:bg-white\/\[0\.04\]:hover,
    .gd-stage .hover\:bg-white\/\[0\.06\]:hover {
        background-color: var(--gd-inner) !important;
    }

    .gd-stage .bg-zinc-100:not(.gd-keep-light) {
        background-color: var(--gd-primary) !important;
        color: var(--gd-primary-ink) !important;
    }

    .gd-stage .bg-zinc-100:not(.gd-keep-light):hover {
        filter: brightness(1.08);
    }

    .gd-stage input:not([type="checkbox"]):not([type="radio"]).bg-\[\#202025\],
    .gd-stage input:not([type="checkbox"]):not([type="radio"]).bg-\[\#18181b\],
    .gd-stage textarea.bg-\[\#202025\],
    .gd-stage select.bg-\[\#202025\] {
        background: var(--gd-inner) !important;
        border-color: var(--gd-line) !important;
        color: var(--gd-ink) !important;
    }

    .gd-stage input:focus,
    .gd-stage textarea:focus,
    .gd-stage select:focus {
        border-color: var(--gd-focus) !important;
        outline: none !important;
    }

    .gd-stage select option {
        background: var(--gd-surface);
        color: var(--gd-ink);
    }

    .gd-stage .border-white\/\[0\.05\],
    .gd-stage .border-white\/\[0\.06\],
    .gd-stage .border-white\/\[0\.07\],
    .gd-stage .border-white\/\[0\.08\],
    .gd-stage .border-white\/10,
    .gd-stage .border-white\/15,
    .gd-stage .border-white\/20,
    .gd-stage .hover\:border-white\/20:hover {
        border-color: var(--gd-line) !important;
    }

    .gd-stage .divide-white\/\[0\.06\] > :not([hidden]) ~ :not([hidden]) {
        border-color: var(--gd-line) !important;
    }

    .gd-stage .text-white:not(.bg-black\/75):not(.gd-keep-white),
    .gd-stage .text-zinc-100:not(.bg-black\/75):not(.gd-keep-white) {
        color: var(--gd-ink) !important;
    }

    .gd-stage .text-zinc-200,
    .gd-stage .text-zinc-300,
    .gd-stage .text-zinc-400,
    .gd-stage .text-zinc-500,
    .gd-stage .text-zinc-600 {
        color: var(--gd-muted) !important;
    }

    .gd-stage .placeholder\:text-zinc-500::placeholder {
        color: var(--gd-muted) !important;
        opacity: 0.72;
    }

    .gd-stage .text-emerald-300,
    .gd-stage .text-emerald-400 {
        color: var(--gd-teal) !important;
    }

    .gd-stage .bg-emerald-500\/15,
    .gd-stage .bg-emerald-500\/10 {
        background-color: color-mix(in srgb, var(--gd-teal) 14%, var(--gd-inner)) !important;
        border-color: color-mix(in srgb, var(--gd-teal) 28%, transparent) !important;
    }

    .gd-stage .text-rose-300,
    .gd-stage .text-rose-400 {
        color: var(--gd-coral) !important;
    }

    .gd-stage .bg-rose-500\/15,
    .gd-stage .bg-rose-500\/10 {
        background-color: color-mix(in srgb, var(--gd-coral) 14%, var(--gd-inner)) !important;
        border-color: color-mix(in srgb, var(--gd-coral) 28%, transparent) !important;
    }

    .gd-stage .border-rose-500\/25 {
        border-color: color-mix(in srgb, var(--gd-coral) 38%, transparent) !important;
    }

    .gd-stage input[type="file"]::file-selector-button {
        background-color: var(--gd-primary) !important;
        color: var(--gd-primary-ink) !important;
        border: 0 !important;
        border-radius: 999px;
    }

    /* Scrollbar per theme */
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--gd-line); border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--gd-rim); }
</style>
