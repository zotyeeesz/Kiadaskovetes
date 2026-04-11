@extends('layouts.ui')

@section('title', 'Regisztráció - Költség Követő')
@section('body_class', 'auth-body')

@section('content')
    <div class="auth-shell">
        <div class="auth-grid">
            <section class="window window-dark reveal">
                <div class="window-header">
                    @include('partials.window_controls')
                    <div class="window-title-group">
                        <span class="window-title">Setup Assistant</span>
                        <span class="window-subtitle">Személyes tér létrehozása néhány lépésben</span>
                    </div>
                </div>

                <div class="window-body control-stack">
                    <div>
                        <span class="auth-kicker">Saját workspace</span>
                        <h1 class="auth-title">Építs fel egy rendezett pénzügyi felületet magadnak.</h1>
                        <p class="auth-copy">
                            A regisztráció után egy ellenőrző emailt küldünk, és onnantól a saját tranzakcióid, kategóriáid és kimutatásaid külön térben élnek tovább.
                        </p>
                    </div>

                    <div class="auth-beat-list">
                        <article class="auth-beat">
                            <span class="auth-beat-index">01</span>
                            <div>
                                <strong>Saját kategóriák</strong>
                                <p>A munkafolyamat közben létrehozott kategóriák rögtön a saját profilodhoz kerülnek.</p>
                            </div>
                        </article>
                        <article class="auth-beat">
                            <span class="auth-beat-index">02</span>
                            <div>
                                <strong>Pénznemek együtt</strong>
                                <p>A felület kezeli a vegyes pénznemeket, miközben a statisztikákat forint alapon is látod.</p>
                            </div>
                        </article>
                        <article class="auth-beat">
                            <span class="auth-beat-index">03</span>
                            <div>
                                <strong>Megerősített belépés</strong>
                                <p>A hozzáférés email-validáció után aktiválódik, így tisztább és biztonságosabb marad a belépés.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="window reveal">
                <div class="window-header">
                    @include('partials.window_controls')
                    <div class="window-title-group">
                        <span class="window-title">Új felhasználó</span>
                        <span class="window-subtitle">Alapadatok és kapcsolatfelvétel</span>
                    </div>
                </div>

                <div class="window-body">
                    <div class="auth-panel-copy">
                        <span class="section-kicker">Kezdjük el</span>
                        <h2 class="section-title">Hozd létre a saját fiókodat.</h2>
                        <p class="section-copy">A regisztráció után megerősítő emailt küldünk, a belépés ezután válik elérhetővé.</p>
                    </div>

                    @include('partials.flash_messages', ['info' => null])

                    <div class="flash flash-info" style="margin-bottom: 18px;">
                        Regisztráció után megerősítő emailt küldünk. A bejelentkezés csak az email cím jóváhagyása után lehetséges.
                    </div>

                    <form action="/felhasznalo/add" method="POST" class="form-grid two-columns">
                        @csrf

                        <div class="field-group span-2">
                            <label class="field-label" for="reg_name">Név</label>
                            <input class="field-control" id="reg_name" type="text" name="nev" value="{{ old('nev') }}" placeholder="Teljes név" required>
                        </div>

                        <div class="field-group span-2">
                            <label class="field-label" for="reg_email">Email cím</label>
                            <input class="field-control" id="reg_email" type="email" name="email" value="{{ old('email') }}" placeholder="pelda@email.hu" required>
                        </div>

                        <div class="field-group span-2">
                            <label class="field-label" for="reg_password">Jelszó</label>
                            <input class="field-control" id="reg_password" type="password" name="password" placeholder="Legalább 6 karakter" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="reg_phone">Telefonszám</label>
                            <input class="field-control" id="reg_phone" type="text" name="telefonszam" value="{{ old('telefonszam') }}" placeholder="Opcionális">
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="reg_country">Ország</label>
                            <input class="field-control" id="reg_country" type="text" name="orszag" value="{{ old('orszag') }}" placeholder="Opcionális">
                        </div>

                        <div class="field-group span-2">
                            <label class="field-label" for="reg_city">Település</label>
                            <input class="field-control" id="reg_city" type="text" name="telepules" value="{{ old('telepules') }}" placeholder="Opcionális">
                        </div>

                        <div class="field-group span-2">
                            <button type="submit" class="btn btn-primary">Regisztráció</button>
                        </div>
                    </form>

                    <p class="auth-footer" style="margin-top: 22px;">
                        Már van fiókod?
                        <a href="/login">Jelentkezz be</a>
                    </p>
                </div>
            </section>
        </div>
    </div>
@endsection
