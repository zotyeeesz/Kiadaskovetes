<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Főoldal - Költség Követő</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            background-color: #f5f5f5;
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
            max-width: 1400px;
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
        .modal form input, .modal form textarea {
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
        .modal form input:focus, .modal form textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        .modal form textarea {
            resize: vertical;
            min-height: 80px;
        }
        .modal form button {
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
        .modal form button:hover {
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
    <script>
        function openModal(tranzakcioId = null) {
            const modal = document.getElementById('koltsegModal');
            const form = document.getElementById('koltsegForm');
            const title = document.getElementById('modalTitle');
            const submitBtn = document.querySelector('#koltsegForm button[type="submit"]');
            
            modal.classList.add('show');
            
            if (tranzakcioId) {
                // Szerkesztés mód
                title.textContent = 'Költség Szerkesztése';
                form.action = `/koltseg/edit/${tranzakcioId}`;
                submitBtn.textContent = 'Költség Szerkesztése';
                
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
                title.textContent = 'Új Költség Hozzáadása';
                form.action = '/koltseg/add';
                submitBtn.textContent = 'Költség Hozzáadása';
                
                // Method field eltávolítása (POST-hoz nincs kell)
                const methodField = document.getElementById('methodField');
                if (methodField) {
                    methodField.remove();
                }
                
                // Form mezők ürítése
                document.getElementById('kategoria_input').value = '';
                document.getElementById('penznem_input').value = '';
                document.getElementById('koltsegForm').querySelector('input[name="osszeg"]').value = '';
                document.getElementById('koltsegForm').querySelector('input[name="rogzites"]').value = '';
                document.getElementById('koltsegForm').querySelector('textarea[name="megjegyzes"]').value = '';
            }
        }
        
        function editTranzakcio(tranzakcioId, rogzites, kategoria, osszeg, penznem, megjegyzes) {
            openModal(tranzakcioId);
            
            // Mezők feltöltése
            document.getElementById('kategoria_input').value = kategoria;
            document.getElementById('kategoria_list').classList.remove('show');
            
            document.getElementById('penznem_input').value = penznem;
            document.getElementById('penznem_list').classList.remove('show');
            
            document.getElementById('koltsegForm').querySelector('input[name="osszeg"]').value = osszeg.replace(/\s/g, '').replace(',', '.');
            document.getElementById('koltsegForm').querySelector('input[name="rogzites"]').value = rogzites;
            document.getElementById('koltsegForm').querySelector('textarea[name="megjegyzes"]').value = megjegyzes || '';
        }
        
        function closeModal() {
            document.getElementById('koltsegModal').classList.remove('show');
        }
        function filterKategoriak() {
            const input = document.getElementById('kategoria_input').value.toLowerCase();
            const list = document.getElementById('kategoria_list');
            const items = list.querySelectorAll('.kategoria-item');
            
            if (input.length > 0) {
                list.classList.add('show');
            } else {
                list.classList.remove('show');
            }
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(input)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        function selectKategoria(nev) {
            document.getElementById('kategoria_input').value = nev;
            document.getElementById('kategoria_list').classList.remove('show');
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
            if (event.target == modal) {
                modal.classList.remove('show');
            }
        }
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('penznem_wrapper');
            const list = document.getElementById('penznem_list');
            
            if (wrapper && !wrapper.contains(event.target)) {
                list.classList.remove('show');
            }
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
            if (confirm(`Biztosan törlöd ezt a költséget?\n\nKategória: ${categoria}\nÖsszeg: ${osszeg}`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/koltseg/delete/${tranzakcioId}`;
                
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
        }

    </script>
</head>
<body>
    <div class="header">
        <div>
            <h1>Költség Követő</h1>
            <h2>Üdvözlünk, {{ session('user')->nev }}!</h2>
        </div>
        <a href="/logout" class="logout-btn">Kijelentkezés</a>
    </div>
    
    <div class="container">
        <div style="margin-bottom: 20px;">
            <button onclick="openModal()" class="add-btn">+ Új Költség</button>
            <button onclick="location.href='/statisztika'" class="add-btn">Statisztika</button>
        </div>

        <div class="main-layout">
            <!-- Bal oldal: Költségek listája (3/5) -->
            <div class="left-column">
                @if($tranzakciok->count() > 0)
                    <h2>Költségeid</h2>
                    <table>
                        <tr>
                            <th>Dátum</th>
                            <th>Kategória</th>
                            <th>Összeg</th>
                            <th>Forint érték</th>
                            <th>Leírás</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($tranzakciokAtvalasztva as $item)
                            <tr>
                                <td>{{ $item->rogzites }}</td>
                                <td>
                                    {{ $item->kategoria->nev ?? (\App\Models\kategoria::find($item->kategoriaid)->nev ?? '-') }}
                                </td>
                                <td><strong>{{ number_format($item->osszeg, 2, ',', ' ') }}</strong> {{ $item->penznem->nev }}</td>
                                <td><strong>{{ number_format($item->osszeghuf, 0, ',', ' ') }}</strong> Ft</td>
                                <td>{{ $item->megjegyzes }}</td>
                                <td>
                                    <button class="delete-btn" onclick="editTranzakcio({{ $item->id }}, '{{ $item->rogzites }}', '{{ $item->kategoria->nev ?? '-' }}', '{{ number_format($item->osszeg, 2, ',', ' ') }}', '{{ $item->penznem->nev }}', '{{ addslashes($item->megjegyzes) }}')" title="Szerkesztés">✏️</button>
                                    <button class="delete-btn" onclick="deleteTranzakcio({{ $item->id }}, '{{ $item->kategoria->nev ?? '-' }}', '{{ number_format($item->osszeg, 2, ',', ' ') }} {{ $item->penznem->nev }}')" title="Törlés">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="no-data">
                        <p>Még nincsenek költségeid. <button onclick="openModal()" style="background: none; border: none; color: #667eea; cursor: pointer; text-decoration: underline;">Hozzáadj egyet!</button></p>
                    </div>
                @endif
            </div>

            <!-- Jobb oldal: Statisztika (2/5) -->
            <div class="right-column">
                <div class="stats-box">
                    <h2 style="margin-top: 0;">Gyors Áttekintés</h2>
                    <div class="stats-summary">
                        <div class="stat-card">
                            <div class="stat-card-label">Összes költség (forintban)</div>
                            <div class="stat-card-value">{{ number_format($total, 0, ',', ' ') }} Ft</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-label">Költségek száma</div>
                            <div class="stat-card-value">{{ $tranzakciok->count() }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-label">Top kategória</div>
                            <div class="stat-card-value" style="font-size: 14px;">
                                @if($byCategory && $byCategory->count() > 0)
                                    {{ $byCategory[0]->name }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($byCategory && $byCategory->count() > 0)
                        <table class="stats-table">
                            <thead>
                                <tr><th>Top kategóriák</th><th>Összeg</th></tr>
                            </thead>
                            <tbody>
                                @foreach($byCategory as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td style="text-align:right;"><strong>{{ number_format($item->total, 0, ',', ' ') }} Ft</strong></td>
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
            <h2 id="modalTitle">Új Költség Hozzáadása</h2>
            
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div style="color: #d32f2f; padding: 10px; background-color: #ffebee; border-left: 3px solid #d32f2f; border-radius: 3px; margin-bottom: 15px;">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            
            <form action="/koltseg/add" method="POST" id="koltsegForm">
                @csrf
                <!--Kategória-->
                <div class="kategoria-input-wrapper" id="kategoria_wrapper">
                    <input type="text" id="kategoria_input" name="kategoria" placeholder="Kategória" required
                        oninput="filterKategoriak()" onclick="document.getElementById('kategoria_list').classList.add('show')">
                    <div id="kategoria_list" class="kategoria-list">                        
                        @foreach($kategoriak as $kat)
                            <div class="kategoria-item" onclick="selectKategoria('{{ $kat->nev }}')">{{ $kat->nev }}</div>
                        @endforeach
                    </div>
                </div>

                <input type="text" name="osszeg" placeholder="Összeg" required>
                <!--Pénznem-->
                <div class="kategoria-input-wrapper" id="penznem_wrapper">
                    <input type="text" id="penznem_input" name="penznem" placeholder="Pénznem" required
                           oninput="filterPenznemek()" onclick="document.getElementById('penznem_list').classList.add('show')">
                    <div id="penznem_list" class="kategoria-list">
                        @foreach($penznemek as $penznem)
                            <div class="kategoria-item penznem-item" onclick="selectPenznem('{{ $penznem->nev }}')">{{ $penznem->nev }}</div>
                        @endforeach
                    </div>
                </div>
                
                <input type="date" name="rogzites" required>
                <textarea name="megjegyzes" placeholder="Leírás (megjegyzés)"></textarea>
                <button type="submit">Költség Hozzáadása</button>
            </form>
        </div>
    </div>
</body>
</html>
