<style>
    :root {
        --text-primary: #10233f;
        --text-secondary: #5a6c84;
        --text-tertiary: #8697ae;
        --accent: #0a84ff;
        --accent-strong: #0067d8;
        --accent-soft: rgba(10, 132, 255, 0.16);
        --success: #1fb85c;
        --success-soft: rgba(31, 184, 92, 0.14);
        --danger: #ff5f57;
        --danger-soft: rgba(255, 95, 87, 0.16);
        --warning: #ffbd2e;
        --window-bg: rgba(243, 248, 255, 0.58);
        --window-bg-strong: rgba(255, 255, 255, 0.74);
        --window-dark: rgba(18, 28, 52, 0.56);
        --line-soft: rgba(255, 255, 255, 0.44);
        --line-strong: rgba(126, 152, 191, 0.28);
        --shadow-window: 0 36px 90px rgba(14, 31, 69, 0.22), 0 12px 32px rgba(14, 31, 69, 0.12);
        --shadow-card: 0 18px 40px rgba(14, 31, 69, 0.12);
        --radius-window: 30px;
        --radius-card: 24px;
        --radius-control: 18px;
        --transition: 180ms ease;
        --blur: blur(24px) saturate(180%);
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        min-height: 100vh;
        font-family: "SF Pro Display", "SF Pro Text", "Helvetica Neue", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--text-primary);
        background:
            radial-gradient(circle at 12% 14%, rgba(255, 94, 122, 0.98), transparent 33%),
            radial-gradient(circle at 86% 10%, rgba(62, 189, 255, 0.92), transparent 36%),
            radial-gradient(circle at 78% 72%, rgba(255, 188, 92, 0.74), transparent 20%),
            linear-gradient(148deg, #54c7ff 0%, #4e8dff 24%, #f7f9ff 55%, #ffb48a 73%, #ff5f7d 100%);
        background-attachment: fixed;
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
        top: 18%;
        right: -12%;
        width: 58vw;
        height: 58vw;
        min-width: 560px;
        min-height: 560px;
        border-radius: 40% 60% 70% 30% / 44% 41% 59% 56%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.18));
        filter: blur(14px);
        opacity: 0.72;
    }

    body::after {
        left: -10%;
        bottom: -18%;
        width: 54vw;
        height: 40vw;
        min-width: 460px;
        min-height: 360px;
        border-radius: 52% 48% 46% 54% / 62% 38% 62% 38%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.54), rgba(255, 255, 255, 0.08));
        filter: blur(20px);
        opacity: 0.56;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    button,
    input,
    textarea,
    select {
        font: inherit;
    }

    button {
        cursor: pointer;
    }

    img {
        display: block;
        max-width: 100%;
    }

    .page-shell,
    .auth-shell {
        position: relative;
        z-index: 1;
        width: min(1440px, calc(100% - 38px));
        margin: 0 auto;
    }

    .page-shell {
        padding: 28px 0 48px;
    }

    .auth-shell {
        min-height: calc(100vh - 24px);
        display: grid;
        place-items: center;
        padding: 28px 0 36px;
    }

    .menu-bar {
        position: sticky;
        top: 0;
        z-index: 40;
        padding: 12px 0 0;
    }

    .menu-bar-inner {
        width: min(1440px, calc(100% - 38px));
        margin: 0 auto;
        min-height: 50px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 0 16px;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        background: rgba(247, 251, 255, 0.34);
        box-shadow: 0 10px 26px rgba(11, 24, 53, 0.1);
        backdrop-filter: blur(24px) saturate(170%);
        -webkit-backdrop-filter: blur(24px) saturate(170%);
    }

    .menu-brand,
    .menu-status,
    .window-header,
    .window-toolbar,
    .window-actions,
    .nav-link,
    .transaction-title-row,
    .transaction-meta,
    .action-set,
    .month-switcher,
    .detail-row,
    .currency-row,
    .ranking-head,
    .story-headline,
    .story-meta,
    .date-picker-header,
    .date-picker-actions,
    .confirm-meta-row,
    .confirm-actions,
    .inline-form {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .menu-brand {
        min-width: 0;
    }

    .apple-mark {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.44));
        box-shadow:
            inset 0 0 0 1px rgba(255, 255, 255, 0.44),
            0 8px 16px rgba(10, 23, 50, 0.12);
    }

    .menu-title {
        font-size: 0.88rem;
        font-weight: 620;
        letter-spacing: -0.01em;
    }

    .menu-status {
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .menu-pill,
    .badge,
    .micro-pill,
    .type-badge {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 0 11px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.48);
        background: rgba(255, 255, 255, 0.5);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.56);
        color: var(--text-secondary);
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }

    .micro-pill {
        min-height: 24px;
        padding: 0 9px;
        font-size: 0.68rem;
    }

    .window {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius-window);
        border: 1px solid rgba(255, 255, 255, 0.42);
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.68), rgba(238, 246, 255, 0.42)),
            linear-gradient(135deg, rgba(94, 191, 255, 0.08), rgba(255, 95, 123, 0.08));
        box-shadow: var(--shadow-window);
        backdrop-filter: var(--blur);
        -webkit-backdrop-filter: var(--blur);
    }

    .window::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        border-radius: inherit;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.42), transparent 34%),
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.42), transparent 34%);
    }

    .window-dark {
        background:
            linear-gradient(160deg, rgba(18, 28, 52, 0.84), rgba(24, 38, 72, 0.54)),
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.12), transparent 35%);
        color: rgba(247, 250, 255, 0.96);
        border-color: rgba(255, 255, 255, 0.16);
    }

    .window-dark::before {
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.08), transparent 34%),
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.08), transparent 28%);
    }

    .window-dark .window-title,
    .window-dark .section-title,
    .window-dark .hero-title,
    .window-dark .auth-title,
    .window-dark .metric-value,
    .window-dark .auth-beat strong,
    .window-dark .menu-title {
        color: rgba(248, 250, 255, 0.98);
    }

    .window-dark .window-subtitle,
    .window-dark .section-copy,
    .window-dark .auth-copy,
    .window-dark .auth-beat p,
    .window-dark .metric-label,
    .window-dark .muted-text {
        color: rgba(224, 233, 248, 0.74);
    }

    .window-dark .metric-card,
    .window-dark .auth-beat,
    .window-dark .hero-fact {
        border-color: rgba(255, 255, 255, 0.12);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: none;
    }

    .window-dark .badge,
    .window-dark .menu-pill {
        border-color: rgba(255, 255, 255, 0.14);
        background: rgba(255, 255, 255, 0.08);
        color: rgba(232, 238, 250, 0.78);
    }

    .window-header,
    .window-toolbar {
        justify-content: space-between;
        padding: 15px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.34);
        position: relative;
        z-index: 1;
    }

    .window-toolbar {
        align-items: flex-start;
    }

    .window-title-group,
    .sidebar-stack,
    .main-stack,
    .detail-list,
    .flash-stack,
    .metric-grid,
    .summary-list,
    .currency-list,
    .transaction-stream,
    .story-list,
    .ranking-list,
    .auth-grid,
    .auth-beat-list,
    .form-grid,
    .field-group,
    .control-stack {
        display: grid;
        gap: 12px;
    }

    .window-title-group {
        gap: 2px;
        min-width: 0;
    }

    .window-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 640;
        letter-spacing: -0.02em;
    }

    .window-subtitle,
    .section-copy,
    .muted-text,
    .transaction-note,
    .flash {
        margin: 0;
        color: var(--text-secondary);
        line-height: 1.52;
        font-size: 0.92rem;
    }

    .window-actions {
        margin-left: auto;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .window-body {
        position: relative;
        z-index: 1;
        padding: 18px;
    }

    .window-controls {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .window-control {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        box-shadow:
            inset 0 1px 1px rgba(255, 255, 255, 0.4),
            0 0 0 1px rgba(0, 0, 0, 0.08);
    }

    .window-control-close {
        background: #ff5f57;
    }

    .window-control-minimize {
        background: #ffbd2e;
    }

    .window-control-expand {
        background: #28c840;
    }

    .workspace-grid,
    .dashboard-main-grid,
    .stats-grid,
    .hero-layout,
    .auth-grid,
    .form-grid.two-columns {
        display: grid;
        gap: 20px;
    }

    .workspace-grid {
        grid-template-columns: minmax(270px, 290px) minmax(0, 1fr);
        align-items: start;
    }

    .dashboard-main-grid {
        grid-template-columns: minmax(0, 1.3fr) minmax(300px, 0.88fr);
        align-items: start;
    }

    .stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .stats-grid .span-2 {
        grid-column: span 2;
    }

    .hero-layout,
    .auth-grid {
        grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.92fr);
        align-items: start;
    }

    .auth-grid {
        width: 100%;
        grid-template-columns: minmax(0, 1.08fr) minmax(340px, 0.82fr);
    }

    .auth-grid-single {
        width: min(760px, 100%);
        margin-inline: auto;
        grid-template-columns: minmax(0, 1fr);
    }

    .sidebar-sticky,
    .sticky-card {
        position: sticky;
        top: 28px;
    }

    .section-kicker,
    .metric-label,
    .field-label,
    .transaction-month,
    .currency-code,
    .story-index,
    .ranking-index,
    .auth-kicker,
    .info-label,
    .type-badge {
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 0.66rem;
        font-weight: 650;
    }

    .section-kicker,
    .info-label,
    .auth-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
    }

    .section-kicker::before,
    .auth-kicker::before {
        content: "";
        width: 18px;
        height: 1px;
        background: currentColor;
        opacity: 0.5;
    }

    .section-title,
    .hero-title,
    .auth-title,
    .metric-value,
    .summary-value,
    .currency-balance,
    .amount-primary,
    .story-number,
    .ranking-share,
    .transaction-day {
        margin: 0;
        letter-spacing: -0.04em;
        font-weight: 700;
        line-height: 0.96;
    }

    .section-title {
        font-size: clamp(1.15rem, 1.8vw, 1.68rem);
    }

    .hero-title {
        max-width: 11ch;
        font-size: clamp(2.2rem, 4.4vw, 4rem);
    }

    .auth-title {
        max-width: 10ch;
        font-size: clamp(2.1rem, 4.2vw, 3.85rem);
    }

    .hero-copy,
    .auth-copy-block,
    .summary-card,
    .metric-card,
    .profile-card,
    .currency-card,
    .transaction-card,
    .story-row,
    .ranking-item,
    .auth-beat,
    .notice-card,
    .empty-state,
    .mini-card {
        position: relative;
        z-index: 1;
        border-radius: var(--radius-card);
        border: 1px solid rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.48);
        box-shadow: var(--shadow-card);
    }

    .profile-card,
    .summary-card,
    .metric-card,
    .currency-card,
    .story-row,
    .ranking-item,
    .auth-beat,
    .notice-card,
    .empty-state,
    .mini-card {
        padding: 14px 16px;
    }

    .hero-copy {
        padding: 18px;
        min-height: 0;
    }

    .overview-copy {
        display: grid;
        gap: 14px;
        align-content: start;
    }

    .overview-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .overview-mini-card {
        padding: 12px 14px;
    }

    .overview-mini-value {
        display: block;
        margin-top: 4px;
        font-size: 0.98rem;
        font-weight: 650;
        letter-spacing: -0.02em;
        line-height: 1.08;
    }

    .overview-side-grid {
        gap: 10px;
        align-content: start;
    }

    .hero-copy .section-copy,
    .auth-copy {
        margin-top: 10px;
        max-width: 54ch;
        font-size: 0.94rem;
    }

    .hero-actions,
    .auth-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .metric-grid {
        grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
    }

    .metric-grid-overview {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .metric-card {
        min-height: 108px;
        align-content: space-between;
    }

    .metric-card-overview {
        min-height: 94px;
        padding: 14px 16px;
        align-content: start;
        gap: 10px;
    }

    .metric-card-overview .metric-label {
        font-size: 0.72rem;
    }

    .metric-label {
        color: var(--text-secondary);
    }

    .metric-value {
        font-size: clamp(1.4rem, 2.4vw, 2.15rem);
        font-variant-numeric: tabular-nums;
    }

    .metric-value-money {
        display: block;
        margin-top: 2px;
        white-space: nowrap;
        font-size: clamp(1.04rem, 1.48vw, 1.46rem);
        line-height: 1.02;
        letter-spacing: -0.06em;
    }

    .metric-value-compact {
        display: block;
        white-space: nowrap;
        font-size: clamp(1.16rem, 1.4vw, 1.52rem);
        line-height: 1.02;
    }

    .metric-value.income,
    .tone-income {
        color: var(--success);
    }

    .metric-value.expense,
    .tone-expense {
        color: var(--danger);
    }

    .metric-value.balance,
    .tone-balance {
        color: var(--accent-strong);
    }

    .btn,
    .nav-link,
    .month-nav,
    .date-picker-nav,
    .date-picker-action,
    .dropdown-item,
    .close-btn {
        transition:
            transform var(--transition),
            background-color var(--transition),
            box-shadow var(--transition),
            border-color var(--transition),
            color var(--transition);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 14px;
        border: none;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.68);
        color: var(--text-primary);
        box-shadow:
            inset 0 0 0 1px rgba(255, 255, 255, 0.52),
            0 14px 28px rgba(12, 27, 58, 0.12);
        font-size: 0.82rem;
        font-weight: 620;
        letter-spacing: -0.01em;
    }

    .btn:hover,
    .nav-link:hover,
    .month-nav:hover,
    .date-picker-nav:hover,
    .date-picker-action:hover,
    .dropdown-item:hover,
    .close-btn:hover {
        transform: translateY(-2px);
    }

    .btn:focus-visible,
    .nav-link:focus-visible,
    .field-control:focus-visible,
    .month-nav:focus-visible,
    .date-picker-nav:focus-visible,
    .date-picker-action:focus-visible,
    .close-btn:focus-visible {
        outline: 3px solid rgba(10, 132, 255, 0.16);
        outline-offset: 3px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0a84ff, #55c8ff);
        color: #ffffff;
        box-shadow:
            inset 0 0 0 1px rgba(255, 255, 255, 0.14),
            0 18px 34px rgba(10, 132, 255, 0.34);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.76);
    }

    .btn-danger {
        background: rgba(255, 95, 87, 0.14);
        color: #c93c37;
    }

    .btn-small {
        min-height: 34px;
        padding: 0 12px;
        font-size: 0.76rem;
    }

    .nav-list {
        display: grid;
        gap: 8px;
    }

    .nav-link {
        justify-content: space-between;
        width: 100%;
        min-height: 46px;
        padding: 0 14px;
        border-radius: 16px;
        border: 1px solid transparent;
        background: rgba(255, 255, 255, 0.46);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.52);
        font-size: 0.84rem;
        font-weight: 610;
        color: var(--text-primary);
    }

    .nav-link.active {
        background: linear-gradient(135deg, rgba(10, 132, 255, 0.96), rgba(83, 185, 255, 0.84));
        color: #ffffff;
        box-shadow:
            inset 0 0 0 1px rgba(255, 255, 255, 0.16),
            0 16px 32px rgba(10, 132, 255, 0.24);
    }

    .nav-link-button {
        border: none;
        text-align: left;
    }

    .nav-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        min-height: 24px;
        padding: 0 8px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.4);
        font-size: 0.68rem;
        font-weight: 640;
    }

    .nav-link.active .nav-badge {
        background: rgba(255, 255, 255, 0.18);
    }

    .profile-card {
        display: grid;
        gap: 12px;
    }

    .stats-sidebar {
        align-content: start;
    }

    .stats-sidebar > * {
        width: 100%;
        min-width: 0;
    }

    .stats-sidebar .profile-card,
    .stats-sidebar .summary-card {
        justify-items: start;
        align-content: start;
    }

    .stats-sidebar .section-title,
    .stats-sidebar .section-copy,
    .stats-sidebar .section-kicker {
        text-align: left;
    }

    .stats-sidebar .nav-list,
    .stats-sidebar .segmented-links,
    .stats-sidebar .month-switcher {
        width: 100%;
    }

    .stats-sidebar .nav-link {
        min-width: 0;
    }

    .stats-sidebar .nav-badge {
        margin-left: 12px;
        flex-shrink: 0;
    }

    .stats-sidebar .segment-link {
        min-width: 0;
    }

    .stats-sidebar .month-switcher {
        display: grid;
        grid-template-columns: 34px minmax(0, 1fr) 34px;
        align-items: center;
        column-gap: 8px;
    }

    .stats-sidebar .month-label {
        min-width: 0;
    }

    .fooldal-sidebar-window {
        display: flex;
        flex-direction: column;
        min-height: clamp(700px, 74vh, 820px);
    }

    .fooldal-overview-window {
        display: flex;
        flex-direction: column;
        min-height: clamp(700px, 74vh, 820px);
    }

    .fooldal-sidebar-window .window-header {
        flex: 0 0 auto;
    }

    .fooldal-overview-window .window-header {
        flex: 0 0 auto;
    }

    .fooldal-sidebar-window .window-body {
        flex: 1 1 auto;
        display: grid;
    }

    .fooldal-overview-window .window-body {
        flex: 1 1 auto;
    }

    .fooldal-sidebar-window .control-stack {
        height: 100%;
        align-content: start;
    }

    .fooldal-sidebar-window .summary-card {
        margin-top: auto;
    }

    .profile-head {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .avatar {
        display: grid;
        place-items: center;
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: linear-gradient(135deg, #0a84ff, #72e3ff);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.24),
            0 18px 28px rgba(10, 132, 255, 0.24);
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.08em;
    }

    .summary-card strong,
    .auth-beat strong,
    .notice-card strong,
    .mini-card strong {
        display: block;
        margin-bottom: 6px;
        font-size: 0.92rem;
    }

    .summary-value,
    .currency-balance,
    .story-number {
        font-size: 1.22rem;
        font-variant-numeric: tabular-nums;
    }

    .detail-list {
        gap: 10px;
    }

    .detail-row,
    .currency-row,
    .confirm-meta-row {
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        color: var(--text-secondary);
        font-size: 0.84rem;
    }

    .detail-row strong,
    .currency-row strong,
    .confirm-meta-value {
        color: var(--text-primary);
        font-weight: 640;
    }

    .summary-card-compact {
        display: grid;
        gap: 8px;
        padding: 12px 14px;
    }

    .summary-card-compact > strong {
        margin-bottom: 0;
    }

    .summary-card-compact .detail-row {
        gap: 10px;
        font-size: 0.78rem;
        align-items: baseline;
    }

    .summary-card-compact .detail-row strong {
        margin-bottom: 0;
        white-space: nowrap;
        font-size: 0.92rem;
        line-height: 1.08;
    }

    .month-switcher {
        padding: 6px;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.46);
        background: rgba(255, 255, 255, 0.46);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.52);
    }

    .month-nav,
    .date-picker-nav,
    .close-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.82);
        color: var(--text-primary);
        box-shadow: var(--shadow-card);
    }

    .month-nav.disabled {
        opacity: 0.38;
        pointer-events: none;
    }

    .month-label {
        min-width: 148px;
        text-align: center;
        padding: 0 8px;
        font-size: 0.86rem;
        font-weight: 620;
        letter-spacing: -0.02em;
    }

    .flash-stack {
        gap: 12px;
    }

    .flash {
        padding: 12px 14px;
        border-radius: 16px;
        border: 1px solid transparent;
        box-shadow: var(--shadow-card);
    }

    .flash-success {
        background: var(--success-soft);
        border-color: rgba(31, 184, 92, 0.16);
        color: #177a3d;
    }

    .flash-danger {
        background: var(--danger-soft);
        border-color: rgba(255, 95, 87, 0.18);
        color: #c03832;
    }

    .flash-info {
        background: rgba(10, 132, 255, 0.12);
        border-color: rgba(10, 132, 255, 0.18);
        color: #0b5ab0;
    }

    .transaction-stream {
        gap: 12px;
    }

    .transaction-card {
        display: grid;
        grid-template-columns: 76px minmax(0, 1fr);
        gap: 14px;
        align-items: stretch;
        padding: 12px 14px 12px 12px;
        background: rgba(255, 255, 255, 0.54);
        overflow: hidden;
        transition: transform var(--transition), box-shadow var(--transition), background-color var(--transition);
    }

    .transaction-card:hover {
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.64);
    }

    .transaction-date {
        display: grid;
        justify-items: center;
        align-content: center;
        gap: 2px;
        padding: 10px 8px;
        border: 1px solid rgba(255, 255, 255, 0.46);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.48);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.58);
    }

    .transaction-day {
        font-size: 1.68rem;
    }

    .transaction-month {
        color: var(--text-secondary);
    }

    .transaction-content,
    .transaction-main {
        min-width: 0;
        display: grid;
        gap: 12px;
        padding-right: 4px;
    }

    .transaction-title-row,
    .transaction-heading {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(156px, 196px);
        align-items: start;
        gap: 14px;
    }

    .transaction-heading-copy {
        display: grid;
        gap: 8px;
        min-width: 0;
        align-content: start;
    }

    .transaction-heading-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .transaction-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 650;
        letter-spacing: -0.03em;
        line-height: 1.08;
    }

    .transaction-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .type-badge {
        min-height: 26px;
        padding: 0 10px;
        border-radius: 999px;
        border: 1px solid transparent;
        letter-spacing: 0.08em;
    }

    .type-income,
    .badge-income {
        color: #178649;
        background: rgba(31, 184, 92, 0.12);
        border-color: rgba(31, 184, 92, 0.14);
    }

    .type-expense,
    .badge-expense {
        color: #ca3f39;
        background: rgba(255, 95, 87, 0.12);
        border-color: rgba(255, 95, 87, 0.14);
    }

    .transaction-note {
        font-size: 0.86rem;
        word-break: break-word;
        max-width: none;
    }

    .transaction-amount {
        display: grid;
        gap: 4px;
        justify-items: end;
        text-align: right;
        align-content: start;
        min-width: 0;
        width: min(100%, 196px);
        justify-self: end;
        padding-left: 12px;
        border-left: 1px solid rgba(126, 152, 191, 0.14);
    }

    .amount-primary {
        font-size: clamp(0.98rem, 1.18vw, 1.08rem);
        font-variant-numeric: tabular-nums;
        line-height: 1.04;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .amount-secondary {
        color: var(--text-secondary);
        font-size: 0.76rem;
        font-variant-numeric: tabular-nums;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .amount-income {
        color: var(--success);
    }

    .amount-expense {
        color: var(--danger);
    }

    .transaction-footer {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        padding-top: 12px;
        padding-right: 2px;
        border-top: 1px solid rgba(126, 152, 191, 0.14);
    }

    .transaction-caption {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .action-set {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        flex-shrink: 0;
    }

    .currency-list,
    .summary-list,
    .story-list,
    .ranking-list {
        gap: 12px;
    }

    .summary-list-overview {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .currency-card {
        display: grid;
        gap: 12px;
    }

    .currency-header {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
    }

    .currency-code {
        color: var(--text-secondary);
    }

    .currency-balance {
        text-align: right;
    }

    .story-row {
        display: grid;
        gap: 12px;
    }

    .story-headline {
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        align-items: baseline;
    }

    .story-index,
    .ranking-index {
        color: var(--text-tertiary);
    }

    .story-meta {
        justify-content: space-between;
        gap: 16px;
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .story-number {
        font-size: 1rem;
    }

    .progress-bar {
        height: 8px;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(91, 119, 160, 0.14);
    }

    .progress-fill {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, rgba(10, 132, 255, 0.96), rgba(83, 185, 255, 0.82));
    }

    .progress-fill.expense {
        background: linear-gradient(90deg, rgba(255, 95, 87, 0.96), rgba(255, 150, 122, 0.84));
    }

    .progress-fill.income {
        background: linear-gradient(90deg, rgba(31, 184, 92, 0.96), rgba(102, 227, 156, 0.84));
    }

    .progress-fill.balance-negative {
        background: linear-gradient(90deg, rgba(255, 95, 87, 0.96), rgba(255, 189, 46, 0.84));
    }

    .ranking-item {
        gap: 12px;
    }

    .ranking-head {
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
    }

    .ranking-share {
        font-size: clamp(1.5rem, 3.2vw, 2.45rem);
    }

    .empty-state {
        justify-items: start;
        gap: 10px;
        border-style: dashed;
        border-color: rgba(126, 152, 191, 0.34);
        background: rgba(255, 255, 255, 0.38);
    }

    .empty-state.centered {
        justify-items: center;
        text-align: center;
    }

    .form-grid {
        gap: 14px;
    }

    .form-grid.two-columns {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .field-group {
        gap: 6px;
    }

    .field-group.span-2,
    .span-2 {
        grid-column: span 2;
    }

    .field-label {
        color: var(--text-secondary);
    }

    .field-help {
        color: var(--text-secondary);
        font-size: 0.78rem;
        line-height: 1.5;
    }

    .field-control {
        width: 100%;
        min-height: 46px;
        padding: 0 14px;
        border: 1px solid rgba(255, 255, 255, 0.42);
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.78);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.52);
        color: var(--text-primary);
        font-size: 0.88rem;
        transition:
            border-color var(--transition),
            box-shadow var(--transition),
            background-color var(--transition);
    }

    textarea.field-control {
        min-height: 108px;
        padding: 13px 14px;
        resize: vertical;
    }

    .field-control:focus {
        outline: none;
        border-color: rgba(10, 132, 255, 0.46);
        box-shadow: 0 0 0 4px rgba(10, 132, 255, 0.12);
        background: rgba(255, 255, 255, 0.92);
    }

    .field-inline-message {
        min-height: 18px;
        font-size: 0.76rem;
        color: var(--text-secondary);
    }

    .field-inline-message.error {
        color: #be3a35;
    }

    .field-inline-message.success {
        color: #178649;
    }

    .type-toggle,
    .segmented-links {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 5px;
        padding: 5px;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.42);
        background: rgba(255, 255, 255, 0.46);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.52);
    }

    .type-toggle-option,
    .segment-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 12px;
        border: none;
        border-radius: 12px;
        background: transparent;
        color: var(--text-secondary);
        font-size: 0.82rem;
        font-weight: 620;
    }

    .type-toggle-option.active,
    .segment-link.active {
        background: linear-gradient(135deg, rgba(10, 132, 255, 0.96), rgba(83, 185, 255, 0.82));
        color: #ffffff;
        box-shadow: 0 14px 22px rgba(10, 132, 255, 0.22);
    }

    .type-toggle-input {
        display: none;
    }

    .kategoria-input-wrapper {
        position: relative;
        display: grid;
        gap: 6px;
    }

    .kategoria-list {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        right: 0;
        display: none;
        max-height: 220px;
        overflow-y: auto;
        padding: 6px;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.48);
        background: rgba(244, 249, 255, 0.92);
        box-shadow: var(--shadow-card);
        backdrop-filter: var(--blur);
        -webkit-backdrop-filter: var(--blur);
        z-index: 16;
    }

    .kategoria-list.show {
        display: grid;
        gap: 6px;
    }

    .kategoria-item,
    .dropdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 14px;
        background: transparent;
        border: none;
        color: var(--text-primary);
        text-align: left;
        font-size: 0.84rem;
    }

    .kategoria-item:hover,
    .dropdown-item:hover {
        background: rgba(10, 132, 255, 0.08);
        color: var(--accent-strong);
    }

    .kategoria-item-label {
        min-width: 0;
        flex: 1;
    }

    .kategoria-item-delete {
        border: none;
        padding: 0;
        background: transparent;
        color: #c03832;
        font-size: 0.72rem;
        font-weight: 650;
        cursor: pointer;
    }

    .date-picker {
        position: relative;
    }

    .date-picker-trigger {
        justify-content: space-between;
        width: 100%;
        text-align: left;
    }

    .date-picker-trigger-icon {
        width: 12px;
        height: 12px;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
        transform: rotate(45deg);
        color: var(--text-tertiary);
        flex-shrink: 0;
        margin-top: -4px;
    }

    .date-picker-popover {
        position: absolute;
        top: calc(100% + 12px);
        left: 0;
        width: 100%;
        min-width: 304px;
        display: none;
        padding: 14px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.48);
        background: rgba(244, 249, 255, 0.92);
        box-shadow: var(--shadow-window);
        backdrop-filter: var(--blur);
        -webkit-backdrop-filter: var(--blur);
        z-index: 18;
    }

    .date-picker.open .date-picker-popover {
        display: block;
    }

    .date-picker.open-upward .date-picker-popover {
        top: auto;
        bottom: calc(100% + 12px);
    }

    .date-picker-month {
        font-size: 0.88rem;
        font-weight: 640;
        letter-spacing: -0.02em;
    }

    .date-picker-weekdays,
    .date-picker-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 6px;
    }

    .date-picker-weekdays {
        margin-top: 16px;
    }

    .date-picker-weekday {
        display: inline-flex;
        justify-content: center;
        color: var(--text-tertiary);
        font-size: 0.68rem;
        font-weight: 650;
    }

    .date-picker-grid {
        margin-top: 10px;
    }

    .date-picker-day {
        min-height: 34px;
        border: none;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.72);
        font-size: 0.8rem;
        font-weight: 620;
        color: var(--text-primary);
    }

    .date-picker-day.is-outside {
        opacity: 0.45;
    }

    .date-picker-day.is-today {
        box-shadow: inset 0 0 0 1px rgba(10, 132, 255, 0.46);
    }

    .date-picker-day.is-selected {
        background: linear-gradient(135deg, #0a84ff, #55c8ff);
        color: #ffffff;
        box-shadow: 0 12px 20px rgba(10, 132, 255, 0.22);
    }

    .date-picker-actions {
        justify-content: flex-end;
        margin-top: 12px;
    }

    .date-picker-action {
        min-height: 34px;
        padding: 0 12px;
        border: none;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.82);
        color: var(--text-primary);
        box-shadow: var(--shadow-card);
        font-size: 0.76rem;
        font-weight: 620;
    }

    .date-picker-action-primary {
        background: linear-gradient(135deg, #0a84ff, #55c8ff);
        color: #ffffff;
    }

    .modal {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(10, 19, 40, 0.28);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        position: relative;
        width: min(760px, 100%);
        max-height: calc(100vh - 48px);
        overflow-y: auto;
        border-radius: var(--radius-window);
        border: 1px solid rgba(255, 255, 255, 0.42);
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.78), rgba(240, 246, 255, 0.5)),
            linear-gradient(135deg, rgba(94, 191, 255, 0.08), rgba(255, 95, 123, 0.08));
        box-shadow: var(--shadow-window);
        backdrop-filter: var(--blur);
        -webkit-backdrop-filter: var(--blur);
    }

    .close-btn {
        margin-left: auto;
    }

    .confirm-modal-content {
        width: min(520px, 100%);
    }

    .confirm-meta {
        display: grid;
        gap: 10px;
        margin: 16px 0 0;
    }

    .confirm-actions {
        justify-content: flex-end;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .notice-card {
        gap: 12px;
    }

    .inline-form {
        grid-template-columns: minmax(0, 1fr) auto;
        margin-top: 14px;
    }

    .auth-panel-copy {
        display: grid;
        gap: 6px;
        margin-bottom: 6px;
    }

    .auth-footer {
        color: var(--text-secondary);
        font-size: 0.84rem;
    }

    .auth-footer a {
        color: var(--accent-strong);
        font-weight: 640;
    }

    .auth-beat {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 12px;
    }

    .auth-beat-index {
        color: rgba(224, 233, 248, 0.58);
        font-size: 0.78rem;
        font-weight: 650;
        letter-spacing: 0.14em;
    }

    .reveal {
        animation: fade-up 420ms ease both;
    }

    @keyframes fade-up {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1180px) {
        .workspace-grid,
        .dashboard-main-grid,
        .hero-layout,
        .auth-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid .span-2 {
            grid-column: span 1;
        }

        .sidebar-sticky,
        .sticky-card {
            position: static;
        }

        .fooldal-sidebar-window {
            min-height: 0;
        }

        .fooldal-overview-window {
            min-height: 0;
        }

        .transaction-heading {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .transaction-amount {
            width: 100%;
            justify-self: start;
            justify-items: start;
            text-align: left;
            padding-left: 0;
            border-left: none;
        }
    }

    @media (max-width: 900px) {
        .page-shell,
        .auth-shell,
        .menu-bar-inner {
            width: min(100% - 20px, 100%);
        }

        .menu-bar-inner {
            padding: 12px 14px;
            align-items: flex-start;
            flex-direction: column;
        }

        .menu-status,
        .window-actions {
            justify-content: flex-start;
        }

        .metric-grid,
        .form-grid.two-columns,
        .overview-meta-grid,
        .metric-grid-overview,
        .summary-list-overview {
            grid-template-columns: 1fr;
        }

        .field-group.span-2,
        .span-2 {
            grid-column: span 1;
        }

        .transaction-card {
            grid-template-columns: 1fr;
        }

        .transaction-date {
            justify-items: start;
            text-align: left;
        }

        .transaction-heading,
        .transaction-footer,
        .transaction-amount,
        .action-set {
            justify-items: start;
            justify-content: flex-start;
            text-align: left;
        }

        .transaction-heading,
        .transaction-footer {
            grid-template-columns: 1fr;
            align-items: flex-start;
        }

        .month-switcher,
        .inline-form {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 640px) {
        .page-shell,
        .auth-shell {
            width: min(100% - 16px, 100%);
        }

        .window-header,
        .window-toolbar,
        .window-body {
            padding: 18px;
        }

        .btn,
        .btn-small {
            width: 100%;
        }

        .hero-actions,
        .auth-actions {
            display: grid;
        }

        .month-label {
            min-width: 0;
            flex: 1;
        }

        .date-picker-popover {
            min-width: 0;
        }
    }
</style>
