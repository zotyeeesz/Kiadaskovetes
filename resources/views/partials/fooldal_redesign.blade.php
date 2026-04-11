<style>
    @import url("https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap");

    :root {
        --bg: #f3efe6;
        --bg-deep: #e4dbc9;
        --surface: rgba(255, 252, 247, 0.84);
        --surface-strong: #fffdfa;
        --ink: #1c2821;
        --muted: #617063;
        --line: rgba(28, 40, 33, 0.12);
        --line-strong: rgba(28, 40, 33, 0.22);
        --accent: #234738;
        --accent-deep: #173126;
        --accent-soft: rgba(35, 71, 56, 0.14);
        --accent-alt: #85996d;
        --income: #2f6b4b;
        --expense: #a15343;
        --shadow-soft: 0 24px 40px -28px rgba(28, 40, 33, 0.25);
        --shadow-panel: 0 28px 80px -48px rgba(28, 40, 33, 0.3);
    }

    body {
        font-family: "Manrope", "Segoe UI", sans-serif !important;
        color: var(--ink);
        background:
            radial-gradient(circle at 10% 8%, rgba(133, 153, 109, 0.28), transparent 28%),
            radial-gradient(circle at 90% 10%, rgba(35, 71, 56, 0.16), transparent 34%),
            linear-gradient(135deg, transparent 0 62%, rgba(133, 153, 109, 0.08) 62% 66%, transparent 66%),
            linear-gradient(180deg, var(--bg) 0%, var(--bg-deep) 100%) !important;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        background:
            linear-gradient(rgba(28, 40, 33, 0.028) 1px, transparent 1px),
            linear-gradient(90deg, rgba(28, 40, 33, 0.024) 1px, transparent 1px);
        background-size: 36px 36px;
        opacity: 0.5;
        mask-image: linear-gradient(180deg, rgba(0,0,0,0.8), rgba(0,0,0,0.25) 72%, transparent 100%);
    }

    .header,
    .page-hero,
    .left-column,
    .stats-box,
    .modal-content,
    .no-data,
    table,
    .stat-card,
    .month-inline-controls {
        position: relative;
        z-index: 1;
    }

    .header {
        position: sticky;
        top: 0;
        z-index: 100;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 20px;
        align-items: center;
        padding: 18px 28px !important;
        background: rgba(243, 239, 230, 0.84) !important;
        border-bottom: 1px solid var(--line);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .header::after {
        content: "";
        position: absolute;
        left: 28px;
        right: 28px;
        bottom: 0;
        height: 1px;
        background: linear-gradient(90deg, var(--accent) 0%, rgba(35, 71, 56, 0.08) 55%, transparent 100%);
    }

    .brand-block {
        display: grid;
        gap: 8px;
        min-width: 0;
    }

    .brand-kicker,
    .hero-kicker,
    .panel-kicker,
    .stat-card-label,
    .month-inline-current {
        font-family: "JetBrains Mono", monospace;
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .brand-kicker {
        color: var(--muted);
        font-size: 0.7rem;
    }

    .header h1 {
        margin: 0;
        font-family: "Fraunces", Georgia, serif;
        font-size: clamp(1.8rem, 2vw, 2.35rem);
        line-height: 0.98;
        letter-spacing: -0.04em;
        color: var(--accent);
    }

    .brand-copy {
        margin: 0;
        color: var(--muted);
        font-size: 0.96rem;
        max-width: 56ch;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: rgba(255, 252, 247, 0.7);
        color: var(--muted);
        font-size: 0.78rem;
        font-weight: 700;
    }

    .logout-btn,
    .add-btn,
    .action-btn,
    .confirm-btn {
        border-radius: 999px !important;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 800;
    }

    .logout-btn {
        min-height: 44px;
        padding: 0 18px;
        border: 1px solid var(--line-strong);
        background: rgba(255, 252, 247, 0.74) !important;
        color: var(--accent) !important;
        box-shadow: var(--shadow-soft);
        font-size: 0.82rem;
        transition: transform .18s ease, background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .logout-btn:hover {
        transform: translateY(-2px);
        background: rgba(255, 252, 247, 0.94) !important;
        border-color: rgba(28, 40, 33, 0.28);
        box-shadow: 0 20px 32px -24px rgba(28, 40, 33, 0.4);
    }

    .container {
        position: relative;
        z-index: 1;
        width: min(1480px, calc(100% - 32px));
        max-width: none;
        padding: 28px 0 42px !important;
        margin: 0 auto;
    }

    .page-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.9fr);
        gap: 24px;
        padding: clamp(24px, 3vw, 36px);
        margin-bottom: 28px;
        border-radius: 34px;
        border: 1px solid rgba(255,255,255,0.52);
        background:
            linear-gradient(180deg, rgba(255, 252, 247, 0.92), rgba(244, 238, 228, 0.78)),
            linear-gradient(135deg, rgba(35, 71, 56, 0.05), rgba(133, 153, 109, 0.08));
        box-shadow: var(--shadow-panel);
        overflow: hidden;
    }

    .page-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(28, 40, 33, 0.03) 1px, transparent 1px),
            linear-gradient(rgba(28, 40, 33, 0.028) 1px, transparent 1px);
        background-size: 28px 28px;
        mask-image: linear-gradient(180deg, rgba(0,0,0,0.4), transparent 85%);
        opacity: 0.45;
        pointer-events: none;
    }

    .hero-copy,
    .hero-side {
        position: relative;
        z-index: 1;
    }

    .hero-kicker,
    .panel-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--muted);
        font-size: 0.72rem;
        font-weight: 700;
    }

    .hero-kicker::before,
    .panel-kicker::before {
        content: "";
        width: 24px;
        height: 1px;
        background: currentColor;
        opacity: 0.45;
    }

    .page-intro {
        margin: 14px 0 12px;
        font-family: "Fraunces", Georgia, serif;
        font-size: clamp(2.6rem, 5vw, 4.6rem);
        line-height: 0.92;
        letter-spacing: -0.05em;
        max-width: 11ch;
    }

    .hero-copy-text {
        margin: 0;
        max-width: 58ch;
        color: var(--muted);
        font-size: 1rem;
        line-height: 1.72;
    }

    .page-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin: 24px 0 0;
        padding: 0;
    }

    .add-btn {
        min-height: 48px;
        padding: 0 18px;
        border: 1px solid var(--line);
        background: rgba(255,252,247,0.72) !important;
        color: var(--accent) !important;
        box-shadow: var(--shadow-soft);
        font-size: 0.8rem;
        transition: transform .18s ease, background-color .18s ease, box-shadow .18s ease, border-color .18s ease, color .18s ease;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        background: rgba(255,252,247,0.92) !important;
        border-color: var(--line-strong);
    }

    .add-btn.primary-btn {
        color: #f8f4ec !important;
        border-color: transparent;
        background: linear-gradient(135deg, var(--accent), var(--accent-deep)) !important;
        box-shadow: 0 18px 30px -18px rgba(23, 49, 38, 0.65);
    }

    .hero-side {
        display: grid;
        gap: 14px;
        align-content: start;
    }

    .hero-note,
    .hero-stat {
        border-radius: 22px;
        border: 1px solid var(--line);
        background: rgba(255, 252, 247, 0.8);
        box-shadow: var(--shadow-soft);
    }

    .hero-note {
        padding: 18px 18px 20px;
    }

    .hero-note strong,
    .transactions-title-row h2,
    .section-title {
        font-family: "Fraunces", Georgia, serif;
        font-weight: 600;
    }

    .hero-note strong {
        display: block;
        margin-bottom: 8px;
        font-size: 1.12rem;
        line-height: 1.15;
    }

    .hero-note p {
        margin: 0;
        color: var(--muted);
        line-height: 1.65;
    }

    .hero-stats {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .hero-stat {
        padding: 16px 18px;
    }

    .hero-stat-label {
        color: var(--muted);
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    .hero-stat-value {
        display: block;
        margin-top: 10px;
        font-family: "Fraunces", Georgia, serif;
        font-size: clamp(1.6rem, 2.8vw, 2.5rem);
        line-height: 0.95;
        letter-spacing: -0.05em;
    }

    .hero-stat-subtle {
        margin-top: 8px;
        color: var(--muted);
        font-size: 0.86rem;
    }

    .main-layout {
        display: grid !important;
        grid-template-columns: minmax(0, 1.7fr) minmax(360px, 1fr);
        gap: 28px !important;
        align-items: start;
    }

    .left-column,
    .stats-box {
        border-radius: 30px !important;
        border: 1px solid rgba(255,255,255,0.52);
        background:
            linear-gradient(180deg, rgba(255,252,247,0.9), rgba(245,239,230,0.76)),
            linear-gradient(135deg, rgba(35, 71, 56, 0.04), rgba(133, 153, 109, 0.05));
        box-shadow: var(--shadow-panel) !important;
        backdrop-filter: blur(18px);
    }

    .left-column {
        padding: 26px;
    }

    .right-column {
        min-width: 0;
        align-self: start;
    }

    .stats-box {
        position: sticky;
        top: 106px !important;
        padding: 24px !important;
    }

    .transactions-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 18px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--line);
    }

    .transactions-title-row h2,
    .section-title {
        margin: 0;
        font-size: 1.42rem;
        letter-spacing: -0.03em;
        color: var(--ink);
    }

    .transactions-caption,
    .section-copy {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 0.94rem;
    }

    .section-heading {
        display: grid;
        gap: 6px;
        margin-bottom: 18px;
    }

    .section-heading::after {
        display: none;
    }

    .month-inline-controls {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: rgba(255,252,247,0.84);
    }

    .month-inline-current {
        min-width: 146px;
        text-align: center;
        color: var(--muted);
        font-size: 0.72rem;
        font-weight: 700;
    }

    .month-nav-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid var(--line);
        text-decoration: none;
        color: var(--ink);
        background: rgba(255,252,247,0.98);
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: rgba(255,252,247,0.68) !important;
        border: 1px solid var(--line);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: none;
    }

    table th,
    table td {
        padding: 16px 14px;
        border-bottom: 1px solid rgba(28,40,33,0.08) !important;
        vertical-align: top;
    }

    table th {
        background: rgba(35, 71, 56, 0.07) !important;
        color: rgba(28, 40, 33, 0.74) !important;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    table tr:nth-child(even) td { background: rgba(255,252,247,0.42); }
    table tr:hover td { background: rgba(133,153,109,0.1) !important; }

    .amount-cell strong,
    .stat-card-value {
        font-family: "JetBrains Mono", monospace;
        font-variant-numeric: tabular-nums;
    }

    .amount-expense { color: var(--expense) !important; }
    .amount-income { color: var(--income) !important; }
    .converted-amount { color: var(--muted); }

    .type-badge {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 0.66rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .type-expense { background: rgba(161, 83, 67, 0.12) !important; color: #8d4134 !important; }
    .type-income { background: rgba(47, 107, 75, 0.13) !important; color: #28593e !important; }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .action-btn,
    .confirm-btn {
        min-height: 36px;
        padding: 0 12px;
        border: 1px solid var(--line) !important;
        background: rgba(255,252,247,0.88) !important;
        color: var(--accent) !important;
        font-size: 0.72rem;
        transition: transform .18s ease, background-color .18s ease, border-color .18s ease, color .18s ease;
    }

    .action-btn:hover,
    .confirm-btn:hover {
        transform: translateY(-1px);
        background: rgba(255,252,247,1) !important;
    }

    .action-btn-danger,
    .confirm-btn-danger {
        color: var(--expense) !important;
        border-color: rgba(161,83,67,0.24) !important;
        background: rgba(161,83,67,0.08) !important;
    }

    .no-data {
        display: grid;
        gap: 10px;
        padding: 30px;
        border-radius: 24px !important;
        border: 1px dashed rgba(28, 40, 33, 0.22);
        background: rgba(255,252,247,0.62) !important;
        color: var(--muted) !important;
        text-align: center;
    }

    .stats-summary {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
    }

    .stat-card {
        padding: 18px !important;
        border-radius: 22px !important;
        border: 1px solid var(--line);
        background: rgba(255,252,247,0.78) !important;
    }

    .stat-card-label {
        display: block;
        margin-bottom: 10px;
        color: var(--muted);
        font-size: 0.68rem;
        font-weight: 700;
    }

    .stat-card-value {
        font-size: 1.1rem;
        line-height: 1.35;
    }

    .stats-table th,
    .stats-table td {
        padding: 10px 10px;
        font-size: 0.84rem;
    }

    .stat-link {
        margin-top: 18px;
        text-align: left;
    }

    .stat-link a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent) !important;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-size: 0.76rem;
        text-decoration: none !important;
    }

    .modal {
        background: rgba(20, 31, 25, 0.56) !important;
        backdrop-filter: blur(8px);
    }

    .modal-content {
        width: min(560px, calc(100% - 32px));
        border-radius: 28px !important;
        border: 1px solid rgba(255,255,255,0.42);
        background: linear-gradient(180deg, rgba(255,252,247,0.98), rgba(243,238,229,0.96)) !important;
        box-shadow: 0 30px 80px -40px rgba(20, 31, 25, 0.52) !important;
        padding: 28px !important;
    }

    .confirm-modal-content {
        width: min(430px, calc(100% - 32px));
        padding: 24px !important;
    }

    .modal form input,
    .modal form textarea,
    .modal form select,
    .kategoria-input {
        display: block;
        width: 100%;
        margin: 14px 0;
        min-height: 52px;
        padding: 0 16px;
        border-radius: 18px !important;
        border: 1px solid var(--line) !important;
        background: rgba(255,252,247,0.94) !important;
        font-family: "Manrope", "Segoe UI", sans-serif !important;
        color: var(--ink);
    }

    .modal form textarea {
        min-height: 120px;
        padding: 16px;
    }

    .modal form input:focus,
    .modal form textarea:focus,
    .modal form select:focus,
    .kategoria-input:focus,
    .date-picker-trigger:focus-visible {
        border-color: rgba(35,71,56,0.32) !important;
        box-shadow: 0 0 0 4px rgba(35,71,56,0.1) !important;
        outline: none;
    }

    .modal form button[type="submit"] {
        width: 100%;
        min-height: 52px;
        border-radius: 20px !important;
        background: linear-gradient(135deg, var(--accent), var(--accent-deep)) !important;
        color: #f8f4ec !important;
        border: none;
        box-shadow: 0 18px 28px -18px rgba(23, 49, 38, 0.72);
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .type-toggle {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        padding: 6px;
        margin: 8px 0 14px;
        border-radius: 18px;
        border: 1px solid var(--line);
        background: rgba(240, 235, 225, 0.9);
    }

    .type-toggle-option {
        min-height: 44px;
        border: 1px solid transparent !important;
        border-radius: 14px;
        background: transparent !important;
        color: var(--muted) !important;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .type-toggle-option.active {
        background: rgba(255,252,247,0.96) !important;
        color: var(--accent) !important;
        box-shadow: var(--shadow-soft) !important;
    }

    .type-toggle-option[data-value="koltseg"].active {
        color: var(--expense) !important;
        background: rgba(161,83,67,0.08) !important;
    }

    .type-toggle-option[data-value="bevetel"].active {
        color: var(--income) !important;
        background: rgba(47,107,75,0.08) !important;
    }

    .date-field { position: relative; }

    .date-picker-trigger {
        width: 100%;
        min-height: 52px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        border-radius: 18px !important;
        border: 1px solid var(--line) !important;
        background: rgba(255,252,247,0.94) !important;
        color: var(--ink);
    }

    .date-picker-trigger-icon {
        width: 18px;
        height: 18px;
        border: 1.5px solid rgba(28,40,33,0.4);
        border-radius: 6px;
        position: relative;
    }

    .date-picker-trigger-icon::before {
        content: "";
        position: absolute;
        left: 3px;
        right: 3px;
        top: 4px;
        height: 2px;
        background: rgba(28,40,33,0.36);
        box-shadow: 0 5px 0 rgba(28,40,33,0.18);
    }

    .date-picker-popover {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: min(290px, 100%);
        padding: 12px;
        border-radius: 22px;
        border: 1px solid var(--line);
        background: rgba(255,252,247,0.98);
        box-shadow: var(--shadow-panel);
        display: none;
        z-index: 35;
    }

    .date-picker.open .date-picker-popover { display: block; }
    .date-picker.open-upward .date-picker-popover { top: auto; bottom: calc(100% + 10px); }

    .date-picker-header,
    .date-picker-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .date-picker-month {
        font-family: "Fraunces", Georgia, serif;
        font-size: 1rem;
    }

    .date-picker-nav,
    .date-picker-action {
        min-height: 32px;
        padding: 0 10px;
        border-radius: 999px;
        border: 1px solid var(--line) !important;
        background: rgba(255,252,247,0.96) !important;
        color: var(--ink) !important;
        box-shadow: none !important;
    }

    .date-picker-weekdays,
    .date-picker-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 4px;
    }

    .date-picker-weekdays { margin: 8px 0 6px; }

    .date-picker-weekday {
        text-align: center;
        font-size: 0.62rem;
        color: var(--muted);
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .date-picker-day {
        min-height: 34px;
        border-radius: 12px;
        border: 1px solid transparent !important;
        background: transparent !important;
        color: var(--ink) !important;
        font-weight: 700;
    }

    .date-picker-day:hover { background: rgba(35,71,56,0.06) !important; }
    .date-picker-day.is-outside { color: rgba(97,112,99,0.56) !important; }
    .date-picker-day.is-today { border-color: rgba(35,71,56,0.24) !important; }
    .date-picker-day.is-selected {
        background: rgba(35,71,56,0.1) !important;
        border-color: rgba(35,71,56,0.2) !important;
    }

    .kategoria-list {
        border: 1px solid var(--line) !important;
        border-radius: 20px !important;
        border-top: none !important;
        box-shadow: var(--shadow-soft) !important;
        background: rgba(255,252,247,0.98) !important;
    }

    .kategoria-item:hover { background: rgba(133,153,109,0.12) !important; }

    .confirm-meta {
        display: grid;
        gap: 10px;
        padding: 16px;
        margin-bottom: 18px;
        border-radius: 20px;
        border: 1px solid var(--line);
        background: rgba(255,252,247,0.78);
    }

    .confirm-meta-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 0.94rem;
    }

    .confirm-meta-label { color: var(--muted); font-weight: 700; }
    .confirm-meta-value { color: var(--ink); font-weight: 700; text-align: right; }
    .confirm-actions { display: flex; justify-content: flex-end; gap: 10px; }
    .confirm-btn-secondary { background: rgba(255,252,247,0.94) !important; color: var(--accent) !important; }

    @media (max-width: 1080px) {
        .page-hero,
        .main-layout { grid-template-columns: 1fr !important; }
        .stats-box { position: static; }
    }

    @media (max-width: 720px) {
        .header {
            grid-template-columns: 1fr;
            padding: 16px 18px !important;
        }

        .header-actions { justify-content: flex-start; }
        .container { width: calc(100% - 20px); }
        .page-hero,
        .left-column,
        .stats-box,
        .modal-content { padding: 20px !important; }
        .page-actions,
        .transactions-title-row,
        .confirm-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .add-btn,
        .logout-btn,
        .action-btn,
        .confirm-btn { width: 100%; }

        .hero-stats { grid-template-columns: 1fr; }
        .month-inline-controls { width: 100%; justify-content: space-between; }
        table { display: block; overflow-x: auto; white-space: nowrap; }
    }
</style>
