@extends('layouts.ui')

@section('title', 'Bejelentkezés - Költség Követő')
@section('body_class', 'auth-body')

@section('content')
    <div class="auth-shell">
        <div class="auth-grid auth-grid-single">
            <section class="window reveal">
                <div class="window-header">
                    @include('partials.window_controls')
                    <div class="window-title-group">
                        <span class="window-title">Belépési ablak</span>
                        <span class="window-subtitle">Biztonságos hozzáférés a saját munkateredhez</span>
                    </div>
                </div>

                <div class="window-body">
                    <div class="auth-panel-copy">
                        <span class="section-kicker">Üdv újra</span>
                        <h2 class="section-title">Lépj be a pénzügyi műszerfaladra.</h2>
                        <p class="section-copy">Add meg a bejelentkezési adataidat, és folytathatod ott, ahol abbahagytad.</p>
                    </div>

                    @include('partials.flash_messages')

                    <form action="/login" method="POST" class="form-grid">
                        @csrf

                        <div class="field-group">
                            <label class="field-label" for="login_email">Email cím</label>
                            <input
                                class="field-control"
                                id="login_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="pelda@email.hu"
                                required
                            >
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="login_password">Jelszó</label>
                            <input
                                class="field-control"
                                id="login_password"
                                type="password"
                                name="password"
                                placeholder="Jelszó"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">Bejelentkezés</button>
                    </form>

                    @if(session('pending_verification_email'))
                        <div class="notice-card" style="margin-top: 18px;">
                            <span class="info-label">Email megerősítés</span>
                            <strong>Nem érkezett meg a levél?</strong>
                            <p class="muted-text">Küldhetsz egy új megerősítő linket ugyanarra az email címre.</p>

                            <form action="/email/verify/resend" method="POST" class="inline-form">
                                @csrf
                                <input
                                    class="field-control"
                                    type="email"
                                    name="email"
                                    value="{{ session('pending_verification_email') }}"
                                    required
                                >
                                <button type="submit" class="btn btn-secondary">Újraküldés</button>
                            </form>
                        </div>
                    @endif

                    <p class="auth-footer" style="margin-top: 22px;">
                        Még nincs fiókod?
                        <a href="/felhasznalo/add">Regisztrálj most</a>
                    </p>
                </div>
            </section>
        </div>
    </div>
@endsection
