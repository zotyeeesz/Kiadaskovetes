<style>
    @import url("https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Space+Grotesk:wght@400;500;700&family=JetBrains+Mono:wght@500;700&display=swap");

    :root {
        --paper: #f4eee6;
        --paper-deep: #ded0c1;
        --paper-soft: rgba(255, 250, 244, 0.86);
        --ink: #111111;
        --muted: #5b5854;
        --line: rgba(17, 17, 17, 0.12);
        --line-strong: rgba(17, 17, 17, 0.24);
        --accent: #f05a28;
        --accent-deep: #bf3e14;
        --accent-soft: rgba(240, 90, 40, 0.12);
        --accent-alt: #245cff;
        --accent-alt-soft: rgba(36, 92, 255, 0.1);
        --income: #0f8c57;
        --expense: #b82f25;
        --shadow-soft: 0 18px 28px -20px rgba(17, 17, 17, 0.45);
        --shadow-panel: 0 34px 80px -42px rgba(17, 17, 17, 0.36);
    }

    body {
        font-family: "Space Grotesk", "Segoe UI", sans-serif !important;
        color: var(--ink);
        background:
            radial-gradient(circle at 9% 10%, rgba(240, 90, 40, 0.18), transparent 28%),
            radial-gradient(circle at 88% 12%, rgba(36, 92, 255, 0.12), transparent 32%),
            linear-gradient(145deg, transparent 0 58%, rgba(36, 92, 255, 0.05) 58% 62%, transparent 62%),
            linear-gradient(180deg, var(--paper), var(--paper-deep)) !important;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        background:
            linear-gradient(rgba(17, 17, 17, 0.028) 1px, transparent 1px),
            linear-gradient(90deg, rgba(17, 17, 17, 0.024) 1px, transparent 1px);
        background-size: 34px 34px;
        opacity: 0.46;
        mask-image: linear-gradient(180deg, rgba(0,0,0,0.88), rgba(0,0,0,0.26) 72%, transparent 100%);
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
        background: rgba(244, 238, 230, 0.84) !important;
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
        height: 3px;
        background: linear-gradient(90deg, var(--accent) 0%, var(--accent-alt) 42%, transparent 72%);
        opacity: 0.9;
    }

    .masthead {
        display: grid;
        gap: 6px;
        min-width: 0;
    }

    .masthead-kicker,
    .hero-kicker,
    .section-kicker,
    .stat-card-label,
    .month-inline-current,
    .ledger-note-label,
    .currency-code,
    .hero-micro {
        font-family: "JetBrains Mono", monospace;
        font-size: 0.72rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 700;
    }

    .masthead-kicker {
        color: var(--muted);
    }

    .header h1 {
        margin: 0;
        font-family: "Fraunces", Georgia, serif;
        font-size: clamp(1.9rem, 2vw, 2.4rem);
        line-height: 0.95;
        letter-spacing: -0.05em;
        color: var(--ink);
    }

    .masthead-copy {
        margin: 0;
        color: var(--muted);
        font-size: 0.92rem;
        line-height: 1.55;
        max-width: 58ch;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
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
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        transition: transform .18s ease, background-color .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease;
    }

    .header-link,
    .logout-btn {
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.76) !important;
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

    .logout-btn {
        border-color: var(--line-strong);
    }

    .container {
        position: relative;
        z-index: 1;
        width: min(1460px, calc(100% - 28px));
        max-width: none;
        padding: 28px 0 40px !important;
        margin: 0 auto;
    }

    .hero-board {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.95fr);
        gap: 20px;
        padding: clamp(24px, 3vw, 38px);
        margin-bottom: 28px;
        border: 2px solid rgba(17, 17, 17, 0.08);
        border-radius: 34px 34px 24px 34px;
        background:
            linear-gradient(180deg, rgba(255, 250, 244, 0.94), rgba(244, 235, 226, 0.84)),
            linear-gradient(135deg, rgba(240, 90, 40, 0.04), rgba(36, 92, 255, 0.06));
        box-shadow: var(--shadow-panel);
        overflow: hidden;
    }

    .hero-board::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(17, 17, 17, 0.03) 1px, transparent 1px),
            linear-gradient(rgba(17, 17, 17, 0.026) 1px, transparent 1px);
        background-size: 28px 28px;
        mask-image: linear-gradient(180deg, rgba(0,0,0,0.36), transparent 86%);
        opacity: 0.48;
        pointer-events: none;
    }

    .hero-board::after {
        content: "";
        position: absolute;
        right: -48px;
        top: 28px;
        width: 190px;
        height: 190px;
        border-radius: 38px;
        background: linear-gradient(135deg, rgba(240, 90, 40, 0.18), rgba(36, 92, 255, 0.14));
        transform: rotate(14deg);
        opacity: 0.85;
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
    }

    .hero-kicker::before,
    .section-kicker::before {
        content: "";
        width: 26px;
        height: 2px;
        background: currentColor;
        opacity: 0.48;
    }

    .page-intro {
        margin: 16px 0 12px;
        max-width: 10ch;
        font-family: "Fraunces", Georgia, serif;
        font-size: clamp(3rem, 5vw, 5.2rem);
        line-height: 0.9;
        letter-spacing: -0.06em;
        color: var(--ink);
    }

    .hero-copy-text {
        margin: 0;
        max-width: 60ch;
        color: var(--muted);
        font-size: 1rem;
        line-height: 1.75;
    }

    .page-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin: 26px 0 0;
        padding: 0;
    }

    .add-btn {
        min-height: 50px;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.84) !important;
        color: var(--ink) !important;
        box-shadow: var(--shadow-soft);
    }

    .add-btn.primary-btn {
        border-color: transparent;
        background: linear-gradient(135deg, var(--accent), var(--accent-deep)) !important;
        color: #fff7f2 !important;
        box-shadow: 0 20px 30px -18px rgba(191, 62, 20, 0.72);
    }

    .add-btn.secondary-btn {
        background: rgba(36, 92, 255, 0.08) !important;
        border-color: rgba(36, 92, 255, 0.22);
        color: var(--accent-alt) !important;
    }

    .hero-mosaic {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-content: start;
    }

    .mosaic-card {
        min-height: 146px;
        padding: 18px;
        border-radius: 26px;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.8);
        box-shadow: var(--shadow-soft);
    }

    .mosaic-card.accent {
        background: linear-gradient(145deg, rgba(240, 90, 40, 0.14), rgba(255, 250, 244, 0.86));
        border-color: rgba(240, 90, 40, 0.24);
    }

    .mosaic-card.alt {
        background: linear-gradient(145deg, rgba(36, 92, 255, 0.12), rgba(255, 250, 244, 0.86));
        border-color: rgba(36, 92, 255, 0.2);
    }

    .hero-micro {
        color: var(--muted);
    }

    .mosaic-value {
        display: block;
        margin-top: 14px;
        font-family: "Fraunces", Georgia, serif;
        font-size: clamp(1.7rem, 2.8vw, 2.55rem);
        line-height: 0.95;
        letter-spacing: -0.05em;
    }

    .mosaic-subtext {
        margin-top: 10px;
        color: var(--muted);
        font-size: 0.9rem;
        line-height: 1.55;
    }

    .main-layout {
        display: grid !important;
        grid-template-columns: minmax(0, 1.75fr) minmax(340px, 0.95fr);
        gap: 24px !important;
        align-items: start;
    }

    .left-column,
    .stats-box {
        border: 2px solid rgba(17, 17, 17, 0.08);
        background:
            linear-gradient(180deg, rgba(255, 250, 244, 0.92), rgba(244, 235, 226, 0.78)),
            linear-gradient(135deg, rgba(240, 90, 40, 0.03), rgba(36, 92, 255, 0.04));
        box-shadow: var(--shadow-panel);
        backdrop-filter: blur(12px);
    }

    .left-column {
        border-radius: 34px 34px 22px 34px;
        padding: 26px;
    }

    .right-column {
        min-width: 0;
        align-self: start;
    }

    .stats-box {
        position: sticky;
        top: 102px !important;
        border-radius: 28px 28px 34px 22px !important;
        padding: 22px !important;
    }

    .transactions-title-row,
    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 18px;
        margin-bottom: 18px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--line);
    }

    .section-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .transactions-title-row h2,
    .section-title {
        margin: 12px 0 0;
        font-family: "Fraunces", Georgia, serif;
        font-size: 1.55rem;
        line-height: 1;
        letter-spacing: -0.05em;
        color: var(--ink);
    }

    .transactions-caption,
    .section-copy {
        margin: 8px 0 0;
        color: var(--muted);
        line-height: 1.68;
        max-width: 54ch;
    }

    .month-inline-controls {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.86);
    }

    .month-inline-current {
        min-width: 148px;
        text-align: center;
        color: var(--muted);
    }

    .month-nav-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.96);
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
        grid-template-columns: 110px minmax(0, 1fr);
        gap: 18px;
        padding: 18px;
        border-radius: 28px 28px 18px 28px;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.8);
        box-shadow: var(--shadow-soft);
    }

    .ledger-card.is-income {
        background: linear-gradient(145deg, rgba(15, 140, 87, 0.08), rgba(255, 250, 244, 0.84));
    }

    .ledger-card.is-expense {
        background: linear-gradient(145deg, rgba(184, 47, 37, 0.08), rgba(255, 250, 244, 0.84));
    }

    .ledger-date {
        display: grid;
        gap: 6px;
        align-content: start;
        padding-right: 12px;
        border-right: 1px solid var(--line);
    }

    .ledger-day {
        font-family: "Fraunces", Georgia, serif;
        font-size: 2.3rem;
        line-height: 0.86;
        letter-spacing: -0.06em;
    }

    .ledger-month {
        color: var(--muted);
        font-size: 0.84rem;
        line-height: 1.4;
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
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .type-expense { background: rgba(184, 47, 37, 0.12) !important; color: var(--expense) !important; }
    .type-income { background: rgba(15, 140, 87, 0.12) !important; color: var(--income) !important; }

    .ledger-category {
        margin: 0;
        font-size: 1.24rem;
        line-height: 1.1;
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
        font-family: "Fraunces", Georgia, serif;
        font-size: 1.45rem;
        line-height: 0.96;
        letter-spacing: -0.04em;
    }

    .amount-expense,
    .converted-amount.amount-expense { color: var(--expense) !important; }
    .amount-income,
    .converted-amount.amount-income { color: var(--income) !important; }

    .converted-amount {
        display: block;
        margin-top: 0;
        color: var(--muted);
        font-family: "JetBrains Mono", monospace;
        font-size: 0.74rem;
        letter-spacing: 0.02em;
    }

    .ledger-note {
        display: grid;
        gap: 6px;
        margin: 0;
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(17, 17, 17, 0.08);
        background: rgba(17, 17, 17, 0.03);
        color: var(--muted);
        line-height: 1.65;
    }

    .ledger-note-label {
        color: var(--ink);
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
        background: rgba(255, 250, 244, 0.88) !important;
        color: var(--ink) !important;
        box-shadow: none !important;
    }

    .action-btn-danger,
    .confirm-btn-danger {
        border-color: rgba(184, 47, 37, 0.24) !important;
        background: rgba(184, 47, 37, 0.08) !important;
        color: var(--expense) !important;
    }

    .stats-summary {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
    }

    .stat-card {
        padding: 18px !important;
        border-radius: 22px 22px 14px 22px !important;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.82) !important;
        box-shadow: var(--shadow-soft);
    }

    .stat-card-label {
        display: block;
        margin-bottom: 10px;
        color: var(--muted);
    }

    .stat-card-value {
        font-family: "Fraunces", Georgia, serif;
        font-size: 1.28rem;
        line-height: 1.08;
        letter-spacing: -0.04em;
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
        background: rgba(255, 250, 244, 0.76);
    }

    .currency-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: baseline;
    }

    .currency-code {
        color: var(--muted);
    }

    .currency-balance {
        font-family: "Fraunces", Georgia, serif;
        font-size: 1.12rem;
        line-height: 1;
    }

    .currency-grid {
        display: grid;
        gap: 8px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .currency-cell {
        padding: 10px 12px;
        border-radius: 16px;
        background: rgba(17, 17, 17, 0.035);
    }

    .currency-label {
        color: var(--muted);
        font-size: 0.72rem;
    }

    .currency-value {
        margin-top: 6px;
        font-family: "JetBrains Mono", monospace;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .stat-link {
        margin-top: 18px;
        text-align: left;
    }

    .stat-link a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent-alt) !important;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        text-decoration: none !important;
    }

    .no-data {
        display: grid;
        gap: 10px;
        padding: 32px;
        border-radius: 28px 28px 18px 28px !important;
        border: 1px dashed rgba(17, 17, 17, 0.22);
        background: rgba(255, 250, 244, 0.72) !important;
        text-align: center;
        color: var(--muted) !important;
    }

    .no-data p {
        margin: 0;
        line-height: 1.7;
    }

    .modal {
        background: rgba(17, 17, 17, 0.46) !important;
        backdrop-filter: blur(8px);
    }

    .modal-content {
        width: min(580px, calc(100% - 28px));
        padding: 28px !important;
        border-radius: 32px 32px 20px 32px !important;
        border: 2px solid rgba(17, 17, 17, 0.08);
        background:
            linear-gradient(180deg, rgba(255, 250, 244, 0.98), rgba(243, 233, 224, 0.96)) !important;
        box-shadow: var(--shadow-panel) !important;
    }

    .confirm-modal-content {
        width: min(430px, calc(100% - 28px));
        padding: 24px !important;
    }

    .close-btn:hover {
        color: var(--accent) !important;
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
        border-radius: 20px !important;
        border: 1px solid var(--line) !important;
        background: rgba(255, 250, 244, 0.92) !important;
        color: var(--ink);
        font-family: "Space Grotesk", "Segoe UI", sans-serif !important;
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
        border-color: rgba(36, 92, 255, 0.28) !important;
        box-shadow: 0 0 0 4px rgba(36, 92, 255, 0.1) !important;
    }

    .modal form button[type="submit"] {
        width: 100%;
        min-height: 54px;
        border-radius: 22px !important;
        border: none;
        background: linear-gradient(135deg, var(--accent), var(--accent-deep)) !important;
        color: #fff7f2 !important;
        font-family: "Space Grotesk", "Segoe UI", sans-serif !important;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        box-shadow: 0 20px 30px -18px rgba(191, 62, 20, 0.72);
    }

    .type-toggle {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin: 10px 0 14px;
        padding: 6px;
        border-radius: 22px;
        border: 1px solid var(--line);
        background: rgba(17, 17, 17, 0.05);
    }

    .type-toggle-option {
        min-height: 44px;
        border: 1px solid transparent !important;
        border-radius: 16px;
        background: transparent !important;
        color: var(--muted) !important;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .type-toggle-option.active {
        background: rgba(255, 250, 244, 0.94) !important;
        box-shadow: var(--shadow-soft) !important;
        color: var(--ink) !important;
    }

    .type-toggle-option[data-value="koltseg"].active {
        color: var(--expense) !important;
        background: rgba(184, 47, 37, 0.08) !important;
    }

    .type-toggle-option[data-value="bevetel"].active {
        color: var(--income) !important;
        background: rgba(15, 140, 87, 0.08) !important;
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
        border-radius: 20px !important;
        border: 1px solid var(--line) !important;
        background: rgba(255, 250, 244, 0.92) !important;
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
        border: 2px solid rgba(17, 17, 17, 0.36);
    }

    .date-picker-trigger-icon::before {
        content: "";
        position: absolute;
        left: 3px;
        right: 3px;
        top: 4px;
        height: 2px;
        background: rgba(17, 17, 17, 0.28);
        box-shadow: 0 5px 0 rgba(17, 17, 17, 0.18);
    }

    .date-picker-popover {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        width: min(294px, 100%);
        padding: 12px;
        border-radius: 24px;
        border: 1px solid var(--line);
        background: rgba(255, 250, 244, 0.98);
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
        font-family: "Fraunces", Georgia, serif;
        font-size: 1rem;
        line-height: 1;
    }

    .date-picker-nav,
    .date-picker-action {
        min-height: 32px;
        padding: 0 10px;
        border-radius: 999px;
        border: 1px solid var(--line) !important;
        background: rgba(255, 250, 244, 0.92) !important;
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
        font-size: 0.62rem;
        font-weight: 700;
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
        box-shadow: none !important;
    }

    .date-picker-day:hover,
    .date-picker-action:hover,
    .date-picker-nav:hover {
        background: rgba(36, 92, 255, 0.08) !important;
    }

    .date-picker-day.is-outside {
        color: rgba(91, 88, 84, 0.56) !important;
    }

    .date-picker-day.is-today {
        border-color: rgba(36, 92, 255, 0.24) !important;
    }

    .date-picker-day.is-selected,
    .date-picker-action-primary {
        background: rgba(240, 90, 40, 0.1) !important;
        border-color: rgba(240, 90, 40, 0.22) !important;
    }

    .kategoria-list {
        border: 1px solid var(--line) !important;
        border-radius: 20px !important;
        box-shadow: var(--shadow-soft) !important;
        background: rgba(255, 250, 244, 0.98) !important;
    }

    .kategoria-item:hover {
        background: rgba(36, 92, 255, 0.08) !important;
    }

    .confirm-meta {
        display: grid;
        gap: 10px;
        padding: 16px;
        margin: 0 0 18px;
        border-radius: 22px;
        border: 1px solid var(--line);
        background: rgba(17, 17, 17, 0.04);
    }

    .confirm-meta-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        font-size: 0.92rem;
    }

    .confirm-meta-label {
        color: var(--muted);
        font-weight: 700;
    }

    .confirm-meta-value {
        color: var(--ink);
        font-weight: 700;
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
            grid-template-columns: 1fr;
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
