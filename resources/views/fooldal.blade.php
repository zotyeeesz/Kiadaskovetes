<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Főoldal - SpendWise</title>
    <style>
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        *, *::before, *::after {
            box-sizing: inherit;
        }
        .header {
            background-color: #667eea;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 !important;
            position: relative;
            top: 0;
        }
        .header {
            background-color: #667eea;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .logout-btn {
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: #b71c1c;
        }
        .container {
            padding: 30px;
            max-width: 1520px;
            margin: 0 auto;
        }
        .main-layout {
            display: flex;
            gap: 25px;
        }
        .left-column {
            flex: 3;
        }
        .right-column {
            flex: 2;
        }
        h2 {
            color: #333;
        }
        .add-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .add-btn:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #667eea;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        .amount-cell strong {
            display: block;
        }
        .amount-expense {
            color: #b00020;
        }
        .amount-income {
            color: #1b8f3a;
        }
        .converted-amount {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: #777;
        }
        .converted-amount.amount-expense {
            color: #b00020;
        }
        .converted-amount.amount-income {
            color: #1b8f3a;
        }
        .type-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: bold;
            border-radius: 12px;
            padding: 2px 8px;
            margin-bottom: 4px;
        }
        .type-expense {
            background: #fde8ec;
            color: #a3001e;
        }
        .type-income {
            background: #e8f5e9;
            color: #1b8f3a;
        }
        .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
            transition: transform 0.2s;
        }
        .delete-btn:hover {
            transform: scale(1.2);
        }
        .no-data {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            color: #666;
        }
        .stats-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 0;
            position: sticky;
            top: 30px;
        }
        .stats-summary {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        .stat-card-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-card-value {
            font-size: 18px;
            font-weight: bold;
            color: #222;
        }
        .stat-link {
            text-align: center;
            margin-top: 15px;
        }
        .stat-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
        }
        .stat-link a:hover {
            text-decoration: underline;
        }
        .stats-table {
            width: 100%;
            font-size: 13px;
            margin-top: 10px;
        }
        .stats-table th {
            background: #f0f4ff;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        .stats-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 500px;
        }
        .close-btn {
            float: right;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            line-height: 20px;
        }
        .close-btn:hover {
            color: #000;
        }
        .modal h2 {
            margin-top: 0;
            color: #333;
        }
        .modal form input, .modal form textarea, .modal form select {
            display: block;
            width: 100%;
            margin: 15px 0;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .modal form input:focus, .modal form textarea:focus, .modal form select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        .modal form textarea {
            resize: vertical;
            min-height: 80px;
        }
        .modal form button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        .modal form button[type="submit"]:hover {
            background-color: #5568d3;
        }
        .kategoria-input-wrapper {
            position: relative;
            margin: 15px 0;
        }
        .kategoria-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .kategoria-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        .field-help {
            margin-top: 6px;
            font-size: 12px;
            color: #777;
        }
        .field-inline-message {
            margin-top: 0;
            font-size: 12px;
            color: #667085;
            min-height: 0;
            height: 0;
            overflow: hidden;
        }
        .field-inline-message.error {
            color: #b42318;
            margin-top: 8px;
            min-height: 18px;
            height: auto;
        }
        .field-inline-message.success {
            color: #027a48;
            margin-top: 8px;
            min-height: 18px;
            height: auto;
        }
        .kategoria-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .kategoria-item-label {
            flex: 1;
            min-width: 0;
        }
        .kategoria-item-delete {
            width: auto !important;
            min-width: 0 !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            border: 0;
            background: transparent !important;
            box-shadow: none !important;
            color: #b42318 !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer;
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            line-height: 1.2;
            flex-shrink: 0;
        }
        .kategoria-item-delete:hover {
            background: transparent !important;
            text-decoration: underline;
        }
        .kategoria-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .kategoria-list.show {
            display: block;
        }
        .kategoria-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .kategoria-item:hover {
            background-color: #f0f0f0;
        }
        .kategoria-item:last-child {
            border-bottom: none;
        }
        /* Reszponzív design */
        @media (max-width: 1024px) {
            .container {
                padding: 20px;
                max-width: 100%;
            }
            .main-layout {
                gap: 15px;
            }
            table th, table td {
                padding: 8px;
                font-size: 13px;
            }
        }
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .header h1 {
                font-size: 20px;
            }
            .header h2 {
                font-size: 14px;
                margin: 0;
            }
            .logout-btn {
                align-self: flex-end;
            }
            .container {
                padding: 15px;
            }
            .main-layout {
                flex-direction: column;
                gap: 20px;
            }
            .left-column, .right-column {
                flex: 1;
            }
            .stats-box {
                position: static;
                margin-top: 0;
            }
            .stats-summary {
                flex-direction: row;
                gap: 10px;
            }
            .stat-card {
                padding: 10px;
                font-size: 12px;
            }
            .stat-card-value {
                font-size: 14px;
            }
            table th, table td {
                padding: 6px;
                font-size: 12px;
            }
            .stats-table th, .stats-table td {
                padding: 5px;
                font-size: 11px;
            }
            .add-btn {
                padding: 8px 12px;
                font-size: 13px;
                margin-right: 5px;
            }
            .modal-content {
                width: 95%;
                padding: 20px;
            }
            .modal form input, .modal form textarea {
                font-size: 13px;
            }
        }
        @media (max-width: 480px) {
            .header h1 {
                font-size: 16px;
            }
            .header h2 {
                font-size: 12px;
            }
            .container {
                padding: 10px;
            }
            .add-btn {
                padding: 6px 10px;
                font-size: 12px;
                margin-bottom: 10px;
            }
            .stats-summary {
                flex-direction: column;
                gap: 8px;
            }
            .stat-card {
                padding: 8px;
            }
            .stat-card-label {
                font-size: 11px;
            }
            .stat-card-value {
                font-size: 13px;
            }
            table {
                font-size: 11px;
            }
            table th, table td {
                padding: 4px;
            }
            .stats-table {
                font-size: 10px;
            }
            .modal-content {
                padding: 15px;
            }
            .close-btn {
                font-size: 24px;
            }
        }
    </style>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap");
        :root {
            /* Modern letisztult színpaletta - világos zöld elemekkel */
            --ink: #1e293b;
            --muted: #64748b;
            --surface: rgba(255, 255, 255, 0.95);
            --line: rgba(30, 41, 59, 0.1);
            --accent: #059669;
            --accent-soft: #10b981;
            --accent-light: rgba(5, 150, 105, 0.1);
            --income: #10b981;
            --expense: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Instrument Sans", "Segoe UI", sans-serif !important;
            color: var(--ink);
            background: #f8fafc !important;
            min-height: 100vh;
        }
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #ffffff !important;
            border-bottom: 1px solid var(--line);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 12px 24px !important;
            margin: 0 !important;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .header-logo {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 12px;
            color: #111827;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }
        .header-logo svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }
        .header-title {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .header-title h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.5px;
        }
        .header-user {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .logout-btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 10px !important;
            background: #ffffff !important;
            color: var(--ink) !important;
            box-shadow: none;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s ease;
        }
        .logout-btn:hover {
            transform: translateY(-1px);
            background: var(--accent-light) !important;
            border-color: var(--accent);
            color: var(--accent) !important;
        }
        .container {
            padding: 20px 24px 40px !important;
            max-width: 1560px;
        }
        .page-intro {
            margin: 0 0 14px;
            color: var(--ink);
            font-size: 1.38rem;
            line-height: 1.25;
            font-weight: 700;
            letter-spacing: -0.01em;
        }
        .main-layout {
            display: grid !important;
            grid-template-columns: minmax(0, 1.7fr) minmax(360px, 1fr);
            gap: 30px !important;
        }
        .left-column,
        .right-column {
            min-width: 0;
        }
        .right-column {
            align-self: start;
        }
        .page-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin: 0 0 30px;
            padding: 0;
        }
        .add-btn {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 18px;
            border: 1px solid var(--line);
            border-radius: 10px !important;
            background: #ffffff !important;
            box-shadow: none;
            font-size: 0.94rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            color: var(--ink) !important;
            margin-bottom: 0;
            transition: all .2s ease;
        }
        .add-btn:hover {
            transform: translateY(-1px);
            background: var(--accent-light) !important;
            border-color: var(--accent);
            color: var(--accent) !important;
        }
        .add-btn.primary-btn {
            background: var(--accent) !important;
            color: #ffffff !important;
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }
        .add-btn.primary-btn:hover {
            background: #047857 !important;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
        }
        .add-btn.secondary-btn {
            background: #ffffff !important;
            color: var(--ink) !important;
        }
        .transactions-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
        }
        .transactions-title-row h2 {
            margin: 0;
            font-size: 1.18rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--ink);
        }
        .section-title {
            margin: 0;
            font-size: 1.02rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--ink);
        }
        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(17, 38, 65, 0.1);
        }
        .section-heading::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(17, 38, 65, 0.18), rgba(17, 38, 65, 0));
        }
        .month-inline-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: #ffffff;
        }
        .month-inline-current {
            min-width: 146px;
            text-align: center;
            font-weight: 600;
            color: var(--ink);
            font-size: 14px;
        }
        .month-nav-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--line);
            text-decoration: none;
            color: var(--ink);
            background: #fff;
            font-weight: 600;
            transition: all .2s ease;
        }
        .month-nav-btn:hover:not(.disabled) {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
        }
        .month-nav-btn.disabled {
            opacity: 0.35;
            pointer-events: none;
        }
        table {
            background: #ffffff !important;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        table th, table td { border-bottom: 1px solid var(--line) !important; }
        table th {
            background: #f8fafc !important;
            color: var(--muted) !important;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        table tr:nth-child(even) td { background: #fafbfc; }
        table tr:hover td { background: var(--accent-light) !important; }
        .amount-cell strong, .stat-card-value { font-family: "JetBrains Mono", monospace; font-variant-numeric: tabular-nums; }
        .amount-expense { color: var(--expense) !important; }
        .amount-income { color: var(--income) !important; }
        .type-expense { background: rgba(191, 31, 63, 0.12) !important; color: #9e1530 !important; }
        .type-income { background: rgba(8, 148, 81, 0.13) !important; color: #0b7b46 !important; }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .action-btn {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border: 1px solid var(--line) !important;
            border-radius: 8px;
            background: #f8fafc !important;
            color: var(--ink) !important;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .02em;
            white-space: nowrap;
            transition: all .2s ease;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            background: var(--accent-light) !important;
            border-color: var(--accent) !important;
            color: var(--accent) !important;
        }
        .action-btn-danger {
            color: var(--expense) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
            background: rgba(239, 68, 68, 0.05) !important;
        }
        .action-btn-danger:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            border-color: var(--expense) !important;
        }
        .no-data {
            border-radius: 12px !important;
            border: 1px dashed var(--line);
            background: #f8fafc !important;
            color: var(--muted) !important;
        }
        .stats-box {
            background: #ffffff !important;
            border: 1px solid var(--line);
            border-radius: 16px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
            top: 96px !important;
            padding: 24px !important;
        }
        .stats-summary {
            gap: 14px;
            margin-bottom: 22px;
        }
        .stat-card {
            background: #f8fafc !important;
            border: 1px solid var(--line);
            border-radius: 12px !important;
            padding: 18px !important;
            border-left: 4px solid var(--accent) !important;
        }
        .stat-card-label {
            margin-bottom: 8px;
            line-height: 1.45;
            color: var(--muted);
        }
        .stat-card-value {
            font-size: 1.2rem;
            line-height: 1.3;
            color: var(--ink);
        }
        table th, table td {
            padding: 14px 12px;
        }
        .stats-table {
            margin-top: 14px;
        }
        .stats-table th, .stats-table td {
            padding: 10px 10px;
        }
        .stats-table th {
            background: rgba(17, 38, 65, 0.05) !important;
            color: rgba(17, 38, 65, 0.74) !important;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-size: 10px;
            position: static;
        }
        .stat-link a {
            color: var(--accent) !important;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-light);
            text-decoration: none !important;
        }
        .modal {
            background: rgba(30, 41, 59, 0.5) !important;
            backdrop-filter: blur(4px);
        }
        .modal-content {
            border-radius: 16px !important;
            border: 1px solid var(--line);
            background: #ffffff !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
        }
        .confirm-modal-content {
            width: min(420px, calc(100% - 32px));
            padding: 24px;
        }
        .modal .section-heading {
            margin-bottom: 14px;
            padding-bottom: 0;
            border-bottom: none;
        }
        .modal .section-heading::after {
            display: none;
        }
        .confirm-meta {
            display: grid;
            gap: 8px;
            margin: 0 0 18px;
            padding: 14px;
            border: 1px solid rgba(17, 38, 65, 0.1);
            border-radius: 14px;
            background: rgba(255,255,255,0.74);
        }
        .confirm-meta-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
        }
        .confirm-meta-label {
            color: rgba(17, 38, 65, 0.6);
            font-weight: 700;
        }
        .confirm-meta-value {
            color: rgba(17, 38, 65, 0.92);
            font-weight: 700;
            text-align: right;
        }
        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .confirm-btn {
            min-width: 112px;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border-radius: 12px;
            border: 1px solid rgba(17, 38, 65, 0.14);
            font-size: 13px;
            font-weight: 700;
            transition: transform .18s ease, background-color .18s ease, border-color .18s ease, color .18s ease;
        }
        .confirm-btn:hover {
            transform: translateY(-1px);
        }
        .confirm-btn-secondary {
            background: rgba(255,255,255,0.94);
            color: rgba(17, 38, 65, 0.86);
        }
        .confirm-btn-secondary:hover {
            background: rgba(17, 38, 65, 0.05);
        }
        .confirm-btn-danger {
            background: rgba(166, 69, 52, 0.1);
            border-color: rgba(166, 69, 52, 0.22);
            color: #9e1530;
        }
        .confirm-btn-danger:hover {
            background: rgba(166, 69, 52, 0.16);
        }
        .close-btn:hover { color: var(--accent) !important; transform: rotate(90deg); }
        .modal form input, .modal form textarea, .modal form select, .kategoria-input {
            border-radius: 10px !important;
            border: 1px solid var(--line) !important;
            background: #ffffff !important;
            font-family: "Instrument Sans", "Segoe UI", sans-serif !important;
        }
        .modal form input:focus, .modal form textarea:focus, .modal form select:focus, .kategoria-input:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--accent-light) !important;
        }
        .type-toggle {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin: 15px 0;
            padding: 6px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #f8fafc;
        }
        .type-toggle-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            width: 1px;
            height: 1px;
        }
        .type-toggle-option {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 14px;
            border: 1px solid transparent !important;
            border-radius: 8px;
            background: transparent !important;
            color: var(--muted) !important;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .02em;
            box-shadow: none !important;
            transition: all .2s ease;
        }
        .type-toggle-option:hover {
            background: #ffffff !important;
            color: var(--ink) !important;
        }
        .type-toggle-option.active {
            background: #ffffff !important;
            border-color: var(--line) !important;
            color: var(--ink) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }
        .type-toggle-option[data-value="koltseg"].active {
            color: var(--expense) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
            background: rgba(239, 68, 68, 0.05) !important;
        }
        .type-toggle-option[data-value="bevetel"].active {
            color: var(--income) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
            background: rgba(16, 185, 129, 0.05) !important;
        }
        .date-field {
            display: grid;
            gap: 6px;
            margin: 6px 0 2px;
            position: relative;
        }
        .date-picker-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            width: 1px;
            height: 1px;
        }
        .date-picker-trigger {
            width: 100%;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 0 14px;
            border: 1px solid var(--line) !important;
            border-radius: 10px !important;
            background: #ffffff !important;
            color: var(--ink);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .01em;
            text-align: left;
            transition: all .2s ease;
        }
        .date-picker.open .date-picker-trigger,
        .date-picker-trigger:hover,
        .date-picker-trigger:focus-visible {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--accent-light) !important;
            outline: none;
        }
        .date-picker-trigger-value {
            color: rgba(17, 38, 65, 0.92);
            font-weight: 600;
            letter-spacing: .01em;
        }
        .date-picker-trigger-icon {
            position: relative;
            flex-shrink: 0;
            width: 18px;
            height: 16px;
            border: 1.5px solid rgba(17, 38, 65, 0.45);
            border-top-width: 5px;
            border-radius: 4px;
            background: rgba(255,255,255,0.84);
        }
        .date-picker-trigger-icon::before {
            content: "";
            position: absolute;
            left: 5px;
            top: 4px;
            width: 6px;
            height: 1.5px;
            background: rgba(17, 38, 65, 0.3);
            box-shadow: 0 4px 0 rgba(17, 38, 65, 0.18);
        }
        .date-picker-popover {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            width: min(286px, 100%);
            padding: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 35;
            max-height: min(296px, calc(100vh - 32px));
            overflow-y: auto;
            overscroll-behavior: contain;
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
        .date-picker-header {
            margin-bottom: 10px;
        }
        .date-picker-month {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            text-transform: capitalize;
        }
        .date-picker-nav,
        .date-picker-action {
            border: 1px solid var(--line) !important;
            background: #fff !important;
            color: var(--ink) !important;
            box-shadow: none !important;
        }
        .date-picker-nav {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
        }
        .date-picker-weekdays,
        .date-picker-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 3px;
        }
        .date-picker-weekdays {
            margin-bottom: 6px;
        }
        .date-picker-weekday {
            text-align: center;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #7a8899;
            padding: 3px 0;
        }
        .date-picker-day {
            min-height: 30px;
            border: 1px solid transparent !important;
            border-radius: 6px;
            background: transparent !important;
            color: var(--ink) !important;
            font-size: 12px;
            font-weight: 500;
            box-shadow: none !important;
        }
        .date-picker-day:hover {
            background: var(--accent-light) !important;
            color: var(--accent) !important;
        }
        .date-picker-day.is-outside {
            color: #cbd5e1 !important;
        }
        .date-picker-day.is-today {
            border-color: var(--accent) !important;
            color: var(--accent) !important;
        }
        .date-picker-day.is-selected {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #ffffff !important;
        }
        .date-picker-actions {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--line);
        }
        .date-picker-action {
            min-height: 30px;
            padding: 0 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .date-picker-action-primary {
            background: var(--accent-light) !important;
            border-color: var(--accent) !important;
            color: var(--accent) !important;
        }
        .date-picker-nav:hover,
        .date-picker-action:hover {
            background: var(--accent-light) !important;
        }
        .date-picker-action-primary:hover {
            background: var(--accent) !important;
            color: #ffffff !important;
        }
        @media (max-width: 480px) {
            .date-picker-popover {
                width: 100%;
            }
        }
        .modal form button[type="submit"] {
            border-radius: 10px !important;
            background: var(--accent) !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            font-weight: 600;
        }
        .modal form button[type="submit"]:hover {
            background: #047857 !important;
        }
        .kategoria-list {
            border: 1px solid var(--line) !important;
            border-top: none !important;
            border-radius: 0 0 10px 10px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }
        .kategoria-item:hover { background: var(--accent-light) !important; }
        .kategoria-item-delete,
        .kategoria-item-delete:hover {
            width: auto !important;
            min-width: 0 !important;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            color: #b42318 !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        @media (max-width: 1024px) {
            .main-layout { grid-template-columns: 1fr !important; }
            .stats-box { position: static !important; }
            .container { padding: 24px !important; }
        }
        @media (max-width: 768px) {
            .container { padding: 12px !important; }
            .page-intro {
                margin-bottom: 12px;
                font-size: 1.12rem;
            }
            .page-actions {
                gap: 10px;
                margin-bottom: 24px;
            }
            .add-btn {
                width: 100%;
            }
            .transactions-title-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .month-inline-controls {
                width: 100%;
                justify-content: space-between;
            }
            table { display: block; overflow-x: auto; white-space: nowrap; }
            .header {
                padding: 12px 14px !important;
                flex-direction: column;
                gap: 12px;
            }
            .header-brand {
                width: 100%;
                justify-content: center;
            }
            .header-user {
                width: 100%;
                justify-content: space-between;
            }
            .header-title h1 {
                font-size: 1.35rem;
            }
        }
        @media (max-width: 480px) {
            .header-logo {
                width: 40px;
                height: 40px;
                border-radius: 10px;
            }
            .header-logo svg {
                width: 22px;
                height: 22px;
            }
            .header-title h1 {
                font-size: 1.2rem;
            }
            .logout-btn {
                padding: 0 12px;
                font-size: 0.85rem;
            }
            .logout-btn svg {
                width: 16px;
                height: 16px;
            }
        }
    </style>
    <script>
        const calendarState = {
            visibleMonth: null,
        };
        const deleteState = {
            id: null,
        };

        function parseDateString(value) {
            if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                return null;
            }

            const [year, month, day] = value.split('-').map(Number);
            return new Date(year, month - 1, day);
        }

        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function formatDateForDisplay(value) {
            const date = parseDateString(value);
            if (!date) {
                return 'Válassz dátumot';
            }

            return new Intl.DateTimeFormat('hu-HU', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            }).format(date);
        }

        function getDateInput() {
            return document.getElementById('rogzites_input');
        }

        @php
            $koltsegKategoriakJs = $koltsegKategoriak->map(function ($kat) {
                return [
                    'id' => $kat->id,
                    'nev' => $kat->nev,
                    'owned' => (bool) $kat->felhasznaloid,
                ];
            })->values()->all();

            $bevetelKategoriakJs = $bevetelKategoriak->map(function ($kat) {
                return [
                    'id' => $kat->id,
                    'nev' => $kat->nev,
                    'owned' => (bool) $kat->felhasznaloid,
                ];
            })->values()->all();
        @endphp
        const categoryOptions = {
            koltseg: @json($koltsegKategoriakJs),
            bevetel: @json($bevetelKategoriakJs),
        };

        let categorySavePromise = null;

        function getActiveKategoriak() {
            const tipusInput = document.getElementById('tipus_input');
            const tipus = tipusInput && tipusInput.value === 'bevetel' ? 'bevetel' : 'koltseg';

            return categoryOptions[tipus] || [];
        }

        function getActiveTipus() {
            const tipusInput = document.getElementById('tipus_input');
            return tipusInput && tipusInput.value === 'bevetel' ? 'bevetel' : 'koltseg';
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function setCategoryMessage(message = '', type = '') {
            const messageBox = document.getElementById('kategoria_message');

            if (!messageBox) {
                return;
            }

            messageBox.textContent = message;
            messageBox.className = `field-inline-message${type ? ` ${type}` : ''}`;
        }

        function renderKategoriak(filterText = '', forceShow = false) {
            const list = document.getElementById('kategoria_list');

            if (!list) {
                return;
            }

            const normalizedFilter = filterText.toLowerCase();
            const matches = getActiveKategoriak().filter((item) => item.nev.toLowerCase().includes(normalizedFilter));

            list.innerHTML = matches
                .map((item) => {
                    const escapedName = String(item.nev).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                    const deleteButton = item.owned
                        ? `<button type="button" class="kategoria-item-delete" onclick="deleteCustomCategory(event, ${item.id}, '${escapedName}')">Törlés</button>`
                        : '';

                    return `<div class="kategoria-item" onclick="selectKategoria('${escapedName}')"><span class="kategoria-item-label">${escapeHtml(item.nev)}</span>${deleteButton}</div>`;
                })
                .join('');

            list.classList.toggle('show', matches.length > 0 && (forceShow || normalizedFilter.length > 0));
        }

        function setTipus(value) {
            ensureCategorySaved({ showMessage: false });

            const input = document.getElementById('tipus_input');
            const options = document.querySelectorAll('.type-toggle-option');
            const normalizedValue = value === 'bevetel' ? 'bevetel' : 'koltseg';

            if (input) {
                input.value = normalizedValue;
            }

            options.forEach((option) => {
                const isActive = option.dataset.value === normalizedValue;
                option.classList.toggle('active', isActive);
                option.setAttribute('aria-pressed', String(isActive));
            });

            const categoryInput = document.getElementById('kategoria_input');
            setCategoryMessage('');
            renderKategoriak(categoryInput ? categoryInput.value : '');
        }

        function categoryExists(name, tipus = getActiveTipus()) {
            const normalizedName = String(name || '').trim().toLowerCase();

            return (categoryOptions[tipus] || []).some((item) => item.nev.trim().toLowerCase() === normalizedName);
        }

        async function ensureCategorySaved(options = {}) {
            const categoryInput = document.getElementById('kategoria_input');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const tipus = getActiveTipus();
            const categoryName = categoryInput ? categoryInput.value.trim() : '';
            const showMessage = options.showMessage !== false;

            if (!categoryName || categoryExists(categoryName, tipus)) {
                return true;
            }

            if (!csrfToken) {
                if (showMessage) {
                    setCategoryMessage('A mentés most nem elérhető.', 'error');
                }
                return false;
            }

            if (categorySavePromise) {
                return categorySavePromise;
            }

            categorySavePromise = (async () => {
                try {
                    const response = await fetch('/kategoria/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            kategoria_nev: categoryName,
                            tipus,
                        }),
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'A kategória mentése nem sikerült.');
                    }

                    if (!categoryExists(data.kategoria_nev, tipus)) {
                        categoryOptions[tipus] = [
                            ...(categoryOptions[tipus] || []),
                            {
                                id: data.kategoriaid,
                                nev: data.kategoria_nev,
                                owned: Boolean(data.owned),
                            },
                        ].sort((a, b) => a.nev.localeCompare(b.nev, 'hu', { sensitivity: 'base' }));
                    }

                    if (categoryInput) {
                        categoryInput.value = data.kategoria_nev;
                    }

                    renderKategoriak(data.kategoria_nev, true);
                    if (showMessage) {
                        setCategoryMessage('Saját kategória elmentve.', 'success');
                    }

                    return true;
                } catch (error) {
                    if (showMessage) {
                        setCategoryMessage(error.message || 'A kategória mentése nem sikerült.', 'error');
                    }
                    return false;
                } finally {
                    categorySavePromise = null;
                }
            })();

            return categorySavePromise;
        }

        function setDateValue(value) {
            const input = getDateInput();
            const display = document.getElementById('datePickerValue');

            if (!input || !display) {
                return;
            }

            input.value = value || '';
            display.textContent = formatDateForDisplay(input.value);

            const parsed = parseDateString(input.value);
            calendarState.visibleMonth = parsed
                ? new Date(parsed.getFullYear(), parsed.getMonth(), 1)
                : new Date(new Date().getFullYear(), new Date().getMonth(), 1);

            renderDatePicker();
        }

        function toggleDatePicker() {
            const wrapper = document.getElementById('date_picker');
            const trigger = document.getElementById('datePickerTrigger');
            const popover = document.getElementById('datePickerPopover');

            if (!wrapper || !trigger || !popover) {
                return;
            }

            const shouldOpen = !wrapper.classList.contains('open');
            wrapper.classList.toggle('open', shouldOpen);
            trigger.setAttribute('aria-expanded', String(shouldOpen));
            popover.setAttribute('aria-hidden', String(!shouldOpen));

            if (shouldOpen) {
                renderDatePicker();
                updateDatePickerPosition();
            }
        }

        function closeDatePicker() {
            const wrapper = document.getElementById('date_picker');
            const trigger = document.getElementById('datePickerTrigger');
            const popover = document.getElementById('datePickerPopover');

            if (wrapper) {
                wrapper.classList.remove('open');
                wrapper.classList.remove('open-upward');
            }
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
            if (popover) {
                popover.setAttribute('aria-hidden', 'true');
                popover.style.maxHeight = '';
            }
        }

        function updateDatePickerPosition() {
            const wrapper = document.getElementById('date_picker');
            const trigger = document.getElementById('datePickerTrigger');
            const popover = document.getElementById('datePickerPopover');

            if (!wrapper || !trigger || !popover || !wrapper.classList.contains('open')) {
                return;
            }

            wrapper.classList.remove('open-upward');
            popover.style.maxHeight = '';

            const triggerRect = trigger.getBoundingClientRect();
            const popoverRect = popover.getBoundingClientRect();
            const viewportPadding = 16;
            const gap = 10;
            const spaceBelow = window.innerHeight - triggerRect.bottom - viewportPadding;
            const spaceAbove = triggerRect.top - viewportPadding;
            const shouldOpenUpward = popoverRect.height > spaceBelow && spaceAbove > spaceBelow;
            const availableSpace = shouldOpenUpward ? spaceAbove - gap : spaceBelow - gap;

            wrapper.classList.toggle('open-upward', shouldOpenUpward);
            popover.style.maxHeight = `${Math.max(160, Math.floor(availableSpace))}px`;
        }

        function changeCalendarMonth(offset) {
            if (!calendarState.visibleMonth) {
                const today = new Date();
                calendarState.visibleMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            }

            calendarState.visibleMonth = new Date(
                calendarState.visibleMonth.getFullYear(),
                calendarState.visibleMonth.getMonth() + offset,
                1
            );

            renderDatePicker();
        }

        function selectDate(value) {
            setDateValue(value);
            closeDatePicker();
        }

        function jumpToToday() {
            setDateValue(formatDateForInput(new Date()));
            closeDatePicker();
        }

        function renderDatePicker() {
            const month = document.getElementById('datePickerMonth');
            const grid = document.getElementById('datePickerGrid');
            const input = getDateInput();

            if (!month || !grid) {
                return;
            }

            if (!calendarState.visibleMonth) {
                const parsed = parseDateString(input?.value || '');
                const source = parsed || new Date();
                calendarState.visibleMonth = new Date(source.getFullYear(), source.getMonth(), 1);
            }

            const visible = calendarState.visibleMonth;
            month.textContent = new Intl.DateTimeFormat('hu-HU', {
                year: 'numeric',
                month: 'long',
            }).format(visible);

            const firstDay = new Date(visible.getFullYear(), visible.getMonth(), 1);
            const gridStart = new Date(firstDay);
            const startOffset = (firstDay.getDay() + 6) % 7;
            gridStart.setDate(firstDay.getDate() - startOffset);

            const selectedValue = input?.value || '';
            const todayValue = formatDateForInput(new Date());
            const activeMonth = visible.getMonth();

            let markup = '';

            for (let index = 0; index < 42; index += 1) {
                const current = new Date(gridStart);
                current.setDate(gridStart.getDate() + index);

                const value = formatDateForInput(current);
                const classes = ['date-picker-day'];

                if (current.getMonth() !== activeMonth) {
                    classes.push('is-outside');
                }
                if (value === todayValue) {
                    classes.push('is-today');
                }
                if (value === selectedValue) {
                    classes.push('is-selected');
                }

                markup += `<button type="button" class="${classes.join(' ')}" onclick="selectDate('${value}')" aria-label="${formatDateForDisplay(value)}">${current.getDate()}</button>`;
            }

            grid.innerHTML = markup;

            if (document.getElementById('date_picker')?.classList.contains('open')) {
                updateDatePickerPosition();
            }
        }

        function openModal(tranzakcioId = null) {
            const modal = document.getElementById('koltsegModal');
            const form = document.getElementById('koltsegForm');
            const title = document.getElementById('modalTitle');
            const submitBtn = document.querySelector('#koltsegForm button[type="submit"]');
            
            modal.classList.add('show');
            
            if (tranzakcioId) {
                // Szerkesztés mód
                title.textContent = 'Tranzakció Szerkesztése';
                form.action = `/koltseg/edit/${tranzakcioId}`;
                submitBtn.textContent = 'Tranzakció Mentése';
                
                // Hidden method field hozzáadása PUT-hez
                let methodField = document.getElementById('methodField');
                if (!methodField) {
                    methodField = document.createElement('input');
                    methodField.id = 'methodField';
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PUT';
                    form.appendChild(methodField);
                } else {
                    methodField.value = 'PUT';
                }
            } else {
                // Új költség hozzáadása mód
                title.textContent = 'Új Tranzakció Hozzáadása';
                form.action = '/koltseg/add';
                submitBtn.textContent = 'Tranzakció Hozzáadása';
                
                // Method field eltávolítása (POST-hoz nincs kell)
                const methodField = document.getElementById('methodField');
                if (methodField) {
                    methodField.remove();
                }
                
                // Form mezők ürítése
                document.getElementById('kategoria_input').value = '';
                document.getElementById('penznem_input').value = '';
                setTipus('koltseg');
                document.getElementById('koltsegForm').querySelector('input[name="osszeg"]').value = '';
                setDateValue('{{ old('rogzites', now()->toDateString()) }}');
                document.getElementById('koltsegForm').querySelector('textarea[name="megjegyzes"]').value = '';
            }
        }
        
        function editTranzakcio(tranzakcioId, rogzites, kategoria, osszeg, penznem, tipus, megjegyzes) {
            openModal(tranzakcioId);
            
            // Mezők feltöltése
            document.getElementById('kategoria_input').value = kategoria;
            document.getElementById('kategoria_list').classList.remove('show');
            
            document.getElementById('penznem_input').value = penznem;
            document.getElementById('penznem_list').classList.remove('show');
            setTipus(tipus || 'koltseg');
            
            document.getElementById('koltsegForm').querySelector('input[name="osszeg"]').value = osszeg.replace(/\s/g, '').replace(',', '.');
            setDateValue(rogzites);
            document.getElementById('koltsegForm').querySelector('textarea[name="megjegyzes"]').value = megjegyzes || '';
        }
        
        function closeModal() {
            document.getElementById('koltsegModal').classList.remove('show');
            closeDatePicker();
        }

        function openDeleteConfirm(tranzakcioId, categoria, osszeg) {
            const modal = document.getElementById('deleteConfirmModal');
            const category = document.getElementById('deleteConfirmCategory');
            const amount = document.getElementById('deleteConfirmAmount');

            deleteState.id = tranzakcioId;

            if (category) {
                category.textContent = categoria || '-';
            }
            if (amount) {
                amount.textContent = osszeg || '-';
            }
            if (modal) {
                modal.classList.add('show');
            }
        }

        function closeDeleteConfirm() {
            const modal = document.getElementById('deleteConfirmModal');

            deleteState.id = null;

            if (modal) {
                modal.classList.remove('show');
            }
        }

        function confirmDeleteTranzakcio() {
            if (!deleteState.id) {
                closeDeleteConfirm();
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/koltseg/delete/${deleteState.id}`;
                
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = csrfToken;
                form.appendChild(input);
            }
                
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }

        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('koltsegModal').classList.add('show');
        });
        @endif
        document.addEventListener('DOMContentLoaded', function() {
            setTipus('{{ old('tipus', 'koltseg') }}');
            setDateValue('{{ old('rogzites', now()->toDateString()) }}');
            renderKategoriak(@json(old('kategoria', '')));

            const form = document.getElementById('koltsegForm');
            if (form) {
                form.addEventListener('submit', async function(event) {
                    const saved = await ensureCategorySaved();

                    if (!saved) {
                        event.preventDefault();
                    }
                });
            }
        });
        function filterKategoriak(forceShow = false) {
            const input = document.getElementById('kategoria_input').value;
            renderKategoriak(input, forceShow);
            setCategoryMessage('');
        }

        function selectKategoria(nev) {
            document.getElementById('kategoria_input').value = nev;
            document.getElementById('kategoria_list').classList.remove('show');
            setCategoryMessage('');
        }

        async function deleteCustomCategory(event, id, nev) {
            event.stopPropagation();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const tipus = getActiveTipus();

            if (!csrfToken) {
                setCategoryMessage('A törlés most nem elérhető.', 'error');
                return;
            }

            try {
                const response = await fetch(`/kategoria/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'A kategória törlése nem sikerült.');
                }

                categoryOptions[tipus] = (categoryOptions[tipus] || []).filter((item) => item.id !== id);

                const categoryInput = document.getElementById('kategoria_input');
                if (categoryInput && categoryInput.value.trim().toLowerCase() === String(nev).trim().toLowerCase()) {
                    categoryInput.value = '';
                }

                renderKategoriak(categoryInput ? categoryInput.value : '', true);
                setCategoryMessage('Saját kategória törölve.', 'success');
            } catch (error) {
                setCategoryMessage(error.message || 'A kategória törlése nem sikerült.', 'error');
            }
        }
        
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('kategoria_wrapper');
            const list = document.getElementById('kategoria_list');
            
            if (wrapper && !wrapper.contains(event.target)) {
                list.classList.remove('show');
            }
        });
        
        window.onclick = function(event) {
            const modal = document.getElementById('koltsegModal');
            const deleteModal = document.getElementById('deleteConfirmModal');

            if (event.target == modal) {
                closeModal();
            }
            if (event.target == deleteModal) {
                closeDeleteConfirm();
            }
        }
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('penznem_wrapper');
            const list = document.getElementById('penznem_list');
            
            if (wrapper && !wrapper.contains(event.target)) {
                list.classList.remove('show');
            }

            const datePicker = document.getElementById('date_picker');
            if (datePicker && !datePicker.contains(event.target)) {
                closeDatePicker();
            }
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDatePicker();
                closeDeleteConfirm();
            }
        });
        window.addEventListener('resize', function() {
            updateDatePickerPosition();
        });
        function filterPenznemek() {
            const input = document.getElementById('penznem_input').value.toUpperCase();
            const list = document.getElementById('penznem_list');
            const items = list.querySelectorAll('.penznem-item');
            
            if (input.length > 0) {
                list.classList.add('show');
            } else {
                list.classList.remove('show');
            }
            
            items.forEach(item => {
                const text = item.textContent.toUpperCase();
                if (text.includes(input)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        function selectPenznem(nev) {
            document.getElementById('penznem_input').value = nev;
            document.getElementById('penznem_list').classList.remove('show');
        }
        
        function deleteTranzakcio(tranzakcioId, categoria, osszeg) {
            openDeleteConfirm(tranzakcioId, categoria, osszeg);
        }

    </script>
</head>
<body>
    <div class="header">
        <div class="header-brand">
            <div class="header-logo">
                @include('partials.app_logo')
            </div>
            <div class="header-title">
                <h1>SpendWise</h1>
            </div>
        </div>
        <div class="header-user">
            <a href="/logout" class="logout-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Kijelentkezés
            </a>
        </div>
    </div>
    
    <div class="container">
        <p class="page-intro">Üdvözlünk, {{ session('user')->nev }}!</p>
        <div class="page-actions">
            <button onclick="openModal()" class="add-btn primary-btn">+ Új Tranzakció</button>
            <button onclick="location.href='/statisztika'" class="add-btn secondary-btn">Statisztika</button>
        </div>

        <div class="main-layout">
            <!-- Bal oldal: Költségek listája (3/5) -->
            <div class="left-column">
                @if($availableMonths->count() > 0)
                    @php
                        $monthValues = $availableMonths->values();
                        $currentMonthIndex = $monthValues->search($selectedMonth);
                        $prevMonth = ($currentMonthIndex !== false && $currentMonthIndex < ($monthValues->count() - 1))
                            ? $monthValues[$currentMonthIndex + 1]
                            : null;
                        $nextMonth = ($currentMonthIndex !== false && $currentMonthIndex > 0)
                            ? $monthValues[$currentMonthIndex - 1]
                            : null;
                    @endphp

                    <div class="transactions-title-row">
                        <h2>Tranzakcióid - {{ $selectedMonthLabel }}</h2>
                        <div class="month-inline-controls">
                            <a class="month-nav-btn {{ $prevMonth ? '' : 'disabled' }}" href="{{ $prevMonth ? '/fooldal?honap='.$prevMonth : '#' }}" aria-label="Előző hónap">‹</a>
                            <span class="month-inline-current">{{ $selectedMonthLabel }}</span>
                            <a class="month-nav-btn {{ $nextMonth ? '' : 'disabled' }}" href="{{ $nextMonth ? '/fooldal?honap='.$nextMonth : '#' }}" aria-label="Következő hónap">›</a>
                        </div>
                    </div>

                    @if($tranzakciokAtvalasztva->count() > 0)
                    <table>
                        <tr>
                            <th>Dátum</th>
                            <th>Kategória</th>
                            <th>Összeg</th>
                            <th>Leírás</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($tranzakciokAtvalasztva as $item)
                            <tr>
                                <td>{{ $item->rogzites }}</td>
                                <td>
                                    {{ $item->kategoria->nev ?? (\App\Models\kategoria::find($item->kategoriaid)->nev ?? '-') }}
                                </td>
                                <td class="amount-cell">
                                    @php $isIncome = (($item->tipus ?? 'koltseg') === 'bevetel'); @endphp
                                    <span class="type-badge {{ $isIncome ? 'type-income' : 'type-expense' }}">{{ $isIncome ? 'Bevétel' : 'Költség' }}</span>
                                    <strong class="{{ $isIncome ? 'amount-income' : 'amount-expense' }}">
                                        {{ $isIncome ? '+' : '-' }}{{ number_format($item->osszeg, 2, ',', ' ') }} {{ $item->penznem->nev }}
                                    </strong>
                                    @if(($item->penznem->nev ?? null) !== 'HUF' && $item->osszeghuf)
                                        <span class="converted-amount {{ $isIncome ? 'amount-income' : 'amount-expense' }}">
                                            {{ $isIncome ? '+' : '-' }}{{ number_format($item->osszeghuf, 0, ',', ' ') }} Ft
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $item->megjegyzes }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn" onclick="editTranzakcio({{ $item->id }}, '{{ $item->rogzites }}', '{{ $item->kategoria->nev ?? '-' }}', '{{ number_format($item->osszeg, 2, ',', ' ') }}', '{{ $item->penznem->nev }}', '{{ $item->tipus ?? 'koltseg' }}', '{{ addslashes($item->megjegyzes) }}')" title="Szerkesztés">Szerkesztés</button>
                                        <button class="action-btn action-btn-danger" onclick="deleteTranzakcio({{ $item->id }}, '{{ $item->kategoria->nev ?? '-' }}', '{{ number_format($item->osszeg, 2, ',', ' ') }} {{ $item->penznem->nev }}')" title="Törlés">Törlés</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    @else
                    <div class="no-data">
                        <p>Ebben a hónapban még nincs tranzakció.</p>
                    </div>
                    @endif
                @else
                    <div class="no-data">
                        <p>Még nincsenek tranzakcióid. <button onclick="openModal()" style="background: none; border: none; color: #667eea; cursor: pointer; text-decoration: underline;">Hozzáadj egyet!</button></p>
                    </div>
                @endif
            </div>

            <!-- Jobb oldal: Statisztika (2/5) -->
            <div class="right-column">
                <div class="stats-box">
                    <div class="section-heading">
                        <h2 class="section-title">Gyors Áttekintés</h2>
                    </div>
                    <div class="stats-summary">
                        <div class="stat-card">
                            <div class="stat-card-label">Összes kiadás (forintban)</div>
                            <div class="stat-card-value" style="color:#b00020;">{{ number_format($expenseTotal, 0, ',', ' ') }} Ft</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-label">Összes bevétel (forintban)</div>
                            <div class="stat-card-value" style="color:#1b8f3a;">{{ number_format($incomeTotal, 0, ',', ' ') }} Ft</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-label">Pénzáramlás</div>
                            <div class="stat-card-value">
                                <span style="color: {{ $balanceTotal >= 0 ? '#1b8f3a' : '#b00020' }};">
                                    {{ number_format($balanceTotal, 0, ',', ' ') }} Ft
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($byCurrency && $byCurrency->count() > 0)
                        <table class="stats-table">
                            <thead>
                                <tr>
                                    <th>Pénznem</th>
                                    <th>Kiadás</th>
                                    <th>Bevétel</th>
                                    <th>Pénzáramlás</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byCurrency as $item)
                                    <tr>
                                        <td><strong>{{ $item->currency }}</strong></td>
                                        <td style="text-align:right; color:#b00020;">
                                            <strong>{{ number_format($item->native_expense, 2, ',', ' ') }} {{ $item->currency }}</strong>
                                            @if($item->currency !== 'HUF')
                                                <div style="font-size:12px; color:#666;">{{ number_format($item->expense, 0, ',', ' ') }} Ft</div>
                                            @endif
                                        </td>
                                        <td style="text-align:right; color:#1b8f3a;">
                                            <strong>{{ number_format($item->native_income, 2, ',', ' ') }} {{ $item->currency }}</strong>
                                            @if($item->currency !== 'HUF')
                                                <div style="font-size:12px; color:#666;">{{ number_format($item->income, 0, ',', ' ') }} Ft</div>
                                            @endif
                                        </td>
                                        <td style="text-align:right;">
                                            <strong style="color: {{ $item->total >= 0 ? '#1b8f3a' : '#b00020' }};">
                                                {{ number_format($item->native_total, 2, ',', ' ') }} {{ $item->currency }}
                                            </strong>
                                            @if($item->currency !== 'HUF')
                                                <div style="font-size:12px; color:#666;">{{ number_format($item->total, 0, ',', ' ') }} Ft</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <div class="stat-link">
                        <a href="/statisztika">→ Részletes statisztika</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div id="koltsegModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div class="section-heading">
                <h2 id="modalTitle" class="section-title">Új Tranzakció Hozzáadása</h2>
            </div>
            
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div style="color: #d32f2f; padding: 10px; background-color: #ffebee; border-left: 3px solid #d32f2f; border-radius: 3px; margin-bottom: 15px;">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            
            <form action="/koltseg/add" method="POST" id="koltsegForm">
                @csrf
                <div class="type-toggle" role="group" aria-label="Tranzakció típusa">
                    <input type="hidden" name="tipus" id="tipus_input" class="type-toggle-input" value="{{ old('tipus', 'koltseg') }}" required>
                    <button type="button" class="type-toggle-option" data-value="koltseg" onclick="setTipus('koltseg')" aria-pressed="false">Költség</button>
                    <button type="button" class="type-toggle-option" data-value="bevetel" onclick="setTipus('bevetel')" aria-pressed="false">Bevétel</button>
                </div>
                <!--Kategória-->
                <div class="kategoria-input-wrapper" id="kategoria_wrapper">
                    <input type="text" id="kategoria_input" name="kategoria" placeholder="Kategória" value="{{ old('kategoria') }}" required
                        oninput="filterKategoriak()" onclick="filterKategoriak(true)" onblur="ensureCategorySaved()">
                    <div id="kategoria_list" class="kategoria-list"></div>
                    <div id="kategoria_message" class="field-inline-message"></div>
                </div>

                <input type="text" name="osszeg" placeholder="Összeg" value="{{ old('osszeg') }}" required>
                <!--Pénznem-->
                <div class="kategoria-input-wrapper" id="penznem_wrapper">
                    <input type="text" id="penznem_input" name="penznem" placeholder="Pénznem" value="{{ old('penznem', 'HUF') }}" required
                           oninput="filterPenznemek()" onclick="document.getElementById('penznem_list').classList.add('show')">
                    <div id="penznem_list" class="kategoria-list">
                        @foreach($penznemek as $penznem)
                            <div class="kategoria-item penznem-item" onclick="selectPenznem('{{ $penznem->nev }}')">{{ $penznem->nev }}</div>
                        @endforeach
                    </div>
                </div>
                
                <div class="date-field date-picker" id="date_picker">
                    <input
                        type="hidden"
                        class="date-picker-input"
                        id="rogzites_input"
                        name="rogzites"
                        value="{{ old('rogzites', now()->toDateString()) }}"
                        required
                    >
                    <button
                        type="button"
                        class="date-picker-trigger"
                        id="datePickerTrigger"
                        onclick="toggleDatePicker()"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="datePickerPopover"
                    >
                        <span class="date-picker-trigger-value" id="datePickerValue">Válassz dátumot</span>
                        <span class="date-picker-trigger-icon" aria-hidden="true"></span>
                    </button>
                    <div class="date-picker-popover" id="datePickerPopover" aria-hidden="true">
                        <div class="date-picker-header">
                            <button type="button" class="date-picker-nav" onclick="changeCalendarMonth(-1)" aria-label="Előző hónap">‹</button>
                            <div class="date-picker-month" id="datePickerMonth"></div>
                            <button type="button" class="date-picker-nav" onclick="changeCalendarMonth(1)" aria-label="Következő hónap">›</button>
                        </div>

                        <div class="date-picker-weekdays">
                            <span class="date-picker-weekday">H</span>
                            <span class="date-picker-weekday">K</span>
                            <span class="date-picker-weekday">Sze</span>
                            <span class="date-picker-weekday">Cs</span>
                            <span class="date-picker-weekday">P</span>
                            <span class="date-picker-weekday">Szo</span>
                            <span class="date-picker-weekday">V</span>
                        </div>

                        <div class="date-picker-grid" id="datePickerGrid"></div>

                        <div class="date-picker-actions">
                            <button type="button" class="date-picker-action" onclick="closeDatePicker()">Bezárás</button>
                            <button type="button" class="date-picker-action date-picker-action-primary" onclick="jumpToToday()">Ma</button>
                        </div>
                    </div>
                </div>
                <textarea name="megjegyzes" placeholder="Leírás (megjegyzés)">{{ old('megjegyzes') }}</textarea>
                <button type="submit">Tranzakció Hozzáadása</button>
            </form>
        </div>
    </div>

    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content confirm-modal-content">
            <div class="section-heading">
                <h2 class="section-title">Biztosan törölni akarod?</h2>
            </div>
            <div class="confirm-meta">
                <div class="confirm-meta-row">
                    <span class="confirm-meta-label">Kategória</span>
                    <span class="confirm-meta-value" id="deleteConfirmCategory">-</span>
                </div>
                <div class="confirm-meta-row">
                    <span class="confirm-meta-label">Összeg</span>
                    <span class="confirm-meta-value" id="deleteConfirmAmount">-</span>
                </div>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn confirm-btn-secondary" onclick="closeDeleteConfirm()">Mégse</button>
                <button type="button" class="confirm-btn confirm-btn-danger" onclick="confirmDeleteTranzakcio()">Törlés</button>
            </div>
        </div>
    </div>
</body>
</html>
