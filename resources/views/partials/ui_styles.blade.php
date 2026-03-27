<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|fraunces:400,500,600,700|jetbrains-mono:400,500,700" rel="stylesheet" />

<style>
    :root {
        --paper: #f3ede2; --paper-deep: #e7dbc4; --surface: rgba(255, 249, 242, 0.84); --surface-strong: #fffaf3;
        --ink: #181614; --ink-soft: #60584c; --line: rgba(24, 22, 20, 0.12); --line-strong: rgba(24, 22, 20, 0.24);
        --accent: #cd643f; --accent-deep: #8d351a; --accent-soft: rgba(205, 100, 63, 0.14);
        --accent-alt: #2f6b87; --accent-alt-soft: rgba(47, 107, 135, 0.12);
        --success: #2d5f42; --success-soft: rgba(45, 95, 66, 0.14); --danger: #a64534; --danger-soft: rgba(166, 69, 52, 0.14);
        --info: #355b75; --info-soft: rgba(53, 91, 117, 0.14); --shadow-soft: 0 20px 48px -34px rgba(24, 22, 20, 0.34);
        --shadow-panel: 0 30px 80px -50px rgba(24, 22, 20, 0.38); --radius-sm: 18px; --radius-md: 28px; --radius-lg: 40px; --transition: 180ms ease;
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
        margin: 0; min-height: 100vh; font-family: "Instrument Sans", "Segoe UI", sans-serif; color: var(--ink);
        background:
            radial-gradient(circle at 12% 14%, rgba(47, 107, 135, 0.16), transparent 26%),
            radial-gradient(circle at 86% 12%, rgba(205, 100, 63, 0.12), transparent 28%),
            linear-gradient(125deg, transparent 0 58%, rgba(205, 100, 63, 0.08) 58% 61%, transparent 61%),
            linear-gradient(180deg, var(--paper) 0%, var(--paper-deep) 100%);
        background-attachment: fixed;
    }
    body::before, body::after { content: ""; position: fixed; pointer-events: none; z-index: 0; }
    body::before {
        inset: 0; opacity: 0.58;
        background: linear-gradient(90deg, rgba(24, 22, 20, 0.035) 1px, transparent 1px), linear-gradient(rgba(24, 22, 20, 0.028) 1px, transparent 1px);
        background-size: 34px 34px; mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.88), rgba(0, 0, 0, 0.3) 58%, transparent 100%);
    }
    body::after { left: 8%; bottom: 6%; width: 240px; height: 240px; border-radius: 50%; background: rgba(255, 255, 255, 0.28); filter: blur(14px); opacity: 0.52; }
    a { color: inherit; text-decoration: none; }
    button, input, select, textarea { font: inherit; }
    button { cursor: pointer; }
    img { display: block; max-width: 100%; }

    .page-shell { position: relative; z-index: 1; width: min(1380px, calc(100% - 40px)); margin: 0 auto; padding: 28px 0 56px; }
    .page-container, .flash-stack, .metric-stack, .transaction-list, .summary-list, .category-tapes, .story-list, .ranking-list, .currency-grid, .auth-rhythm, .form-stack, .form-grid { display: grid; gap: 16px; }
    .page-container, .dashboard-side, .stats-side { gap: 28px; }
    .topbar {
        position: sticky; top: 16px; z-index: 40; display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 20px; align-items: center;
        padding: 18px 0 20px; margin-bottom: 26px; border-top: 1px solid var(--line-strong); border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, rgba(243, 237, 226, 0.88), rgba(243, 237, 226, 0.72)); backdrop-filter: blur(18px);
    }
    .topbar::after { content: ""; position: absolute; left: 0; right: 0; bottom: -9px; height: 9px; background: linear-gradient(90deg, var(--accent) 0 12%, transparent 12%); opacity: 0.35; }
    .brand { display: inline-flex; align-items: center; gap: 16px; min-width: 0; }
    .brand-mark {
        position: relative; width: 56px; height: 56px; flex-shrink: 0; border-radius: 18px; border: 1px solid var(--line-strong);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.26)), linear-gradient(135deg, rgba(47, 107, 135, 0.14), rgba(205, 100, 63, 0.22));
        overflow: hidden; box-shadow: var(--shadow-soft);
    }
    .brand-mark::before, .brand-mark::after { content: ""; position: absolute; inset: 10px; border: 1px solid rgba(24, 22, 20, 0.12); border-radius: 14px; }
    .brand-mark::after { inset: 18px; background: linear-gradient(180deg, rgba(24, 22, 20, 0.1), rgba(24, 22, 20, 0)); border-radius: 10px; }
    .brand-title, .pill, .badge, .metric-label, .field-label, .story-label, .currency-metric span:first-child, .fact-label, .transaction-meta, .transaction-month, .category-kicker, .story-index {
        font-family: "JetBrains Mono", monospace; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
    }
    .brand-title { display: block; font-size: 0.79rem; letter-spacing: 0.18em; }
    .brand-subtitle { display: block; margin-top: 6px; color: var(--ink-soft); font-family: "Fraunces", Georgia, serif; font-size: 1.04rem; font-weight: 500; line-height: 1.2; }
    .topbar-actions, .hero-actions, .hero-meta, .transaction-heading, .transaction-actions, .table-actions, .ranking-head, .story-meta, .currency-header, .summary-item, .category-tape, .topbar-actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .topbar-actions { justify-content: flex-end; align-items: center; }
    .pill, .badge {
        display: inline-flex; align-items: center; min-height: 34px; padding: 0 14px; border-radius: 999px; border: 1px solid var(--line);
        background: rgba(255, 250, 243, 0.68); color: var(--ink-soft); font-size: 0.73rem; white-space: nowrap;
    }

    .panel {
        position: relative; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.52); border-radius: var(--radius-lg);
        background: linear-gradient(180deg, rgba(255, 250, 243, 0.9), rgba(247, 240, 230, 0.72)), linear-gradient(135deg, rgba(47, 107, 135, 0.04), rgba(205, 100, 63, 0.06));
        box-shadow: var(--shadow-panel); backdrop-filter: blur(20px);
    }
    .panel::before {
        content: ""; position: absolute; inset: 0; pointer-events: none; opacity: 0.5;
        background: linear-gradient(90deg, rgba(24, 22, 20, 0.04) 1px, transparent 1px), linear-gradient(rgba(24, 22, 20, 0.03) 1px, transparent 1px);
        background-size: 26px 26px; mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.3), transparent 86%);
    }
    .section-card, .hero, .auth-feature, .auth-panel, .modal-panel { padding: clamp(24px, 3vw, 38px); }
    .section-header { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; margin-bottom: 24px; }
    .section-eyebrow { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px; color: var(--ink-soft); font-family: "JetBrains Mono", monospace; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; }
    .section-eyebrow::before { content: ""; width: 22px; height: 1px; background: currentColor; opacity: 0.45; }
    .section-title, .hero-title, .auth-title, .metric-value, .amount-primary, .summary-value, .category-total, .story-number, .currency-balance, .fact-value, .transaction-day, .ranking-share, .story-month strong, .hero-note strong {
        font-family: "Fraunces", Georgia, serif; font-weight: 600; letter-spacing: -0.05em; color: var(--ink);
    }
    .section-title { margin: 0; font-size: clamp(1.5rem, 2vw, 2.3rem); line-height: 1.02; }
    .section-description, .muted-text, .table-note, .auth-copy, .auth-footer, .notice-box p, .transaction-note, .hero-note, .flash, .auth-beat p { margin: 0; color: var(--ink-soft); line-height: 1.68; }

    .hero { position: relative; z-index: 1; display: grid; gap: 28px; grid-template-columns: minmax(0, 1.45fr) minmax(300px, 0.9fr); align-items: start; min-height: 380px; }
    .hero::after { content: ""; position: absolute; right: 28px; bottom: 28px; width: min(240px, 40%); height: 1px; background: linear-gradient(90deg, transparent, var(--line-strong)); opacity: 0.7; }
    .hero-copy, .hero-side { position: relative; z-index: 1; }
    .hero-title { margin: 0; max-width: 11ch; font-size: clamp(3rem, 7vw, 5.85rem); line-height: 0.9; text-wrap: balance; }
    .hero-copy .section-description { max-width: 58ch; margin-top: 18px; font-size: 1.02rem; }
    .hero-actions { margin-top: 24px; align-items: center; }
    .hero-side { display: grid; gap: 16px; align-content: start; }
    .hero-note, .metric-card, .empty-state, .notice-box, .summary-item, .story-row, .currency-card, .ranking-item, .auth-beat, .transaction-row {
        position: relative; z-index: 1; border: 1px solid var(--line); background: rgba(255, 251, 245, 0.74); box-shadow: var(--shadow-soft);
    }
    .hero-note, .metric-card, .notice-box, .summary-item, .story-row, .currency-card, .ranking-item, .auth-beat { border-radius: 22px; }
    .hero-note { padding: 20px; }
    .hero-note strong { display: block; margin-bottom: 8px; font-size: 1.1rem; line-height: 1.2; }
    .hero-facts { display: grid; gap: 14px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .hero-fact { padding: 16px 18px; border-top: 1px solid var(--line-strong); background: rgba(24, 22, 20, 0.03); }
    .fact-value { display: block; font-size: clamp(1.5rem, 2.6vw, 2.4rem); line-height: 0.92; }
    .fact-label { display: block; margin-top: 8px; color: var(--ink-soft); font-size: 0.72rem; }

    .btn, .month-nav, .modal-close, .dropdown-item, .icon-button {
        transition: transform var(--transition), border-color var(--transition), background-color var(--transition), box-shadow var(--transition), color var(--transition);
    }
    .btn {
        position: relative; display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 48px; padding: 0 18px; border: 1px solid transparent;
        border-radius: 999px; font-family: "JetBrains Mono", monospace; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; white-space: nowrap;
    }
    .btn::after { content: ""; position: absolute; left: 18px; right: 18px; bottom: 10px; height: 1px; background: currentColor; transform: scaleX(0.24); transform-origin: left; opacity: 0.45; transition: transform var(--transition), opacity var(--transition); }
    .btn:hover, .month-nav:hover, .modal-close:hover, .icon-button:hover { transform: translateY(-2px); }
    .btn:hover::after { transform: scaleX(1); opacity: 0.8; }
    .btn:focus-visible, .field-control:focus-visible, .dropdown-item:focus-visible, .icon-button:focus-visible, .month-nav:focus-visible, .modal-close:focus-visible { outline: 3px solid rgba(47, 107, 135, 0.16); outline-offset: 3px; }
    .btn-primary { color: #fff7f2; border-color: rgba(24, 22, 20, 0.15); background: linear-gradient(135deg, var(--accent), var(--accent-deep)); box-shadow: 0 20px 34px -24px rgba(141, 53, 26, 0.7); }
    .btn-secondary { color: var(--ink); border-color: var(--line-strong); background: rgba(255, 250, 243, 0.74); }
    .btn-ghost { color: var(--ink-soft); border-color: var(--line); background: rgba(255, 250, 243, 0.42); }
    .btn-danger { color: var(--danger); border-color: rgba(166, 69, 52, 0.22); background: rgba(255, 250, 243, 0.74); }
    .btn-small, .icon-button { min-height: 40px; padding: 0 15px; font-size: 0.72rem; }
    .icon-button.danger:hover { color: var(--danger); border-color: rgba(166, 69, 52, 0.22); background: rgba(166, 69, 52, 0.08); }

    .month-switcher {
        display: inline-flex; align-items: center; gap: 12px; width: 100%; padding: 10px; border-top: 1px solid var(--line-strong); border-bottom: 1px solid var(--line); background: rgba(255, 251, 245, 0.48);
    }
    .month-label { flex: 1; text-align: center; font-family: "Fraunces", Georgia, serif; font-size: 1.1rem; font-weight: 600; letter-spacing: -0.03em; }
    .month-nav, .modal-close, .icon-button {
        display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; border: 1px solid var(--line); background: rgba(255, 251, 245, 0.88); color: var(--ink); box-shadow: var(--shadow-soft);
    }
    .month-nav { width: 40px; height: 40px; font-size: 1rem; font-weight: 700; }
    .month-nav.disabled { opacity: 0.34; pointer-events: none; }

    .flash { padding: 15px 18px; border-radius: 18px; border: 1px solid transparent; box-shadow: var(--shadow-soft); }
    .flash-success { color: var(--success); border-color: rgba(45, 95, 66, 0.18); background: var(--success-soft); }
    .flash-danger { color: var(--danger); border-color: rgba(166, 69, 52, 0.2); background: var(--danger-soft); }
    .flash-info { color: var(--info); border-color: rgba(53, 91, 117, 0.2); background: var(--info-soft); }

    .dashboard-grid, .stats-grid { display: grid; gap: 28px; align-items: start; }
    .dashboard-grid { grid-template-columns: minmax(0, 1.55fr) minmax(300px, 0.9fr); }
    .stats-grid { grid-template-columns: minmax(0, 1.25fr) minmax(0, 0.95fr); }
    .stats-grid .span-2 { grid-column: span 2; }
    .sticky-card { position: sticky; top: 118px; }
    .metric-grid { display: grid; gap: 16px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .metric-grid--single { grid-template-columns: 1fr; }
    .metric-card { padding: 18px 20px; }
    .metric-label { display: block; color: var(--ink-soft); font-size: 0.72rem; }
    .metric-value { display: block; margin-top: 12px; font-size: clamp(1.8rem, 3vw, 3rem); line-height: 0.94; font-variant-numeric: tabular-nums; }
    .metric-value.income, .tone-income { color: var(--success); }
    .metric-value.expense, .tone-expense { color: var(--danger); }
    .metric-value.neutral, .tone-accent { color: var(--accent-alt); }
    .amount-stack { display: grid; gap: 6px; }
    .amount-primary { font-size: 1.28rem; font-variant-numeric: tabular-nums; }
    .amount-secondary, .mono, .summary-value, .story-number, .currency-balance { font-variant-numeric: tabular-nums; }
    .amount-secondary { color: var(--ink-soft); font-family: "JetBrains Mono", monospace; font-size: 0.74rem; letter-spacing: 0.06em; }
    .badge-income { color: var(--success); border-color: rgba(45, 95, 66, 0.16); background: rgba(45, 95, 66, 0.08); }
    .badge-expense { color: var(--danger); border-color: rgba(166, 69, 52, 0.16); background: rgba(166, 69, 52, 0.08); }

    .transaction-row { display: grid; grid-template-columns: 112px minmax(0, 1fr) minmax(180px, auto) auto; gap: 18px; align-items: start; padding: 18px; border-radius: 28px; }
    .transaction-row:hover { transform: translateX(6px); border-color: var(--line-strong); background: rgba(255, 252, 248, 0.9); }
    .transaction-date { display: grid; gap: 6px; align-content: start; padding-right: 8px; border-right: 1px solid var(--line); }
    .transaction-day { font-size: 2.2rem; line-height: 0.88; }
    .transaction-month { color: var(--ink-soft); font-size: 0.72rem; }
    .transaction-main { display: grid; gap: 10px; min-width: 0; }
    .transaction-heading { justify-content: space-between; align-items: flex-start; }
    .transaction-category { margin: 0; font-size: 1.18rem; font-weight: 700; letter-spacing: -0.03em; }
    .transaction-meta { color: var(--ink-soft); font-size: 0.72rem; }
    .transaction-note { margin: 0; }
    .transaction-amount { display: grid; gap: 6px; justify-items: end; text-align: right; }
    .transaction-actions, .table-actions { justify-content: flex-end; }

    .summary-item, .category-tape, .currency-metric { justify-content: space-between; align-items: flex-start; gap: 16px; }
    .summary-item, .category-tape { padding: 16px 18px; }
    .summary-item strong, .category-tape strong, .ranking-copy strong, .auth-beat strong { display: block; margin-bottom: 6px; }
    .summary-value, .category-total { font-size: 1.34rem; white-space: nowrap; }
    .category-kicker { display: inline-flex; margin-bottom: 8px; color: var(--ink-soft); font-size: 0.7rem; }

    .story-row { padding: 20px; }
    .story-head { display: grid; grid-template-columns: minmax(0, 220px) 1fr; gap: 20px; align-items: start; }
    .story-month { display: grid; gap: 8px; }
    .story-index { color: var(--ink-soft); font-size: 0.7rem; letter-spacing: 0.16em; }
    .story-month strong { font-size: 1.4rem; line-height: 1; }
    .story-metrics { display: grid; gap: 14px; }
    .story-metric { display: grid; gap: 8px; }
    .story-meta { justify-content: space-between; align-items: baseline; }
    .story-label { color: var(--ink-soft); font-size: 0.72rem; }
    .story-number { font-size: 1.18rem; }
    .story-track, .ranking-track { height: 10px; overflow: hidden; border-radius: 999px; background: rgba(24, 22, 20, 0.08); }
    .story-fill, .ranking-fill { height: 100%; border-radius: 999px; }
    .story-fill.expense { background: linear-gradient(90deg, rgba(166, 69, 52, 0.98), rgba(166, 69, 52, 0.56)); }
    .story-fill.income { background: linear-gradient(90deg, rgba(45, 95, 66, 0.98), rgba(45, 95, 66, 0.56)); }
    .story-fill.balance-positive, .ranking-fill { background: linear-gradient(90deg, rgba(47, 107, 135, 0.96), rgba(205, 100, 63, 0.7)); }
    .story-fill.balance-negative { background: linear-gradient(90deg, rgba(166, 69, 52, 0.98), rgba(205, 100, 63, 0.62)); }

    .ranking-item { display: grid; gap: 14px; padding: 18px 20px; }
    .ranking-head { justify-content: space-between; align-items: flex-start; }
    .ranking-share { font-size: clamp(2rem, 5vw, 3.6rem); line-height: 0.88; }

    .currency-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .currency-card { padding: 20px; }
    .currency-header { justify-content: space-between; align-items: flex-start; margin-bottom: 18px; }
    .currency-code { font-family: "JetBrains Mono", monospace; font-size: 0.92rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; }
    .currency-balance { font-size: 1.5rem; }
    .currency-metrics { display: grid; gap: 14px; }
    .currency-metric { display: flex; padding-top: 12px; border-top: 1px solid var(--line); }
    .currency-metric span:first-child { color: var(--ink-soft); font-size: 0.72rem; }

    .empty-state { display: grid; gap: 18px; padding: 28px; border-style: dashed; border-radius: 28px; background: rgba(255, 250, 243, 0.52); }
    .empty-state h3, .empty-state p, .auth-copy, .auth-footer, .notice-box p { margin: 0; }

    .form-grid.two-columns { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .form-field { display: grid; gap: 10px; }
    .form-field.span-2, .span-2 { grid-column: span 2; }
    .field-label { font-size: 0.74rem; }
    .field-help { color: var(--ink-soft); font-size: 0.84rem; line-height: 1.55; }
    .field-control {
        width: 100%; min-height: 54px; padding: 0 18px; border: 1px solid var(--line); border-radius: 18px; background: rgba(255, 252, 247, 0.96);
        color: var(--ink); transition: border-color var(--transition), box-shadow var(--transition), background-color var(--transition);
    }
    textarea.field-control { min-height: 128px; padding: 16px 18px; resize: vertical; }
    .field-control:focus { outline: none; border-color: rgba(47, 107, 135, 0.38); box-shadow: 0 0 0 5px rgba(47, 107, 135, 0.08); background: #ffffff; }
    .input-group { position: relative; }
    .dropdown-list {
        position: absolute; left: 0; right: 0; top: calc(100% + 8px); display: none; max-height: 230px; overflow-y: auto; padding: 8px;
        border-radius: 22px; border: 1px solid var(--line); background: rgba(255, 252, 247, 0.98); box-shadow: var(--shadow-soft); z-index: 15;
    }
    .dropdown-list.show { display: block; }
    .dropdown-item { width: 100%; border: none; border-radius: 16px; background: transparent; text-align: left; padding: 11px 12px; color: var(--ink); }
    .dropdown-item:hover { background: var(--accent-soft); color: var(--accent-deep); }
    .notice-box { padding: 18px; }
    .inline-form { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 10px; align-items: center; margin-top: 14px; }

    .modal { position: fixed; inset: 0; z-index: 60; display: none; align-items: center; justify-content: center; padding: 24px; background: rgba(24, 22, 20, 0.42); backdrop-filter: blur(8px); }
    .modal.show { display: flex; }
    .modal-panel { width: min(780px, 100%); max-height: calc(100vh - 48px); overflow-y: auto; }
    .modal-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 22px; }
    .modal-close { width: 42px; height: 42px; color: var(--ink-soft); }

    .auth-page { overflow-x: hidden; }
    .auth-layout { position: relative; z-index: 1; min-height: 100vh; display: grid; place-items: center; padding: 28px 0; }
    .auth-shell { width: min(1240px, calc(100% - 40px)); display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(340px, 0.82fr); gap: 22px; align-items: stretch; }
    .auth-feature, .auth-panel { position: relative; z-index: 1; }
    .auth-feature {
        display: grid; gap: 28px; align-content: space-between; color: rgba(255, 250, 243, 0.96);
        background: linear-gradient(160deg, rgba(24, 22, 20, 0.96), rgba(54, 48, 40, 0.92)), radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 34%);
    }
    .auth-feature::after { content: ""; position: absolute; right: -100px; bottom: -100px; width: 320px; height: 320px; border-radius: 50%; background: rgba(205, 100, 63, 0.1); filter: blur(8px); }
    .auth-kicker { width: fit-content; color: rgba(255, 250, 243, 0.82); border-color: rgba(255, 250, 243, 0.16); background: rgba(255, 250, 243, 0.08); }
    .auth-title { margin: 0; max-width: 9ch; color: rgba(255, 250, 243, 0.98); font-size: clamp(3rem, 7vw, 5.6rem); line-height: 0.88; }
    .auth-copy { max-width: 48ch; color: rgba(255, 250, 243, 0.74); }
    .auth-beat {
        display: grid; grid-template-columns: auto 1fr; gap: 16px; padding: 18px 20px; border-color: rgba(255, 250, 243, 0.12); background: rgba(255, 250, 243, 0.06); box-shadow: none;
    }
    .auth-beat-index { color: rgba(255, 250, 243, 0.54); font-family: "JetBrains Mono", monospace; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; }
    .auth-beat p { color: rgba(255, 250, 243, 0.72); }
    .auth-panel { display: grid; gap: 22px; align-content: center; margin-top: 42px; }
    .text-link { color: var(--accent-deep); font-weight: 700; }

    .reveal { animation: fade-up 420ms ease both; }
    @keyframes fade-up { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 1160px) {
        .dashboard-grid, .stats-grid, .hero, .auth-shell, .story-head { grid-template-columns: 1fr; }
        .sticky-card { position: static; }
        .stats-grid .span-2 { grid-column: span 1; }
        .auth-panel { margin-top: 0; }
    }

    @media (max-width: 900px) {
        .topbar, .metric-grid, .hero-facts, .currency-grid, .form-grid.two-columns, .month-switcher { grid-template-columns: 1fr; }
        .topbar { padding-top: 16px; }
        .form-field.span-2, .span-2 { grid-column: span 1; }
        .transaction-row { grid-template-columns: 1fr; }
        .transaction-date { padding-right: 0; padding-bottom: 12px; border-right: none; border-bottom: 1px solid var(--line); }
        .transaction-amount, .transaction-actions { justify-items: start; text-align: left; justify-content: flex-start; }
    }

    @media (max-width: 720px) {
        .page-shell, .auth-shell { width: min(100% - 20px, 100%); }
        .section-card, .hero, .auth-feature, .auth-panel, .modal-panel { padding: 22px; }
        .topbar { top: 10px; margin-bottom: 22px; }
        .month-switcher, .inline-form, .topbar, .section-header { display: grid; }
        .month-label { order: -1; text-align: left; }
        .btn, .btn-small, .icon-button { width: 100%; }
        .hero-actions, .topbar-actions { width: 100%; }
    }

    @media (max-width: 560px) {
        .page-shell { padding-top: 16px; }
        .hero-title, .auth-title { max-width: 100%; }
        .brand { align-items: flex-start; }
        .brand-mark { width: 48px; height: 48px; }
    }
</style>
