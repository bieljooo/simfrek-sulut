<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>document.documentElement.classList.add('js-page-transition');</script>
    <title>Login Admin SISFREK SULUT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.34.1/dist/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(135, 177, 255, 0.26), transparent 32%),
                linear-gradient(135deg, #182740 0%, #295fb7 48%, #4e7cf7 100%);
        }

        html.js-page-transition body {
            opacity: 0;
            transform: translateY(18px) scale(0.994);
            transition: opacity 0.34s ease, transform 0.42s cubic-bezier(0.22, 1, 0.36, 1);
        }

        html.js-page-transition body.page-ready {
            opacity: 1;
            transform: none;
        }

        html.js-page-transition body.page-leaving {
            opacity: 0;
            transform: translateY(12px) scale(0.992);
        }

        .page-transition-curtain {
            position: fixed;
            inset: 0;
            z-index: 2500;
            pointer-events: none;
            opacity: 0;
            background:
                radial-gradient(circle at top left, rgba(135, 177, 255, 0.16), transparent 28%),
                linear-gradient(135deg, rgba(24, 39, 64, 0.55) 0%, rgba(41, 95, 183, 0.5) 48%, rgba(78, 124, 247, 0.48) 100%);
            transition: opacity 0.26s ease;
        }

        html.js-page-transition body.page-leaving .page-transition-curtain {
            opacity: 1;
        }

        @media (prefers-reduced-motion: reduce) {
            html.js-page-transition body {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .page-transition-curtain {
                display: none;
                transition: none;
            }
        }

        .login-card {
            width: min(100%, 460px);
            padding: 2.15rem;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 28px 72px rgba(24, 39, 64, 0.22);
        }

        .login-badge {
            width: 82px;
            height: 82px;
            margin: 0 auto 1.2rem;
            display: grid;
            place-items: center;
            border-radius: 24px;
            color: #fff;
            font-size: 1.9rem;
            background: linear-gradient(135deg, #182740, #4e7cf7);
            box-shadow: 0 18px 32px rgba(41, 95, 183, 0.2);
        }

        .login-card h1 {
            margin: 0;
            text-align: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.9rem;
            font-weight: 800;
            color: #18253c;
            letter-spacing: -0.04em;
        }

        .login-card p {
            margin: 0.7rem 0 0;
            text-align: center;
            color: #6d7c92;
            line-height: 1.7;
            font-weight: 600;
        }

        .form-control {
            border-radius: 16px;
            padding: 0.92rem 1rem;
            border: 1px solid #d7e2ee;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #9cb7ff;
            box-shadow: 0 0 0 0.2rem rgba(41, 95, 183, 0.12);
        }

        .btn-login {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 0.95rem 1rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #182740, #4e7cf7);
            box-shadow: 0 18px 30px rgba(41, 95, 183, 0.2);
        }
    </style>
</head>
<body>
    <div class="page-transition-curtain" aria-hidden="true"></div>
    <div class="container py-4">
        <div class="mx-auto login-card">
            <div class="login-badge"><i class="ti ti-shield-lock"></i></div>
            <h1>Login Admin</h1>
            <p>Gunakan akun admin untuk masuk ke dashboard.</p>

            <form action="{{ route('login.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username atau Email</label>
                    <input type="text" name="login" value="{{ old('login') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat sesi login</label>
                </div>
                <button type="submit" class="btn-login"><i class="ti ti-login-2 me-2"></i>Masuk ke Dashboard</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-decoration-none fw-semibold">Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.flash-sweetalert')
    <script>
        (() => {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const enterPage = () => requestAnimationFrame(() => document.body.classList.add('page-ready'));

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', enterPage, { once: true });
            } else {
                enterPage();
            }

            window.addEventListener('pageshow', () => {
                document.body.classList.remove('page-leaving');
                document.body.classList.add('page-ready');
            });

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a');

                if (!link || event.defaultPrevented || prefersReducedMotion) return;
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                if (link.target && link.target !== '_self') return;
                if (link.hasAttribute('download')) return;

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return;

                const destination = new URL(link.href, window.location.href);
                if (destination.origin !== window.location.origin) return;
                if (destination.href === window.location.href) return;
                if (document.body.classList.contains('page-leaving')) return;

                event.preventDefault();
                document.body.classList.add('page-leaving');
                window.setTimeout(() => {
                    window.location.href = destination.href;
                }, 220);
            });
        })();
    </script>
</body>
</html>