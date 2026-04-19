<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

<style>
    :root {
        /* Modern letisztult színpaletta - világos zöld elemekkel */
        --primary: #059669;        /* Zöld - fő szín */
        --primary-light: #10b981;  /* Világos zöld */
        --primary-dark: #047857;   /* Sötét zöld */
        --primary-soft: rgba(5, 150, 105, 0.1);
        
        --secondary: #1e293b;      /* Sötétszürke */
        --secondary-light: #334155;
        
        --surface: #ffffff;        /* Fehér felület */
        --surface-soft: #f8fafc;   /* Világosszürke háttér */
        --surface-alt: #f1f5f9;
        
        --text: #1e293b;           /* Fő szöveg */
        --text-soft: #64748b;      /* Másodlagos szöveg */
        --text-light: #94a3b8;
        
        --border: #e2e8f0;
        --border-light: #f1f5f9;
        
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        
        --radius-sm: 6px;
        --radius: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 24px;
        
        --transition: all 0.2s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: var(--text);
        background: var(--surface-soft);
        line-height: 1.6;
        min-height: 100vh;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    button, input, select, textarea {
        font: inherit;
    }

    button {
        cursor: pointer;
    }

    img {
        display: block;
        max-width: 100%;
    }

    /* Layout */
    .page-shell {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
    }

    /* Topbar */
    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
    }

    .brand-text {
        font-size: 20px;
        font-weight: 600;
        color: var(--secondary);
    }

    .topbar-nav {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .nav-link {
        padding: 8px 16px;
        border-radius: var(--radius);
        color: var(--text-soft);
        font-weight: 500;
        transition: var(--transition);
    }

    .nav-link:hover {
        background: var(--surface-soft);
        color: var(--primary);
    }

    .nav-link.active {
        background: var(--primary-soft);
        color: var(--primary);
    }

    /* Gombok */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: var(--radius);
        font-weight: 500;
        font-size: 14px;
        border: none;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--surface);
        color: var(--text);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--surface-soft);
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    /* Kártyák */
    .card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        border: 1px solid var(--border-light);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-light);
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--secondary);
    }

    /* Grid layout */
    .main-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .main-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Táblázat */
    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 14px 16px;
        text-align: left;
    }

    th {
        font-weight: 600;
        color: var(--text-soft);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--surface-soft);
        border-bottom: 1px solid var(--border);
    }

    td {
        border-bottom: 1px solid var(--border-light);
    }

    tr:hover td {
        background: var(--surface-soft);
    }

    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .badge-info {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    /* Statisztika kártyák */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-md);
        padding: 20px;
        border-left: 4px solid var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-soft);
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--secondary);
    }

    .stat-value.positive {
        color: var(--success);
    }

    .stat-value.negative {
        color: var(--danger);
    }

    /* Form */
    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        font-size: 14px;
        transition: var(--transition);
        background: var(--surface);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }

    /* Összeg formázás */
    .amount {
        font-weight: 600;
        font-variant-numeric: tabular-nums;
    }

    .amount-positive {
        color: var(--success);
    }

    .amount-negative {
        color: var(--danger);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-soft);
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 16px;
    }

    /* Flash messages */
    .flash-message {
        padding: 14px 20px;
        border-radius: var(--radius);
        margin-bottom: 16px;
        font-weight: 500;
    }

    .flash-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .flash-error {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .flash-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    /* Footer */
    .footer {
        text-align: center;
        padding: 24px;
        color: var(--text-light);
        font-size: 14px;
    }
</style>