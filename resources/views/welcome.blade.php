<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'HRIS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <style>
            * { box-sizing: border-box; }

            html, body {
                margin: 0;
                padding: 0;
            }

            body {
                min-height: 100vh;
                background: #ffffff;
                color: #111111;
                font-family: 'Figtree', ui-sans-serif, system-ui, -apple-system, sans-serif;
                display: flex;
                flex-direction: column;
                -webkit-font-smoothing: antialiased;
            }

            .container {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                max-width: 720px;
                margin: 0 auto;
                padding: 48px 24px;
            }

            h1 {
                font-size: clamp(1.75rem, 4.5vw, 2.75rem);
                font-weight: 600;
                line-height: 1.35;
                color: #111111;
                margin: 0 0 48px 0;
            }

            .buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 16px;
                justify-content: center;
                margin-bottom: 36px;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 18px 40px;
                border-radius: 9999px;
                font-weight: 700;
                font-size: 1.0625rem;
                text-decoration: none;
                cursor: pointer;
                border: 2px solid #111111;
                font-family: inherit;
                transition: transform .15s ease, background-color .15s ease, opacity .15s ease;
            }
            .btn:hover { transform: translateY(-1px); }

            .btn-primary {
                background: #111111;
                color: #ffffff;
            }
            .btn-primary:hover { background: #000000; }

            .btn-outline {
                background: #ffffff;
                color: #111111;
            }
            .btn-outline:hover { background: #f4f4f5; }

            .subtext {
                color: #6b7280;
                font-size: 1.0625rem;
            }

            .logout-form { margin-top: 28px; }
            .link-btn {
                background: none;
                border: none;
                color: #6b7280;
                font-size: .9375rem;
                text-decoration: underline;
                cursor: pointer;
                padding: 0;
                font-family: inherit;
            }
            .link-btn:hover { color: #111111; }

            footer {
                padding: 20px 24px;
                text-align: center;
                color: #9ca3af;
                font-size: .8125rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            @auth
                <h1>Selamat datang kembali.</h1>

                <div class="buttons">
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Buka Dashboard</a>
                </div>

                <p class="subtext">Lanjut kelola absensi, cuti, dan gaji tim kamu.</p>

                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="link-btn">Keluar</button>
                </form>
            @else
                <h1>Satu aplikasi buat urus absensi, cuti, dan gaji karyawan.</h1>

                <div class="buttons">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    @endif
                </div>

                <p class="subtext">Cepat diatur · Mudah dipakai · Aman buat tim kamu</p>
            @endauth
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'HRIS') }}. Semua hak cipta dilindungi.</p>
        </footer>
    </body>
</html>