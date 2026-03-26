<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztrálás - Költség Követő</title>
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
        @import url("https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap");
        :root {
            --ink: #112641;
            --muted: #53657d;
            --card: rgba(255, 255, 255, 0.78);
            --line: rgba(17, 38, 65, 0.14);
            --accent: #ff5a36;
            --accent-2: #00b8d9;
            --ok: #0a9a57;
            --bad: #bf1f3f;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Sora", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 18% 18%, rgba(255, 90, 54, 0.30), transparent 42%),
                radial-gradient(circle at 82% 12%, rgba(0, 184, 217, 0.24), transparent 40%),
                linear-gradient(135deg, #fdf2f8, #ecfeff 52%, #fff7ed) !important;
            color: var(--ink);
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            border-radius: 24px !important;
            background: var(--card) !important;
            border: 1px solid var(--line);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 28px 60px rgba(255, 90, 54, 0.18) !important;
        }
        h1 {
            font-size: clamp(1.6rem, 3vw, 2rem);
            font-weight: 800;
            letter-spacing: 0.4px;
            color: var(--ink) !important;
        }
        form input {
            border-radius: 14px !important;
            border: 1px solid var(--line) !important;
            background: rgba(255,255,255,0.9);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }
        form input:focus {
            border-color: var(--accent-2) !important;
            box-shadow: 0 0 0 4px rgba(0,184,217,0.18) !important;
            transform: translateY(-1px);
        }
        form button {
            border-radius: 14px !important;
            background: linear-gradient(110deg, var(--accent), #ff7f50 54%, var(--accent-2)) !important;
            box-shadow: 0 12px 24px rgba(255, 90, 54, 0.28);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        form button:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(255, 90, 54, 0.34); }
        .info, .error, .success {
            border-radius: 12px !important;
            border-left: 0 !important;
            border: 1px solid transparent;
        }
        .info {
            color: #0c5575 !important;
            background: rgba(0, 184, 217, 0.13) !important;
            border-color: rgba(0, 184, 217, 0.25) !important;
        }
        .error {
            color: var(--bad) !important;
            background: rgba(191, 31, 63, 0.10) !important;
            border-color: rgba(191, 31, 63, 0.24) !important;
        }
        .success {
            color: var(--ok) !important;
            background: rgba(10, 154, 87, 0.11) !important;
            border-color: rgba(10, 154, 87, 0.24) !important;
        }
        .register-link { color: var(--muted) !important; }
        .register-link a {
            color: var(--ink) !important;
            font-weight: 700;
            border-bottom: 2px solid rgba(255, 90, 54, 0.45);
            text-decoration: none !important;
        }
        @media (max-width: 600px) {
            .login-container { border-radius: 20px !important; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Költség Követő</h1>
        
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
