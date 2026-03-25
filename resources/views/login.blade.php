<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés - Költség Követő</title>
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
        .resend-box {
            margin-top: 10px;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background: #fafafa;
        }
        .resend-box p {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #444;
        }
        .resend-box form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .resend-box input {
            margin: 0;
            flex: 1;
        }
        .resend-box button {
            width: auto;
            margin-top: 0;
            padding: 10px 14px;
            font-size: 13px;
        }
        /* Reszponzív beállítások */
        @media (max-width: 600px) {
            body {
                padding: 20px;
                align-items: flex-start;
                height: auto;
            }
            .login-container {
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 6px 18px rgba(0,0,0,0.12);
                max-width: 100%;
                width: 100%;
            }
            h1 { font-size: 20px; }
            form input { padding: 10px; font-size: 14px; }
            form button { padding: 10px; font-size: 15px; }
            .register-link { font-size: 13px; }
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
