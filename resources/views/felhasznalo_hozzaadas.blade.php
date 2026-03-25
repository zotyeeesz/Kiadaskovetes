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
