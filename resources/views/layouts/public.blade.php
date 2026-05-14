<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>document.documentElement.classList.add('js-page-transition');</script>
    <title>@yield('title', 'SISFREK SULUT')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.34.1/dist/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --sf-blue-900: #182740;
            --sf-blue-700: #295fb7;
            --sf-blue-500: #4e7cf7;
            --sf-ink: #18253c;
            --sf-soft: #6d7c92;
            --sf-border: #dce5ef;
            --sf-card: #ffffff;
            --sf-bg: linear-gradient(180deg, #eef3f9 0%, #f7f9fc 100%);
            --sf-shadow: 0 24px 56px rgba(24, 39, 64, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--sf-ink);
            background: var(--sf-bg);
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
                radial-gradient(circle at top left, rgba(135, 177, 255, 0.18), transparent 28%),
                linear-gradient(180deg, rgba(238, 243, 249, 0.82) 0%, rgba(247, 249, 252, 0.9) 100%);
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

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Space Grotesk', sans-serif;
        }

        .btn-sf-primary {
            border: 0;
            border-radius: 16px;
            padding: 0.85rem 1.15rem;
            font-weight: 800;
            color: #fff;
            background: #295fb7;
            box-shadow: 0 18px 30px rgba(41, 95, 183, 0.18);
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-sf-primary:hover {
            color: #fff;
            background: #234f9a;
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(41, 95, 183, 0.22);
        }

        .btn-sf-outline {
            border: 1px solid var(--sf-border);
            border-radius: 16px;
            padding: 0.85rem 1.15rem;
            font-weight: 800;
            color: var(--sf-ink);
            background: #fff;
        }

        .btn-sf-outline:hover {
            color: var(--sf-ink);
            background: #f6f9fc;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page-transition-curtain" aria-hidden="true"></div>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    @stack('scripts')
</body>
</html>
