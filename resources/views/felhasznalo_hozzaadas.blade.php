<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztrálás - SpendWise</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        form input {
            display: block;
            width: 100%;
            margin: 15px 0;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        form button {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
        form button:hover {
            background-color: #5568d3;
        }
        .info {
            color: #0d47a1;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e3f2fd;
            border-left: 3px solid #0d47a1;
            border-radius: 3px;
            font-size: 13px;
        }
        .error {
            color: #d32f2f;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffebee;
            border-left: 3px solid #d32f2f;
            border-radius: 3px;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .success {
            color: green;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e8f5e9;
            border-left: 3px solid green;
            border-radius: 3px;
        }
    </style>
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
            --info: #3b82f6;
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
        .info, .error, .success {
            border-radius: 10px !important;
            border: 1px solid transparent;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .info {
            color: var(--info) !important;
            background: rgba(59, 130, 246, 0.1) !important;
            border-color: rgba(59, 130, 246, 0.2) !important;
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
        .register-link { 
            color: var(--muted) !important;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
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
        @media (max-width: 600px) {
            .login-container { 
                border-radius: 12px !important; 
                padding: 24px;
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
        <div class="info">
            Regisztráció után megerősítő emailt küldünk. A bejelentkezés csak megerősítés után lehetséges.
        </div>
        
        <form action="/felhasznalo/add" method="POST">
            @csrf
            <input type="text" name="nev" placeholder="Felhasználónév" required>
            <input type="email" name="email" placeholder="Email - bejelentkezéshez" required>
            <input type="password" name="password" placeholder="Jelszó - bejelentkezéshez" required>
            <input type="text" name="telefonszam" placeholder="Telefonszám - opcionális">
            <input type="text" name="orszag" placeholder="Ország - opcionális">
            <input type="text" name="telepules" placeholder="Település - opcionális">
            <button type="submit">Regisztrálás</button>
        </form>
        
        <div class="register-link">
            Már van fiók? <a href="/login">Bejelentkezés</a>
        </div>
    </div>
</body>
</html>
