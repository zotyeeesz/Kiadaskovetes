<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés - SpendWise</title>
    <!-- Legacy styles removed - see modern styles below -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap");
        :root {
            --ink: #1e293b;
            --muted: #64748b;
            --card: #ffffff;
            --line: rgba(30, 41, 59, 0.1);
            --accent: #059669;
            --accent-soft: #10b981;
            --accent-light: rgba(5, 150, 105, 0.1);
            --ok: #10b981;
            --bad: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Instrument Sans", "Segoe UI", sans-serif;
            background: #f8fafc !important;
            color: var(--ink);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            border-radius: 16px !important;
            background: var(--card) !important;
            border: 1px solid var(--line);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 16px;
            color: #111827;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            margin: 0 auto 16px;
        }
        .login-logo svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }
        .login-brand h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--ink) !important;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }
        form input {
            border-radius: 10px !important;
            border: 1px solid var(--line) !important;
            background: #ffffff !important;
            padding: 12px 14px;
            font-size: 14px;
            transition: all .2s ease;
            width: 100%;
            margin: 12px 0;
            box-sizing: border-box;
        }
        form input:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--accent-light) !important;
            outline: none;
        }
        form button {
            border-radius: 10px !important;
            background: var(--accent) !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            transition: all .2s ease;
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            margin-top: 16px;
            border: none;
            cursor: pointer;
            color: white;
        }
        form button:hover {
            transform: translateY(-1px);
            background: #047857 !important;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
        }
        .error, .success {
            border-radius: 10px !important;
            border: 1px solid transparent;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .error {
            color: var(--bad) !important;
            background: rgba(239, 68, 68, 0.1) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }
        .success {
            color: var(--ok) !important;
            background: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
        .resend-box {
            border-radius: 12px !important;
            border: 1px solid var(--line) !important;
            background: #f8fafc !important;
            padding: 16px;
            margin-top: 16px;
        }
        .resend-box p, .register-link { 
            color: var(--muted) !important; 
            font-size: 14px;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a {
            color: var(--accent) !important;
            font-weight: 600;
            text-decoration: none !important;
            border-bottom: 2px solid var(--accent-light);
        }
        .register-link a:hover {
            color: #047857 !important;
        }
        .resend-box form {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }
        .resend-box input {
            margin: 0 !important;
            flex: 1;
        }
        .resend-box button {
            width: auto !important;
            margin-top: 0 !important;
            padding: 10px 16px;
            font-size: 13px;
        }
        @media (max-width: 600px) {
            .login-container { 
                border-radius: 12px !important; 
                padding: 24px;
            }
            .resend-box form { 
                flex-direction: column; 
                align-items: stretch; 
            }
            .resend-box button { 
                width: 100% !important; 
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="login-logo">
                @include('partials.app_logo')
            </div>
            <h1>SpendWise</h1>
        </div>
        
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif
        
        <form action="/login" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email cím" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <button type="submit">Bejelentkezés</button>
        </form>

        @if(session('pending_verification_email'))
            <div class="resend-box">
                <p>Nem kaptad meg a megerősítő emailt? Itt újra tudod küldeni.</p>
                <form action="/email/verify/resend" method="POST">
                    @csrf
                    <input type="email" name="email" value="{{ session('pending_verification_email') }}" required>
                    <button type="submit">Újraküldés</button>
                </form>
            </div>
        @endif
        
        <div class="register-link">
            Még nincs fiók? <a href="/felhasznalo/add">Regisztrálj most</a>
        </div>
    </div>
</body>
</html>
