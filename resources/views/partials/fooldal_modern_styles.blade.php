<style>
    @import url("https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap");

    :root {
        --bg: #f6f8fc;
        --bg-deep: #edf2f8;
        --surface: rgba(255, 255, 255, 0.76);
        --surface-strong: rgba(255, 255, 255, 0.94);
        --ink: #0f1728;
        --muted: #667085;
        --line: rgba(15, 23, 40, 0.08);
        --line-strong: rgba(15, 23, 40, 0.14);
        --accent: #4361ee;
        --accent-deep: #2747d6;
        --accent-soft: rgba(67, 97, 238, 0.12);
        --accent-alt: #2bb6f6;
        --accent-alt-soft: rgba(43, 182, 246, 0.12);
        --income: #12b76a;
        --expense: #e5484d;
        --shadow-soft: 0 20px 50px -30px rgba(15, 23, 42, 0.26);
        --shadow-panel: 0 30px 80px -42px rgba(15, 23, 42, 0.2);
    }

    body {
        font-family: "Outfit", "Segoe UI", sans-serif !important;
        color: var(--ink);
        background:
            radial-gradient(circle at 10% 8%, rgba(67, 97, 238, 0.12), transparent 28%),
            radial-gradient(circle at 90% 10%, rgba(43, 182, 246, 0.08), transparent 30%),
            linear-gradient(180deg, var(--bg), var(--bg-deep)) !important;
        min-height: 100vh;
        overflow-x: hidden;
    }

    body::before,
    body::after {
        content: "";
        position: fixed;
        pointer-events: none;
        z-index: 0;
    }

    body::before {
        inset: 0;
        background:
            linear-gradient(rgba(16, 24, 40, 0.024) 1px, transparent 1px),
            linear-gradient(90deg, rgba(16, 24, 40, 0.022) 1px, transparent 1px);
        background-size: 36px 36px;
        opacity: 0.28;
        mask-image: linear-gradient(180deg, rgba(0,0,0,0.86), rgba(0,0,0,0.18) 74%, transparent 100%);
    }

    body::after {
        top: 120px;
        right: -80px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(67, 97, 238, 0.16), transparent 62%);
        filter: blur(12px);
        animation: orb-float 10s ease-in-out infinite alternate;
    }

    .interactive-panel {
        --mx: 50%;
        --my: 50%;
        --rx: 0deg;
        --ry: 0deg;
        --lift: 0px;
        position: relative;
        overflow: hidden;
        transform: perspective(1400px) rotateX(var(--rx)) rotateY(var(--ry)) translateY(var(--lift));
        transform-style: preserve-3d;
        transition: transform .18s ease, box-shadow .24s ease, border-color .24s ease;
        will-change: transform;
    }

    .interactive-panel::after {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        pointer-events: none;
        background: radial-gradient(circle at var(--mx) var(--my), rgba(67, 97, 238, 0.16), transparent 34%);
        opacity: 0;
        transition: opacity .2s ease;
    }

    .interactive-panel:hover {
        --lift: -4px;
    }

    .interactive-panel:hover::after {
        opacity: 1;
    }

    @keyframes orb-float {
        from { transform: translate3d(0, 0, 0) scale(1); }
        to { transform: translate3d(-40px, 30px, 0) scale(1.08); }
    }

    .header,
    .hero-board,
    .left-column,
    .stats-box,
    .modal-content,
    .no-data,
    .ledger-card,
    .stat-card,
    .currency-ribbon,
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
        gap: 18px;
        align-items: center;
        padding: 18px 28px !important;
        background: rgba(246, 248, 252, 0.78) !important;
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
        background: linear-gradient(90deg, var(--accent) 0%, rgba(67,97,238,0.22) 50%, transparent 100%);
    }

    .masthead {
        display: grid;
        gap: 4px;
        min-width: 0;
    }

    .masthead-kicker,
    .hero-kicker,
    .section-kicker,
    .stat-card-label,
    .month-inline-current,
    .ledger-note-label,
    .currency-code,
    .hero-micro,
    .header-link,
    .logout-btn {
        font-family: "IBM Plex Mono", monospace;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .masthead-kicker {
        color: var(--muted);
        font-size: 0.72rem;
        font-weight: 600;
    }

    .header h1 {
        margin: 0;
        font-size: clamp(1.8rem, 2vw, 2.3rem);
        line-height: 0.98;
        letter-spacing: -0.04em;
        font-weight: 700;
    }

    .masthead-copy {
        margin: 0;
        color: var(--muted);
        font-size: 0.95rem;
        line-height: 1.55;
        max-width: 52ch;
    }

    .header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .header-link,
    .logout-btn,
    .add-btn,
    .action-btn,
    .confirm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 18px;
        border-radius: 999px !important;
        text-decoration: none;
        font-size: 0.74rem;
        font-weight: 600;
        transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-color .18s ease, color .18s ease;
    }

    .header-link,
    .logout-btn {
        border: 1px solid var(--line-strong);
        background: rgba(255, 255, 255, 0.68) !important;
        color: var(--ink) !important;
        box-shadow: var(--shadow-soft);
    }

    .header-link:hover,
    .logout-btn:hover,
    .add-btn:hover,
    .action-btn:hover,
    .confirm-btn:hover {
        transform: translateY(-2px);
    }

    .container {
        position: relative;
        z-index: 1;
        width: min(1440px, calc(100% - 28px));
        max-width: none;
        padding: 28px 0 40px !important;
        margin: 0 auto;
    }

    .hero-board {
        display: grid;
        grid-template-columns: minmax(0, 1.16fr) minmax(320px, 0.96fr);
        gap: 18px;
        padding: clamp(24px, 3vw, 36px);
        margin-bottom: 26px;
        border: 1px solid rgba(255,255,255,0.56);
        border-radius: 34px;
        background:
            linear-gradient(180deg, rgba(255,255,255,0.9), rgba(248,250,255,0.84)),
            linear-gradient(135deg, rgba(67,97,238,0.04), rgba(43,182,246,0.03));
        box-shadow: var(--shadow-panel);
        overflow: hidden;
    }

    .hero-board::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 18% 12%, rgba(67,97,238,0.1), transparent 34%);
        pointer-events: none;
    }

    .hero-copy,
    .hero-mosaic {
        position: relative;
        z-index: 1;
    }

    .hero-kicker,
    .section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--muted);
        font-size: 0.72rem;
        font-weight: 600;
    }

    .hero-kicker::before,
    .section-kicker::before {
        content: "";
        width: 24px;
        height: 1px;
        background: currentColor;
        opacity: 0.46;
    }

    .page-intro {
        margin: 14px 0 12px;
        max-width: 10ch;
        font-size: clamp(3rem, 5vw, 4.9rem);
        line-height: 0.92;
        letter-spacing: -0.06em;
        font-weight: 700;
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
        min-height: 50px;
        border: 1px solid var(--line-strong);
        background: rgba(255,255,255,0.74) !important;
        color: var(--ink) !important;
        box-shadow: var(--shadow-soft);
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .add-btn.primary-btn {
        border-color: transparent;
        background: linear-gradient(135deg, var(--accent), var(--accent-deep)) !important;
        color: #f8fbff !important;
        box-shadow: 0 20px 34px -18px rgba(39, 71, 214, 0.38);
    }

    .add-btn.secondary-btn {
        border-color: rgba(67,97,238,0.18);
        background: rgba(67,97,238,0.08) !important;
        color: var(--accent-deep) !important;
    }

    .hero-mosaic {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-content: start;
    }

    .mosaic-card {
        min-height: 144px;
        padding: 18px;
        border-radius: 26px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.72);
        box-shadow: var(--shadow-soft);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .mosaic-card:hover {
        box-shadow: 0 28px 42px -26px rgba(15, 23, 42, 0.28);
        border-color: rgba(67,97,238,0.14);
    }

    .mosaic-card.accent {
        background: linear-gradient(145deg, rgba(67,97,238,0.12), rgba(255,255,255,0.8));
        border-color: rgba(67,97,238,0.14);
    }

    .mosaic-card.alt {
        background: linear-gradient(145deg, rgba(43,182,246,0.12), rgba(255,255,255,0.8));
        border-color: rgba(43,182,246,0.14);
    }

    .hero-micro {
        color: var(--muted);
        font-size: 0.7rem;
        font-weight: 600;
    }

    .mosaic-value {
        display: block;
        margin-top: 14px;
        font-size: clamp(1.7rem, 2.7vw, 2.5rem);
        line-height: 0.96;
        letter-spacing: -0.05em;
        font-weight: 700;
    }

    .mosaic-subtext {
        margin-top: 8px;
        color: var(--muted);
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .main-layout {
        display: grid !important;
        grid-template-columns: minmax(0, 1.72fr) minmax(340px, 0.94fr);
        gap: 24px !important;
        align-items: start;
    }

    .left-column,
    .stats-box {
        border: 1px solid rgba(255,255,255,0.56);
        border-radius: 30px;
        background:
            linear-gradient(180deg, rgba(255,255,255,0.88), rgba(248,251,255,0.82)),
            linear-gradient(135deg, rgba(67,97,238,0.03), rgba(43,182,246,0.02));
        box-shadow: var(--shadow-panel);
        backdrop-filter: blur(14px);
    }

    .left-column {
        padding: 24px;
    }

    .stats-box {
        position: sticky;
        top: 102px !important;
        padding: 22px !important;
    }

    .transactions-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 18px;
        margin-bottom: 18px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--line);
    }

    .section-heading {
        display: grid;
        gap: 8px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--line);
    }

    .transactions-title-row h2,
    .section-title {
        margin: 12px 0 0;
        font-size: 1.45rem;
        line-height: 1;
        letter-spacing: -0.04em;
        font-weight: 700;
    }

    .transactions-caption,
    .section-copy {
        margin: 6px 0 0;
        color: var(--muted);
        line-height: 1.68;
        max-width: 52ch;
    }

    .month-inline-controls {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.84);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
    }

    .month-inline-current {
        min-width: 148px;
        text-align: center;
        color: var(--muted);
        font-size: 0.68rem;
        font-weight: 600;
    }

    .month-nav-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.96);
        color: var(--ink);
        font-weight: 700;
        text-decoration: none;
    }

    .month-nav-btn.disabled {
        opacity: 0.35;
        pointer-events: none;
    }

    .ledger-list {
        display: grid;
        gap: 14px;
    }

    .ledger-card {
        display: grid;
        grid-template-columns: 96px minmax(0, 1fr);
        gap: 18px;
        padding: 18px;
        border-radius: 26px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.72);
        box-shadow: var(--shadow-soft);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .ledger-card:hover {
        box-shadow: 0 28px 42px -26px rgba(15, 23, 42, 0.26);
        border-color: rgba(67,97,238,0.14);
    }

    .ledger-card.is-income {
        background: linear-gradient(145deg, rgba(18,183,106,0.08), rgba(255,255,255,0.78));
    }

    .ledger-card.is-expense {
        background: linear-gradient(145deg, rgba(229,72,77,0.08), rgba(255,255,255,0.78));
    }

    .ledger-date {
        display: grid;
        gap: 6px;
        align-content: start;
        padding-right: 10px;
        border-right: 1px solid var(--line);
    }

    .ledger-day {
        font-size: 2.25rem;
        line-height: 0.88;
        letter-spacing: -0.06em;
        font-weight: 700;
    }

    .ledger-month {
        color: var(--muted);
        font-size: 0.84rem;
        line-height: 1.45;
    }

    .ledger-body {
        display: grid;
        gap: 14px;
        min-width: 0;
    }

    .ledger-top {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: flex-start;
    }

    .ledger-title-group {
        display: grid;
        gap: 8px;
        min-width: 0;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 0.66rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .type-expense {
        background: rgba(229,72,77,0.12) !important;
        color: var(--expense) !important;
    }

    .type-income {
        background: rgba(18,183,106,0.12) !important;
        color: var(--income) !important;
    }

    .ledger-category {
        margin: 0;
        font-size: 1.16rem;
        line-height: 1.12;
        font-weight: 700;
        letter-spacing: -0.03em;
    }

    .ledger-meta {
        color: var(--muted);
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .ledger-amount {
        display: grid;
        gap: 6px;
        justify-items: end;
        text-align: right;
        min-width: 170px;
    }

    .amount-main {
        font-size: 1.38rem;
        line-height: 0.98;
        letter-spacing: -0.04em;
        font-weight: 700;
    }

    .amount-expense,
    .converted-amount.amount-expense { color: var(--expense) !important; }

    .amount-income,
    .converted-amount.amount-income { color: var(--income) !important; }

    .converted-amount {
        display: block;
        color: var(--muted);
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.74rem;
        letter-spacing: 0.02em;
    }

    .ledger-note {
        display: grid;
        gap: 6px;
        margin: 0;
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid var(--line);
        background: rgba(16,24,40,0.03);
        color: var(--muted);
        line-height: 1.64;
    }

    .ledger-note-label {
        color: var(--ink);
        font-size: 0.68rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .action-btn,
    .confirm-btn {
        min-height: 38px;
        padding: 0 14px;
        border: 1px solid var(--line) !important;
        background: rgba(255,255,255,0.84) !important;
        color: var(--ink) !important;
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.68rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        box-shadow: none !important;
    }

    .action-btn-danger,
    .confirm-btn-danger {
        border-color: rgba(229,72,77,0.22) !important;
        background: rgba(229,72,77,0.08) !important;
        color: var(--expense) !important;
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
        background: rgba(255,255,255,0.76) !important;
        box-shadow: var(--shadow-soft);
        transition: transform .2s ease, border-color .2s ease;
    }

    .stat-card:hover {
        border-color: rgba(67,97,238,0.14);
    }

    .stat-card-label {
        display: block;
        margin-bottom: 10px;
        color: var(--muted);
        font-size: 0.68rem;
        font-weight: 600;
    }

    .stat-card-value {
        font-size: 1.22rem;
        line-height: 1.08;
        letter-spacing: -0.04em;
        font-weight: 700;
    }

    .currency-stack {
        display: grid;
        gap: 10px;
        margin-top: 10px;
    }

    .currency-ribbon {
        display: grid;
        gap: 10px;
        padding: 16px;
        border-radius: 22px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.72);
        transition: transform .2s ease, border-color .2s ease;
    }

    .currency-ribbon:hover {
        border-color: rgba(43,182,246,0.14);
    }

    .currency-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: baseline;
    }

    .currency-code {
        color: var(--muted);
        font-size: 0.68rem;
        font-weight: 600;
    }

    .currency-balance {
        font-size: 1.08rem;
        line-height: 1;
        font-weight: 700;
    }

    .currency-grid {
        display: grid;
        gap: 8px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .currency-cell {
        padding: 10px 12px;
        border-radius: 16px;
        background: rgba(16,24,40,0.035);
    }

    .currency-label {
        color: var(--muted);
        font-size: 0.72rem;
    }

    .currency-value {
        margin-top: 6px;
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.84rem;
        font-weight: 600;
    }

    .stat-link {
        margin-top: 18px;
        text-align: left;
    }

    .stat-link a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent-deep) !important;
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        text-decoration: none !important;
    }

    .no-data {
        display: grid;
        gap: 10px;
        padding: 32px;
        border-radius: 26px !important;
        border: 1px dashed var(--line-strong);
        background: rgba(255,255,255,0.72) !important;
        text-align: center;
        color: var(--muted) !important;
    }

    .modal {
        background: rgba(15, 23, 42, 0.46) !important;
        backdrop-filter: blur(8px);
    }

    .modal-content {
        width: min(560px, calc(100% - 28px));
        padding: 28px !important;
        border-radius: 30px !important;
        border: 1px solid rgba(255,255,255,0.6);
        background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(247,250,255,0.9)) !important;
        box-shadow: var(--shadow-panel) !important;
    }

    .confirm-modal-content {
        width: min(430px, calc(100% - 28px));
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
        background: rgba(255,255,255,0.9) !important;
        color: var(--ink);
        font-family: "Outfit", "Segoe UI", sans-serif !important;
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
        outline: none;
        border-color: rgba(67,97,238,0.28) !important;
        box-shadow: 0 0 0 4px rgba(67,97,238,0.1) !important;
    }

    .modal form button[type="submit"] {
        width: 100%;
        min-height: 54px;
        border-radius: 20px !important;
        border: none;
        background: linear-gradient(135deg, var(--accent), var(--accent-deep)) !important;
        color: #f8fbff !important;
        font-family: "IBM Plex Mono", monospace !important;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        box-shadow: 0 20px 34px -18px rgba(39, 71, 214, 0.38);
    }

    .type-toggle {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin: 10px 0 14px;
        padding: 6px;
        border-radius: 20px;
        border: 1px solid var(--line);
        background: rgba(16,24,40,0.04);
    }

    .type-toggle-option {
        min-height: 44px;
        border: 1px solid transparent !important;
        border-radius: 14px;
        background: transparent !important;
        color: var(--muted) !important;
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .type-toggle-option.active {
        background: rgba(255,255,255,0.92) !important;
        color: var(--ink) !important;
        box-shadow: var(--shadow-soft) !important;
    }

    .type-toggle-option[data-value="koltseg"].active {
        color: var(--expense) !important;
        background: rgba(229,72,77,0.08) !important;
    }

    .type-toggle-option[data-value="bevetel"].active {
        color: var(--income) !important;
        background: rgba(18,183,106,0.08) !important;
    }

    .date-field {
        position: relative;
        display: grid;
        gap: 8px;
    }

    .date-picker-trigger {
        width: 100%;
        min-height: 52px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 0 16px;
        border-radius: 18px !important;
        border: 1px solid var(--line) !important;
        background: rgba(255,255,255,0.9) !important;
        color: var(--ink);
    }

    .date-picker-trigger-value {
        font-weight: 500;
    }

    .date-picker-trigger-icon {
        position: relative;
        width: 18px;
        height: 18px;
        border-radius: 6px;
        border: 2px solid rgba(16,24,40,0.36);
    }

    .date-picker-trigger-icon::before {
        content: "";
        position: absolute;
        left: 3px;
        right: 3px;
        top: 4px;
        height: 2px;
        background: rgba(16,24,40,0.28);
        box-shadow: 0 5px 0 rgba(16,24,40,0.18);
    }

    .date-picker-popover {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: min(294px, 100%);
        padding: 12px;
        border-radius: 22px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.98);
        box-shadow: var(--shadow-panel);
        display: none;
        z-index: 35;
    }

    .date-picker.open .date-picker-popover {
        display: block;
    }

    .date-picker.open-upward .date-picker-popover {
        top: auto;
        bottom: calc(100% + 10px);
    }

    .date-picker-header,
    .date-picker-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .date-picker-month {
        font-size: 1rem;
        line-height: 1;
        font-weight: 700;
    }

    .date-picker-nav,
    .date-picker-action {
        min-height: 32px;
        padding: 0 10px;
        border-radius: 999px;
        border: 1px solid var(--line) !important;
        background: rgba(255,255,255,0.92) !important;
        color: var(--ink) !important;
        box-shadow: none !important;
    }

    .date-picker-weekdays,
    .date-picker-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 4px;
    }

    .date-picker-weekdays {
        margin: 8px 0 6px;
    }

    .date-picker-weekday {
        text-align: center;
        color: var(--muted);
        font-family: "IBM Plex Mono", monospace;
        font-size: 0.62rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .date-picker-day {
        min-height: 34px;
        border-radius: 12px;
        border: 1px solid transparent !important;
        background: transparent !important;
        color: var(--ink) !important;
        font-weight: 600;
        box-shadow: none !important;
    }

    .date-picker-day:hover,
    .date-picker-action:hover,
    .date-picker-nav:hover {
        background: rgba(67,97,238,0.08) !important;
    }

    .date-picker-day.is-outside {
        color: rgba(102,112,133,0.56) !important;
    }

    .date-picker-day.is-today {
        border-color: rgba(67,97,238,0.24) !important;
    }

    .date-picker-day.is-selected,
    .date-picker-action-primary {
        background: rgba(67,97,238,0.12) !important;
        border-color: rgba(67,97,238,0.22) !important;
    }

    .kategoria-list {
        border: 1px solid var(--line) !important;
        border-radius: 20px !important;
        box-shadow: var(--shadow-soft) !important;
        background: rgba(255,255,255,0.98) !important;
    }

    .kategoria-item:hover {
        background: rgba(67,97,238,0.08) !important;
    }

    .confirm-meta {
        display: grid;
        gap: 10px;
        padding: 16px;
        margin: 0 0 18px;
        border-radius: 20px;
        border: 1px solid var(--line);
        background: rgba(16,24,40,0.03);
    }

    .confirm-meta-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 0.92rem;
    }

    .confirm-meta-label {
        color: var(--muted);
        font-weight: 600;
    }

    .confirm-meta-value {
        color: var(--ink);
        font-weight: 600;
        text-align: right;
    }

    @media (max-width: 1120px) {
        .hero-board,
        .main-layout {
            grid-template-columns: 1fr !important;
        }

        .stats-box {
            position: static !important;
        }
    }

    @media (max-width: 760px) {
        .header {
            grid-template-columns: 1fr;
            padding: 16px 18px !important;
        }

        .header-actions {
            justify-content: flex-start;
        }

        .container {
            width: calc(100% - 18px);
        }

        .hero-board,
        .left-column,
        .stats-box,
        .modal-content {
            padding: 20px !important;
        }

        .hero-mosaic,
        .transactions-title-row,
        .page-actions,
        .action-buttons,
        .confirm-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .add-btn,
        .header-link,
        .logout-btn,
        .action-btn,
        .confirm-btn {
            width: 100%;
        }

        .month-inline-controls {
            width: 100%;
            justify-content: space-between;
        }

        .ledger-card {
            grid-template-columns: 1fr;
        }

        .ledger-date {
            border-right: none;
            border-bottom: 1px solid var(--line);
            padding-right: 0;
            padding-bottom: 12px;
        }

        .ledger-top {
            flex-direction: column;
        }

        .ledger-amount {
            justify-items: start;
            text-align: left;
            min-width: 0;
        }
    }
</style>
