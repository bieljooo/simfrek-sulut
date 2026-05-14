<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>document.documentElement.classList.add('js-page-transition');</script>
    <title>@yield('title', 'Admin SIMFREK SULUT')</title>
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
            --sf-blue-300: #78b5ff;
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
            overflow-x: hidden;
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
                linear-gradient(180deg, rgba(238, 243, 249, 0.82) 0%, rgba(247, 249, 252, 0.9) 100%);
            transition: opacity 0.26s ease;
        }

        html.js-page-transition body.page-leaving .page-transition-curtain {
            opacity: 1;
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
            background: linear-gradient(135deg, var(--sf-blue-900), var(--sf-blue-500));
            box-shadow: 0 18px 30px rgba(41, 95, 183, 0.18);
        }

        .btn-sf-primary:hover {
            color: #fff;
            transform: translateY(-1px);
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

        .admin-app,
        .admin-app > .row {
            min-height: 100vh;
        }

        .admin-sidebar {
            background: rgba(255, 255, 255, 0.95);
            border-right: 1px solid rgba(220, 229, 239, 0.9);
            height: 100vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #c4d1df transparent;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #c8d5e2;
            border-radius: 999px;
            border: 2px solid rgba(255, 255, 255, 0.95);
        }

        .admin-sidebar-header {
            position: sticky;
            top: 0;
            z-index: 20;
            padding: 1.15rem 1.15rem 1rem;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(232, 238, 245, 0.95);
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .admin-brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #295fb7, #4e7cf7);
            color: #fff;
            box-shadow: 0 18px 34px rgba(41, 95, 183, 0.2);
            overflow: hidden;
        }

        .admin-brand-mark.is-image {
            width: 96px;
            height: 52px;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .admin-brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
        }

        .admin-brand-copy h1 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: #18253c;
            letter-spacing: -0.03em;
        }

        .admin-brand-copy p {
            margin: 0.15rem 0 0;
            font-size: 0.78rem;
            color: #6d7c92;
            font-weight: 700;
        }

        .admin-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-top: 1rem;
            padding: 0.54rem 0.82rem;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 800;
            color: #1e3150;
            background: #edf4ff;
        }

        .admin-sidebar-body {
            padding: 1.15rem 1.1rem 1.4rem;
        }

        .admin-nav-label {
            margin-bottom: 0.8rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7f8ea2;
        }

        .admin-nav,
        .admin-subnav {
            display: grid;
            gap: 0.45rem;
        }

        .admin-nav {
            margin-bottom: 1rem;
        }

        .admin-nav-link,
        .admin-nav-button,
        .admin-nav-summary,
        .admin-subnav-link {
            width: 100%;
            border: 1px solid transparent;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            transition:
                color 0.24s ease,
                border-color 0.24s ease,
                box-shadow 0.28s ease,
                transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                background 0.24s ease;
        }

        .admin-nav-link,
        .admin-nav-button,
        .admin-nav-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.88rem 0.95rem;
            border-radius: 16px;
            color: #3b4d67;
            background: transparent;
            font-size: 0.9rem;
            font-weight: 800;
        }

        .admin-nav-link::before,
        .admin-nav-button::before,
        .admin-nav-summary::before,
        .admin-subnav-link::before {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: inherit;
            opacity: 0;
            transform: translateX(-16px) scale(0.96);
            background: linear-gradient(135deg, rgba(108, 177, 255, 0.24), rgba(78, 124, 247, 0.12));
            transition: opacity 0.24s ease, transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            z-index: 0;
        }

        .admin-nav-link > *,
        .admin-nav-button > *,
        .admin-nav-summary > * {
            position: relative;
            z-index: 1;
        }

        .admin-nav-copy {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
        }

        .admin-nav-copy i {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #edf4ff;
            color: #295fb7;
            transition:
                transform 0.24s ease,
                background 0.24s ease,
                color 0.24s ease,
                box-shadow 0.24s ease;
        }

        .admin-nav-link > .ti-chevron-right,
        .admin-nav-button > .ti-chevron-right {
            transition: transform 0.24s ease, color 0.24s ease;
        }

        .admin-nav-summary > .ti-chevron-down {
            transition: color 0.24s ease;
        }

        .admin-nav-link:hover,
        .admin-nav-button:hover,
        .admin-nav-summary:hover,
        .admin-subnav-link:hover {
            color: #113b7b;
            background: rgba(255, 255, 255, 0.72);
            border-color: rgba(120, 181, 255, 0.42);
            box-shadow: 0 18px 30px rgba(78, 124, 247, 0.14);
            transform: translateX(5px) translateY(-1px);
        }

        .admin-nav-link:hover::before,
        .admin-nav-button:hover::before,
        .admin-nav-summary:hover::before,
        .admin-subnav-link:hover::before {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .admin-nav-link:hover .admin-nav-copy i,
        .admin-nav-button:hover .admin-nav-copy i,
        .admin-nav-summary:hover .admin-nav-copy i {
            color: #0c58c8;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 14px 24px rgba(78, 124, 247, 0.18);
            transform: translateY(-1px) scale(1.03);
        }

        .admin-nav-link:hover > .ti-chevron-right,
        .admin-nav-button:hover > .ti-chevron-right {
            transform: translateX(3px);
            color: #0c58c8;
        }

        .admin-nav-summary:hover > .ti-chevron-down {
            color: #0c58c8;
        }

        .admin-nav-link.is-active,
        .admin-subnav-link.is-active {
            color: #fff;
            background: linear-gradient(135deg, #295fb7, #4e7cf7);
            border-color: transparent;
            box-shadow: 0 16px 28px rgba(41, 95, 183, 0.2);
        }

        .admin-nav-link.is-active::before,
        .admin-subnav-link.is-active::before {
            display: none;
        }

        .admin-nav-link.is-active .admin-nav-copy i {
            color: #fff;
            background: rgba(255, 255, 255, 0.18);
            box-shadow: none;
        }

        .admin-nav-link.is-active:hover,
        .admin-subnav-link.is-active:hover {
            color: #fff;
            transform: translateX(0);
        }

        .admin-nav-button {
            color: #3b4d67;
        }

        .admin-nav-accordion {
            margin-bottom: 1rem;
            padding: 0.2rem;
            border-radius: 20px;
            background: #f8fbff;
            border: 1px solid #e6edf6;
        }

        .admin-nav-accordion summary {
            list-style: none;
            cursor: pointer;
        }

        .admin-nav-accordion summary::-webkit-details-marker {
            display: none;
        }

        .admin-nav-accordion[open] .ti-chevron-down {
            transform: rotate(180deg);
        }

        .admin-subnav {
            padding: 0.25rem 0.35rem 0.35rem;
        }

        .admin-subnav-link {
            display: block;
            padding: 0.78rem 0.9rem 0.78rem 2.7rem;
            border-radius: 14px;
            color: #43556f;
            font-size: 0.86rem;
            font-weight: 800;
            background: transparent;
        }

        .admin-logout-form {
            margin-top: 0.25rem;
        }

        .admin-main {
            position: relative;
            min-height: 100vh;
            padding: 1.25rem;
            background: linear-gradient(180deg, #edf3fa 0%, #f5f8fc 100%);
        }

        .admin-main-shell {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-height: calc(100vh - 2.5rem);
        }

        .admin-main-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            padding: 0 0.3rem;
        }

        .admin-kicker {
            display: inline-block;
            margin-bottom: 0.35rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7d8ba1;
        }

        .admin-title {
            margin: 0;
            font-size: clamp(1.6rem, 2.5vw, 2.3rem);
            font-weight: 800;
            color: #18253c;
            letter-spacing: -0.04em;
        }

        .admin-subtitle {
            margin: 0.4rem 0 0;
            color: #6d7c92;
            font-weight: 700;
        }

        .admin-actions {
            display: inline-flex;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .admin-hero-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            gap: 1rem;
            padding: 1.2rem;
            border: 1px solid var(--sf-border);
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, #ffffff 100%);
            box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
        }

        .admin-hero-copy {
            display: grid;
            gap: 0.24rem;
        }

        .admin-hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.62rem 0.88rem;
            border-radius: 999px;
            background: #eef5ff;
            color: #295fb7;
            font-size: 0.78rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .admin-hero-chip i {
            font-size: 1rem;
        }

        .admin-hero-actions {
            display: inline-flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                height: auto;
                min-height: auto;
            }

            .admin-main {
                padding: 1rem;
            }

            .admin-main-shell {
                min-height: auto;
            }
        }

        @media (max-width: 575.98px) {
            .admin-sidebar-header,
            .admin-sidebar-body,
            .admin-main {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            .admin-main-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-hero-card {
                grid-template-columns: 1fr;
                align-items: flex-start;
            }

            .admin-hero-actions {
                justify-content: flex-start;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html.js-page-transition body {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .page-transition-curtain,
            .admin-nav-link,
            .admin-nav-button,
            .admin-nav-summary,
            .admin-subnav-link,
            .btn-sf-primary,
            .btn-sf-outline {
                transition: none;
            }
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

