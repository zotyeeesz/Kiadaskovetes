<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teszt - Felhasználók</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        form { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; }
        form input { display: block; margin: 10px 0; padding: 8px; width: 300px; }
        form button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        form button:hover { background-color: #45a049; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Felhasználók</h1>
    <a href="/felhasznalo/add">+Új felhasználó hozzáadása</a>
    
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($felhasznalo->count() > 0)
        <table>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Email</th>
            </tr>
            @foreach($felhasznalo as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->nev }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Nincs felhasználó az adatbázisban</p>
    @endif
</body>
</html>