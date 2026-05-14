@extends('layouts.public')

@section('title', 'Sistem Informasi Spektrum Frekuensi Sulawesi Utara')

@php
    $brandLogoUrl = $websiteStyle->brandLogoUrl();
    $mapBackdropUrl = $websiteStyle->mapBackgroundUrl();
    $mapBannerDefaultUrl = asset('images/map-banner-reference.jpg');
    $mapBannerLeftLogoUrl = asset('images/banner-left-logo-putih.png');
    $mapBannerRightLogoUrl = asset('images/banner-right-logo-putih.png');
    $mapBannerUrl = $mapBackdropUrl ?: $mapBannerDefaultUrl;
    $mapBannerImage = $mapBannerUrl ? "url('{$mapBannerUrl}')" : 'none';
    $mapBannerScale = number_format(max(40, min(180, (int) $websiteStyle->map_background_size)) / 100, 2, '.', '');
@endphp

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    body {
        overflow-x: hidden;
    }

    .sf-shell,
    .sf-shell > .row {
        min-height: 100svh;
    }

    .sf-sidebar {
        background: rgba(255, 255, 255, 0.95);
        border-right: 1px solid rgba(220, 229, 239, 0.9);
        min-height: 100svh;
        max-height: 100svh;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #c4d1df transparent;
        scrollbar-gutter: stable;
    }

    .sf-sidebar::-webkit-scrollbar {
        width: 10px;
    }

    .sf-sidebar::-webkit-scrollbar-thumb {
        background: #c8d5e2;
        border-radius: 999px;
        border: 2px solid rgba(255, 255, 255, 0.95);
    }

    .sf-sidebar-header {
        position: sticky;
        top: 0;
        z-index: 20;
        padding: 1.15rem 1.15rem 1rem;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(18px);
        border-bottom: 1px solid rgba(232, 238, 245, 0.95);
    }

    .sf-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.7rem;
        text-align: center;
    }

    .sf-brand-mark {
        width: 64px;
        height: 64px;
        border-radius: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex: 0 0 auto;
        background: linear-gradient(135deg, #295fb7, #4e7cf7);
        color: #fff;
        box-shadow: 0 18px 34px rgba(41, 95, 183, 0.2);
    }

    .sf-brand-mark.is-image {
        width: 112px;
        height: 68px;
        border-radius: 0;
        background: transparent;
        color: inherit;
        box-shadow: none;
    }

    .sf-brand-mark img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        display: block;
    }

    .sf-brand-copy {
        display: grid;
        gap: 0.24rem;
        min-width: 0;
        justify-items: center;
        text-align: center;
    }

    .sf-brand-heading,
    .sf-panel-title {
        margin: 0;
        letter-spacing: -0.03em;
    }

    .sf-brand-heading {
        display: block;
        line-height: 1;
        text-align: center;
    }

    .sf-brand-heading-primary {
        display: block;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: 1.14rem;
        font-weight: 800;
        letter-spacing: 0.32em;
        padding-left: 0.32em;
        text-transform: uppercase;
        color: #18253c;
    }

    .sf-brand-heading-secondary {
        display: none;
    }

    .sf-brand-tagline {
        margin: 0;
        max-width: 15rem;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: 0.71rem;
        line-height: 1.44;
        letter-spacing: 0.01em;
        color: #6b7b91;
        font-weight: 700;
        text-align: center;
    }

    .sf-brand-agency {
        display: block;
        width: min(100%, 15rem);
        margin-top: 0.18rem;
        text-align: center;
        font-family: 'Manrope', sans-serif;
        font-size: 0.88rem;
        font-weight: 800;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #2f537d;
    }

    .sf-brand-agency span {
        display: none;
    }



    .sf-panel {
        padding: 1.1rem 1.1rem 1.35rem;
    }

    .sf-label {
        margin-bottom: 0.85rem;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #7f8ea2;
    }

    .sf-nav {
        display: grid;
        gap: 0.45rem;
        margin-bottom: 1.1rem;
    }

    .sf-nav-link,
    .sf-nav-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        width: 100%;
        padding: 0.88rem 0.95rem;
        border-radius: 16px;
        background: transparent;
        border: 1px solid transparent;
        color: #3b4d67;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 800;
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

    .sf-nav-link::before,
    .sf-nav-button::before {
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

    .sf-nav-link > *,
    .sf-nav-button > * {
        position: relative;
        z-index: 1;
    }

    .sf-nav-link:hover,
    .sf-nav-button:hover {
        color: #113b7b;
        background: rgba(255, 255, 255, 0.72);
        border-color: rgba(120, 181, 255, 0.42);
        box-shadow: 0 18px 30px rgba(78, 124, 247, 0.14);
        transform: translateX(5px) translateY(-1px);
    }

    .sf-nav-link:hover::before,
    .sf-nav-button:hover::before {
        opacity: 1;
        transform: translateX(0) scale(1);
    }

    .sf-nav-link.is-active {
        color: #fff;
        background: linear-gradient(135deg, #295fb7, #4e7cf7);
        border-color: transparent;
        box-shadow: 0 16px 28px rgba(41, 95, 183, 0.2);
    }

    .sf-nav-link.is-active::before {
        display: none;
    }

    .sf-nav-link-left,
    .sf-nav-button-left {
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
    }

    .sf-nav-link-left i,
    .sf-nav-button-left i {
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

    .sf-nav-link:hover .sf-nav-link-left i,
    .sf-nav-button:hover .sf-nav-button-left i {
        color: #0c58c8;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 14px 24px rgba(78, 124, 247, 0.18);
        transform: translateY(-1px) scale(1.03);
    }

    .sf-nav-link.is-active .sf-nav-link-left i {
        color: #fff;
        background: rgba(255, 255, 255, 0.18);
        box-shadow: none;
    }

    .sf-nav-link > .ti-chevron-right,
    .sf-nav-button > .ti-chevron-right {
        transition: transform 0.24s ease, color 0.24s ease;
    }

    .sf-nav-link:hover > .ti-chevron-right,
    .sf-nav-button:hover > .ti-chevron-right {
        transform: translateX(3px);
        color: #0c58c8;
    }

    .sf-panel-block {
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8fbff;
        border: 1px solid #e6edf6;
        border-radius: 22px;
    }

    .sf-panel-title {
        margin-bottom: 0.85rem;
        font-size: 0.96rem;
        font-weight: 800;
        color: #18253c;
    }

    .sf-field {
        position: relative;
        margin-bottom: 0.85rem;
    }

    .sf-field .ti {
        position: absolute;
        top: 50%;
        left: 0.95rem;
        transform: translateY(-50%);
        color: #8a99af;
        pointer-events: none;
    }

    .sf-field .form-control,
    .sf-field .form-select {
        padding: 0.84rem 0.9rem 0.84rem 2.7rem;
        border-radius: 16px;
        border: 1px solid #d7e2ee;
        background: #fff;
        box-shadow: none;
        font-size: 0.92rem;
        color: #16243a;
    }

    .sf-field .form-control:focus,
    .sf-field .form-select:focus {
        border-color: #9cb7ff;
        box-shadow: 0 0 0 0.2rem rgba(41, 95, 183, 0.12);
    }

    .sf-filter-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.65rem;
    }

    .sf-filter-actions .btn {
        border-radius: 16px;
        font-size: 0.9rem;
        font-weight: 800;
    }

    .sf-loading,
    .sf-filter-info {
        display: none;
        margin-top: 0.8rem;
        padding: 0.85rem 0.95rem;
        border-radius: 16px;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .sf-loading {
        background: #edf4ff;
        color: #46607e;
    }

    .sf-filter-info {
        background: #eef5ff;
        border: 1px solid #dce8fb;
        color: #4a5f7d;
    }

    .sf-loading.show,
    .sf-filter-info.show {
        display: flex;
        align-items: center;
    }

    .sf-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.7rem;
    }

    .sf-summary-card {
        padding: 0.9rem;
        background: #fff;
        border: 1px solid #e1e9f3;
        border-radius: 18px;
    }

    .sf-summary-card span {
        display: block;
        margin-bottom: 0.36rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #7a889e;
    }

    .sf-summary-card strong {
        display: block;
        font-size: 1.34rem;
        font-weight: 800;
        color: #182740;
        letter-spacing: -0.04em;
    }

    .sf-service-list {
        display: grid;
        gap: 0.55rem;
    }

    .sf-service-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        padding: 0.8rem 0.85rem;
        border-radius: 16px;
        background: #fff;
        border: 1px solid #e3ebf4;
    }

    .sf-service-copy {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        min-width: 0;
        color: #21324f;
        font-size: 0.86rem;
        font-weight: 800;
    }

    .sf-service-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        flex: 0 0 auto;
    }

    .sf-service-copy span:last-child {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sf-service-count {
        min-width: 36px;
        padding: 0.35rem 0.55rem;
        border-radius: 999px;
        background: #eef3f9;
        color: #405572;
        text-align: center;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .sf-map-column {
        position: relative;
        min-height: 100svh;
        padding: 0;
        background: #f3f7fb;
        overflow: hidden;
    }

    .sf-map-shell {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        height: 100svh;
        min-height: 100svh;
    }

    .sf-map-column::before,
    .sf-map-column::after {
        display: none;
    }

    .sf-map-banner {
        position: relative;
        height: clamp(68px, 8.6vw, 92px);
        flex: 0 0 auto;
        overflow: hidden;
        border-left: 1px solid #dae4ef;
        background: #dbe6f2;
    }

    .sf-map-banner-art {
        position: absolute;
        inset: 0;
        background-image: var(--sf-map-banner-image);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: var(--sf-map-banner-position);
        opacity: var(--sf-map-banner-opacity);
        transform: scale(var(--sf-map-banner-scale));
        transform-origin: center;
        filter: saturate(0.98) contrast(1.02);
    }

    .sf-map-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(31, 95, 191, 0.54), rgba(58, 125, 238, 0.34) 48%, rgba(219, 230, 242, 0.08));
        pointer-events: none;
    }

    .sf-map-banner-logos {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0 1.15rem 0 1rem;
        pointer-events: none;
    }

    .sf-map-banner-logo {
        display: inline-flex;
        align-items: center;
        min-width: 0;
        opacity: 0.96;
        filter: drop-shadow(0 10px 20px rgba(10, 32, 66, 0.16));
    }

    .sf-map-banner-logo img {
        display: block;
        width: auto;
        max-width: 100%;
        object-fit: contain;
        object-position: center;
    }

    .sf-map-banner-logo.is-left img {
        height: 58px;
    }

    .sf-map-banner-logo.is-right img {
        height: 46px;
    }

    .sf-map-stage {
        position: relative;
        z-index: 1;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
        border-radius: 0;
        border-left: 1px solid #dae4ef;
        background: #d9e8ff;
        box-shadow: none;
    }

    .sf-map-loading {
        position: absolute;
        inset: 0;
        z-index: 650;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.85rem;
        flex-direction: column;
        background: rgba(248, 251, 255, 0.72);
        backdrop-filter: blur(6px);
        color: #18253c;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .sf-map-loading.show {
        opacity: 1;
        visibility: visible;
    }

    .sf-map-loading .spinner-border {
        width: 2.4rem;
        height: 2.4rem;
        color: #295fb7;
    }

    .sf-map-loading strong {
        font-size: 0.92rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .sf-map-loading span {
        color: #6d7c92;
        font-size: 0.84rem;
        font-weight: 700;
    }

    #map {
        position: absolute;
        inset: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
    }

    .leaflet-container {
        width: 100%;
        height: 100%;
        background: #d9e8ff;
        cursor: grab;
    }

    .leaflet-container:active {
        cursor: grabbing;
    }

    .sf-admin-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.72rem 0.95rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        color: #1e3150;
        font-size: 0.82rem;
        font-weight: 800;
        box-shadow: 0 18px 40px rgba(20, 40, 76, 0.14);
    }

    .sf-aggregate-marker {
        background: transparent;
        border: 0;
    }

    .sf-aggregate-marker .sf-aggregate-pill {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 0.7rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #295fb7, #4e7cf7);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 800;
        border: 3px solid rgba(255, 255, 255, 0.92);
        box-shadow: 0 14px 30px rgba(34, 70, 175, 0.24);
        white-space: nowrap;
        transform-origin: center;
        transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        animation: sfAggregateEnter 0.24s cubic-bezier(0.22, 1, 0.36, 1);
        overflow: hidden;
    }

    .sf-aggregate-marker .sf-aggregate-pill::before {
        content: '';
        position: absolute;
        inset: 3px;
        border-radius: inherit;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0));
        pointer-events: none;
    }

    .sf-aggregate-marker .sf-aggregate-pill::after {
        content: '';
        position: absolute;
        inset: -7px;
        border-radius: inherit;
        border: 1px solid rgba(125, 177, 255, 0.24);
        opacity: 0.9;
        pointer-events: none;
        animation: sfAggregatePulse 2.6s ease-out infinite;
    }

    .sf-aggregate-marker .sf-aggregate-count {
        position: relative;
        z-index: 1;
        letter-spacing: -0.02em;
    }

    .sf-aggregate-marker .sf-aggregate-pill.is-medium {
        background: linear-gradient(135deg, #1f57c2, #4e89ff);
        box-shadow: 0 16px 32px rgba(31, 87, 194, 0.28);
    }

    .sf-aggregate-marker .sf-aggregate-pill.is-large {
        background: linear-gradient(135deg, #1149b8, #3a82ff);
        box-shadow: 0 18px 36px rgba(17, 73, 184, 0.34);
    }

    .leaflet-marker-icon.sf-aggregate-marker:hover .sf-aggregate-pill,
    .leaflet-marker-icon.sf-aggregate-marker:focus .sf-aggregate-pill {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 20px 38px rgba(22, 76, 188, 0.34);
        filter: saturate(1.08);
    }

    @keyframes sfAggregateEnter {
        from {
            opacity: 0;
            transform: scale(0.82);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes sfAggregatePulse {
        0%,
        100% {
            transform: scale(0.96);
            opacity: 0.72;
        }

        50% {
            transform: scale(1.06);
            opacity: 0.28;
        }
    }
    .leaflet-popup-content-wrapper,
    .leaflet-popup-tip {
        box-shadow: 0 18px 48px rgba(18, 36, 70, 0.16);
    }

    .sf-popup {
        min-width: 250px;
        font-family: 'Manrope', sans-serif;
        color: #1b2a42;
        line-height: 1.55;
    }

    .sf-popup-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.25rem 0;
        border-bottom: 1px solid #edf2f7;
    }

    .sf-popup-row:last-child {
        border-bottom: 0;
    }

    .sf-popup-row span {
        color: #6c7a90;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .sf-popup-row strong {
        text-align: right;
        font-size: 0.84rem;
        font-weight: 800;
        color: #16243a;
    }

    .sf-empty-state {
        padding: 0.85rem 0.95rem;
        border-radius: 16px;
        background: #fff;
        border: 1px dashed #dce5ef;
        color: #6d7c92;
        font-size: 0.84rem;
        font-weight: 700;
    }

    @media (max-width: 991.98px) {
        .sf-sidebar {
            height: auto;
            min-height: auto;
            max-height: none;
        }

        .sf-map-column {
            padding: 0;
        }

        .sf-map-shell {
            height: auto;
            min-height: auto;
        }

        .sf-map-banner {
            height: 74px;
            border-left: 0;
        }

        .sf-map-banner-logos {
            padding: 0 0.9rem;
        }

        .sf-map-banner-logo.is-left img {
            height: 48px;
        }

        .sf-map-banner-logo.is-right img {
            height: 38px;
        }

        .sf-map-stage {
            min-height: 72vh;
            border-left: 0;
        }
    }

    @media (max-width: 575.98px) {
        .sf-sidebar-header,
        .sf-panel {
            padding-left: 0.9rem;
            padding-right: 0.9rem;
        }

        .sf-summary-grid,
        .sf-filter-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 sf-shell" style="--sf-map-banner-image: {{ $mapBannerImage }}; --sf-map-banner-opacity: {{ $websiteStyle->mapBackgroundOpacityDecimal() }}; --sf-map-banner-position: {{ $websiteStyle->mapBackgroundPositionCss() }}; --sf-map-banner-scale: {{ $mapBannerScale }}; --sf-brand-logo-opacity: {{ $websiteStyle->brandLogoOpacityDecimal() }};">
    <div class="row g-0">
        <aside class="col-lg-4 col-xl-3 sf-sidebar">
            <div class="sf-sidebar-header">
                <div class="sf-brand">
                    <span class="sf-brand-mark {{ $brandLogoUrl ? 'is-image' : '' }}" @if ($brandLogoUrl) style="{{ $websiteStyle->brandLogoFrameStyle(112, 68) }}" @endif>
                        @if ($brandLogoUrl)
                            <img src="{{ $brandLogoUrl }}" alt="Logo SIMFREK SULUT" style="opacity: {{ $websiteStyle->brandLogoOpacityDecimal() }}; object-position: {{ $websiteStyle->brandLogoPositionCss() }};">
                        @else
                            <i class="ti ti-wave-sine"></i>
                        @endif
                    </span>
                    <div class="sf-brand-copy">
                        <h1 class="sf-brand-heading">
                            <span class="sf-brand-heading-primary">SIMFREK</span>
                        </h1>
                        <p class="sf-brand-tagline">Sistem Informasi Monitoring Spektrum Frekuensi Sulut</p>
                        <span class="sf-brand-agency">Balmon Manado</span>
                    </div>
                </div>
            </div>

            <div class="sf-panel">
                <div class="sf-label">Menu</div>
                <div class="sf-nav">
                    <a href="{{ route('home') }}" class="sf-nav-link is-active">
                        <span class="sf-nav-link-left"><i class="ti ti-layout-dashboard"></i><span>Halaman Utama</span></span>
                        <i class="ti ti-chevron-right"></i>
                    </a>
                    <a href="{{ route('contact.index') }}" class="sf-nav-link">
                        <span class="sf-nav-link-left"><i class="ti ti-phone-call"></i><span>Kontak</span></span>
                        <i class="ti ti-chevron-right"></i>
                    </a>
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="sf-nav-link">
                                <span class="sf-nav-link-left"><i class="ti ti-layout-grid"></i><span>Dashboard Admin</span></span>
                                <i class="ti ti-chevron-right"></i>
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="sf-nav-button">
                                    <span class="sf-nav-button-left"><i class="ti ti-logout-2"></i><span>Logout</span></span>
                                    <i class="ti ti-chevron-right"></i>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="sf-nav-link">
                            <span class="sf-nav-link-left"><i class="ti ti-shield-lock"></i><span>Login Admin</span></span>
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    @endauth
                </div>

                <section class="sf-panel-block">
                    <h2 class="sf-panel-title">Pencarian & Filter</h2>
                    <div class="sf-field">
                        <i class="ti ti-search"></i>
                        <input type="text" id="searchUser" class="form-control" placeholder="Cari nama pengguna layanan">
                    </div>
                    <div class="sf-field">
                        <i class="ti ti-broadcast"></i>
                        <select id="serviceType" class="form-select">
                            <option value="">Semua layanan</option>
                            @foreach ($services as $service)
                                <option value="{{ $service }}">{{ $service }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sf-field">
                        <i class="ti ti-circle-check"></i>
                        <select id="statusFilter" class="form-select">
                            <option value="">Semua status</option>
                            <option value="GRANTED">Granted</option>
                            <option value="DENDA">Denda</option>
                            <option value="PRE_CANCEL">Pre Elim Cancelled</option>
                        </select>
                    </div>
                    <div class="sf-field mb-0">
                        <i class="ti ti-map-pin"></i>
                        <select id="cityRegency" class="form-select">
                            <option value="">Semua wilayah</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sf-filter-actions mt-2">
                        <button type="button" class="btn btn-sf-primary" id="applyFilterBtn">Terapkan</button>
                        <button type="button" class="btn btn-sf-outline" id="resetFilterBtn">Reset</button>
                    </div>
                    <div class="sf-loading" id="loadingIndicator">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memuat data...
                    </div>
                    <div class="sf-filter-info" id="filterResultInfo"><span id="filterResultText">Menampilkan semua data</span></div>
                </section>

                <section class="sf-panel-block">
                    <h2 class="sf-panel-title">Ringkasan</h2>
                    <div class="sf-summary-grid">
                        <div class="sf-summary-card"><span>Total data</span><strong id="totalCount">0</strong></div>
                        <div class="sf-summary-card"><span>Granted</span><strong id="grantedCount">0</strong></div>
                        <div class="sf-summary-card"><span>Denda</span><strong id="dendaCount">0</strong></div>
                        <div class="sf-summary-card"><span>Pre-cancel</span><strong id="preCancelCount">0</strong></div>
                    </div>
                </section>

                <section class="sf-panel-block mb-0">
                    <h2 class="sf-panel-title">Jenis Layanan</h2>
                    <div class="sf-service-list" id="serviceSummary">
                        <div class="sf-empty-state">Belum ada data.</div>
                    </div>
                </section>
            </div>
        </aside>

        <main class="col-lg-8 col-xl-9 sf-map-column">
            <div class="sf-map-shell">
                <div class="sf-map-banner">
                    <div class="sf-map-banner-art"></div>
                    <div class="sf-map-banner-logos">
                        <span class="sf-map-banner-logo is-left">
                            <img src="{{ $mapBannerLeftLogoUrl }}" alt="Logo BISA">
                        </span>
                        <span class="sf-map-banner-logo is-right">
                            <img src="{{ $mapBannerRightLogoUrl }}" alt="Logo DJID">
                        </span>
                    </div>
                </div>
                <div class="sf-map-stage">
                    <div class="sf-map-loading show" id="mapLoadingOverlay">
                        <div class="spinner-border" role="status" aria-hidden="true"></div>
                        <strong>Memuat peta</strong>
                        <span>Harap tunggu sebentar</span>
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const spectrumDataUrl = @json(route('api.spectrum.index'));
const spectrumStatsUrl = @json(route('api.spectrum.statistics'));
const sulawesiBounds = [[-6.75, 118.6], [2.85, 126.95]];
const fallbackPalette = ['#4f46e5', '#0891b2', '#0f766e', '#c2410c', '#dc2626', '#7c3aed', '#2563eb'];
const wayback2025Tiles = 'https://wayback.maptiles.arcgis.com/arcgis/rest/services/World_Imagery/WMTS/1.0.0/GoogleMapsCompatible/MapServer/tile/13192/{z}/{y}/{x}';
const liveImageryTiles = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
const renderBatchSize = 240;
const aggregationZoomThreshold = 11;
const largeDatasetThreshold = 700;
const mapMaxZoom = 18;
const liveDetailZoomThreshold = 18;
const serviceColorCache = new Map();

let map = null;
let pointRenderer = null;
let pointsLayer = null;
let archiveTileLayer = null;
let liveDetailTileLayer = null;
let liveDetailLayerHealthy = true;
let allData = [];
let filteredData = [];
let globalStats = null;
let viewportRenderTimer = null;
let suppressViewportRefreshUntil = 0;
let mapRenderToken = 0;

function initMap() {
    map = L.map('map', {
        zoomControl: true,
        minZoom: 6,
        maxZoom: mapMaxZoom,
        inertia: true,
        preferCanvas: true,
        zoomAnimationThreshold: 4,
        fadeAnimation: false,
        markerZoomAnimation: false,
    }).fitBounds(sulawesiBounds, { padding: [44, 44] });

    pointRenderer = L.canvas({ padding: 0.28 });

    archiveTileLayer = L.tileLayer(wayback2025Tiles, {
        minZoom: 6,
        maxZoom: mapMaxZoom,
        maxNativeZoom: 23,
        detectRetina: true,
        noWrap: true,
        updateWhenIdle: true,
        updateWhenZooming: false,
        updateInterval: 180,
        keepBuffer: 1,
        attribution: '&copy; Esri, Maxar, Earthstar Geographics',
    }).addTo(map);

    liveDetailTileLayer = L.tileLayer(liveImageryTiles, {
        minZoom: liveDetailZoomThreshold,
        maxZoom: mapMaxZoom,
        maxNativeZoom: mapMaxZoom,
        detectRetina: true,
        noWrap: true,
        updateWhenIdle: false,
        updateWhenZooming: true,
        updateInterval: 120,
        keepBuffer: 2,
        opacity: 0,
        attribution: '&copy; Esri, Maxar, Earthstar Geographics',
    });

    liveDetailTileLayer.on('tileerror', () => {
        liveDetailLayerHealthy = false;
        syncImageryDetailMode();
    });

    pointsLayer = L.layerGroup().addTo(map);
    map.on('moveend zoomend', handleViewportChange);
    syncImageryDetailMode();

    setTimeout(() => map.invalidateSize(), 150);

    loadData();
    loadStatistics();
}

function showLoading(show) {
    document.getElementById('loadingIndicator').classList.toggle('show', show);
    document.getElementById('mapLoadingOverlay').classList.toggle('show', show);
}

function normalizeString(value) {
    return (value ?? '').toString().trim().toLowerCase();
}

function normalizeUpper(value) {
    return (value ?? '').toString().trim().toUpperCase();
}

function escapeHtml(value) {
    return (value ?? '-')
        .toString()
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function formatDateIndo(value) {
    if (!value) {
        return '-';
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return '-';
    }

    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date);
}

function formatCoordinate(lat, lng) {
    const latitude = Number.parseFloat(lat);
    const longitude = Number.parseFloat(lng);

    if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
        return '-';
    }

    return `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
}

function getServiceColor(serviceType) {
    const normalized = normalizeString(serviceType);

    if (normalized.includes('broadcast')) return '#ff6f61';
    if (normalized.includes('fixed')) return '#0072b2';
    if (normalized.includes('land mobile') && normalized.includes('public')) return '#f0e442';
    if (normalized.includes('land mobile') && normalized.includes('private')) return '#009e73';
    if (normalized.includes('maritim') || normalized.includes('marine')) return '#56b4e9';
    if (normalized.includes('aeronautical')) return '#d55e00';
    if (normalized.includes('satellite')) return '#cc79a7';

    if (!serviceColorCache.has(normalized)) {
        const color = fallbackPalette[serviceColorCache.size % fallbackPalette.length];
        serviceColorCache.set(normalized, color);
    }

    return serviceColorCache.get(normalized);
}

function createPopupContent(item) {
    const rows = [
        ['Pengguna', item.nama],
        ['Layanan', item.jenis_layanan],
        ['Status', item.status],
        ['Kota', item.city],
        ['Alamat', item.stn_address],
        ['Koordinat', formatCoordinate(item.lat, item.lng)],
        ['Frekuensi', item.frekuensi],
        ['Callsign', item.callsign],
        ['Masa Berlaku', formatDateIndo(item.masa_berlaku)],
    ];

    return `<div class="sf-popup">${rows
        .map(([label, value]) => `<div class="sf-popup-row"><span>${label}</span><strong>${escapeHtml(value || '-')}</strong></div>`)
        .join('')}</div>`;
}

function setFilterInfo(message, show = false) {
    const info = document.getElementById('filterResultInfo');
    const text = document.getElementById('filterResultText');

    text.textContent = message;
    info.classList.toggle('show', show);
}

function markFiltersAsPending() {
    if (!allData.length) {
        return;
    }

    setFilterInfo('Filter berubah. Klik Terapkan untuk memperbarui data.', true);
}

function nextFrame() {
    return new Promise((resolve) => window.requestAnimationFrame(() => resolve()));
}

function getPointRadius(zoom = map?.getZoom() ?? 8) {
    if (zoom >= 16) return 6;
    if (zoom >= 13) return 5;

    return 4;
}

function getViewportBounds() {
    return map.getBounds().pad(0.18);
}

function getGridSize(zoom) {
    if (zoom <= 6) return 0.72;
    if (zoom <= 7) return 0.46;
    if (zoom <= 8) return 0.28;
    if (zoom <= 9) return 0.18;
    if (zoom <= 10) return 0.12;

    return 0.08;
}

function syncImageryDetailMode() {
    const useLiveDetail = liveDetailLayerHealthy && map.getZoom() >= liveDetailZoomThreshold;

    if (useLiveDetail) {
        if (!map.hasLayer(liveDetailTileLayer)) {
            liveDetailTileLayer.addTo(map);
        }

        archiveTileLayer.setOpacity(1);
        liveDetailTileLayer.setOpacity(1);

        return;
    }

    archiveTileLayer.setOpacity(1);

    if (map.hasLayer(liveDetailTileLayer)) {
        liveDetailTileLayer.setOpacity(0);
        map.removeLayer(liveDetailTileLayer);
    }
}

function createAggregateIcon(count) {
    const size = count >= 120 ? 52 : count >= 40 ? 46 : 40;
    const tierClass = count >= 120 ? 'is-large' : (count >= 40 ? 'is-medium' : 'is-small');

    return L.divIcon({
        className: 'sf-aggregate-marker',
        html: `<span class="sf-aggregate-pill ${tierClass}" style="min-width:${size}px;height:${size}px;"><span class="sf-aggregate-count">${count}</span></span>`,
        iconSize: [size, size],
        iconAnchor: [size / 2, size / 2],
    });
}

function createAggregateMarker(bucket) {
    const marker = L.marker([bucket.lat, bucket.lng], {
        icon: createAggregateIcon(bucket.count),
        keyboard: false,
        riseOnHover: true,
    });

    marker.on('click', () => {
        suppressViewportRefreshUntil = performance.now() + 380;
        map.fitBounds(bucket.bounds.pad(0.55), { padding: [26, 26], maxZoom: 14 });
    });

    return marker;
}

function createPointMarker(item, radius) {
    const marker = L.circleMarker([item.lat, item.lng], {
        renderer: pointRenderer,
        radius,
        color: 'rgba(255,255,255,0.92)',
        weight: 1.2,
        opacity: 0.94,
        fillColor: getServiceColor(item.jenis_layanan),
        fillOpacity: 0.88,
    });

    let popupInitialized = false;
    marker.on('click', () => {
        if (!popupInitialized) {
            marker.bindPopup(createPopupContent(item), { maxWidth: 320 });
            popupInitialized = true;
        }

        marker.openPopup();
    });

    return marker;
}

function buildDataBounds(data) {
    let bounds = null;

    data.forEach((item) => {
        bounds = bounds ? bounds.extend([item.lat, item.lng]) : L.latLngBounds([item.lat, item.lng], [item.lat, item.lng]);
    });

    return bounds;
}

function buildAggregateBuckets(data, limitToViewport) {
    const buckets = new Map();
    const zoom = map.getZoom();
    const gridSize = getGridSize(zoom);
    const viewportBounds = limitToViewport ? getViewportBounds() : null;

    data.forEach((item) => {
        if (viewportBounds && !viewportBounds.contains([item.lat, item.lng])) {
            return;
        }

        const latKey = Math.floor(item.lat / gridSize);
        const lngKey = Math.floor(item.lng / gridSize);
        const key = `${latKey}:${lngKey}`;
        const bucket = buckets.get(key) ?? {
            count: 0,
            latTotal: 0,
            lngTotal: 0,
            bounds: null,
        };

        bucket.count += 1;
        bucket.latTotal += item.lat;
        bucket.lngTotal += item.lng;
        bucket.bounds = bucket.bounds
            ? bucket.bounds.extend([item.lat, item.lng])
            : L.latLngBounds([item.lat, item.lng], [item.lat, item.lng]);

        buckets.set(key, bucket);
    });

    return [...buckets.values()].map((bucket) => ({
        ...bucket,
        lat: bucket.latTotal / bucket.count,
        lng: bucket.lngTotal / bucket.count,
    }));
}

function getVisiblePoints(data, limitToViewport) {
    if (!limitToViewport) {
        return data;
    }

    const viewportBounds = getViewportBounds();
    return data.filter((item) => viewportBounds.contains([item.lat, item.lng]));
}

async function renderAggregateMarkers(buckets, renderToken) {
    for (let index = 0; index < buckets.length; index += renderBatchSize) {
        if (renderToken !== mapRenderToken) {
            return;
        }

        const batch = buckets.slice(index, index + renderBatchSize);
        batch.forEach((bucket) => pointsLayer.addLayer(createAggregateMarker(bucket)));

        if (index + renderBatchSize < buckets.length) {
            await nextFrame();
        }
    }
}

async function renderPointMarkers(items, renderToken) {
    const radius = getPointRadius();

    for (let index = 0; index < items.length; index += renderBatchSize) {
        if (renderToken !== mapRenderToken) {
            return;
        }

        const batch = items.slice(index, index + renderBatchSize);
        batch.forEach((item) => pointsLayer.addLayer(createPointMarker(item, radius)));

        if (index + renderBatchSize < items.length) {
            await nextFrame();
        }
    }
}

async function updateMapMarkers(data, { fitToData = true, manageLoading = true } = {}) {
    const renderToken = ++mapRenderToken;

    if (manageLoading) {
        showLoading(true);
    }

    pointsLayer.clearLayers();

    if (!data.length) {
        if (fitToData) {
            suppressViewportRefreshUntil = performance.now() + 380;
            map.fitBounds(sulawesiBounds, { padding: [44, 44] });
        }

        if (manageLoading) {
            showLoading(false);
        }

        return;
    }

    const zoom = map.getZoom();
    const limitToViewport = !fitToData;
    const useAggregation = zoom <= aggregationZoomThreshold || (fitToData && data.length > largeDatasetThreshold);
    const dataBounds = fitToData ? buildDataBounds(data) : null;

    if (useAggregation) {
        const buckets = buildAggregateBuckets(data, limitToViewport);
        await renderAggregateMarkers(buckets, renderToken);
    } else {
        const visiblePoints = getVisiblePoints(data, limitToViewport);
        await renderPointMarkers(visiblePoints, renderToken);
    }

    if (renderToken !== mapRenderToken) {
        return;
    }

    if (fitToData && dataBounds) {
        suppressViewportRefreshUntil = performance.now() + 380;
        map.fitBounds(dataBounds.pad(0.18), {
            padding: [48, 48],
            maxZoom: useAggregation ? 10 : 15,
        });
    }

    if (manageLoading) {
        showLoading(false);
    }
}

function renderServiceSummary(serviceCounts) {
    const container = document.getElementById('serviceSummary');
    const entries = Object.entries(serviceCounts || {})
        .filter(([label]) => label)
        .sort((a, b) => b[1] - a[1] || a[0].localeCompare(b[0]));

    if (!entries.length) {
        container.innerHTML = '<div class="sf-empty-state">Belum ada data.</div>';
        return;
    }

    container.innerHTML = entries.map(([label, count]) => {
        const color = getServiceColor(label);
        return `
            <div class="sf-service-row">
                <div class="sf-service-copy">
                    <span class="sf-service-dot" style="background:${color}"></span>
                    <span>${escapeHtml(label)}</span>
                </div>
                <span class="sf-service-count">${count}</span>
            </div>
        `;
    }).join('');
}

function updateStatistics(data) {
    globalStats = data;
    document.getElementById('totalCount').textContent = data.total || 0;
    document.getElementById('grantedCount').textContent = data.granted || 0;
    document.getElementById('dendaCount').textContent = data.denda || 0;
    document.getElementById('preCancelCount').textContent = data.pre_cancel || 0;
    const mapDataTotal = document.getElementById('mapDataTotal');
    if (mapDataTotal) {
        mapDataTotal.textContent = `${data.total || 0} data`;
    }
    renderServiceSummary(data.by_service || {});
}

function updateFilteredStatistics(data) {
    const summary = {
        total: data.length,
        granted: 0,
        denda: 0,
        pre_elim: 0,
        canceled: 0,
        pre_cancel: 0,
        by_service: {},
    };

    data.forEach((item) => {
        const status = normalizeUpper(item.status);
        const service = item.jenis_layanan || '-';

        if (status.includes('GRANTED')) summary.granted += 1;
        else if (status.includes('DENDA')) summary.denda += 1;
        else if (status.includes('PRELIM')) summary.pre_elim += 1;
        else if (status.includes('CANCEL')) summary.canceled += 1;

        summary.by_service[service] = (summary.by_service[service] || 0) + 1;
    });

    summary.pre_cancel = summary.pre_elim + summary.canceled;
    updateStatistics(summary);
}

function hydrateFilterOptions(data) {
    const serviceSelect = document.getElementById('serviceType');
    const citySelect = document.getElementById('cityRegency');
    const currentService = serviceSelect.value;
    const currentCity = citySelect.value;

    const services = [...new Set(data.map((item) => (item.jenis_layanan ?? '').trim()).filter(Boolean))].sort((a, b) => a.localeCompare(b));
    const cities = [...new Set(data.map((item) => (item.city ?? '').trim()).filter(Boolean))].sort((a, b) => a.localeCompare(b));

    serviceSelect.innerHTML = '<option value="">Semua layanan</option>' + services.map((service) => `<option value="${escapeHtml(service)}">${escapeHtml(service)}</option>`).join('');
    citySelect.innerHTML = '<option value="">Semua wilayah</option>' + cities.map((city) => `<option value="${escapeHtml(city)}">${escapeHtml(city)}</option>`).join('');

    serviceSelect.value = currentService;
    citySelect.value = currentCity;
}

function handleViewportChange() {
    syncImageryDetailMode();

    if (performance.now() < suppressViewportRefreshUntil) {
        return;
    }

    clearTimeout(viewportRenderTimer);
    viewportRenderTimer = window.setTimeout(() => {
        void updateMapMarkers(filteredData, { fitToData: false, manageLoading: false });
    }, 80);
}

async function applyFilters() {
    const searchTerm = normalizeString(document.getElementById('searchUser').value);
    const serviceType = normalizeString(document.getElementById('serviceType').value);
    const status = normalizeUpper(document.getElementById('statusFilter').value);
    const city = normalizeString(document.getElementById('cityRegency').value);

    filteredData = allData.filter((item) => {
        if (searchTerm && !normalizeString(item.nama).includes(searchTerm)) {
            return false;
        }

        if (serviceType && normalizeString(item.jenis_layanan) !== serviceType) {
            return false;
        }

        if (status) {
            const normalizedStatus = normalizeString(item.status).toUpperCase();
            if (status === 'GRANTED' && !normalizedStatus.includes('GRANTED')) return false;
            if (status === 'DENDA' && !normalizedStatus.includes('DENDA')) return false;
            if (status === 'PRE_CANCEL' && !normalizedStatus.includes('PRELIM') && !normalizedStatus.includes('CANCEL')) return false;
        }

        if (city && normalizeString(item.city) !== city) {
            return false;
        }

        return true;
    });

    await updateMapMarkers(filteredData);
    updateFilteredStatistics(filteredData);
    setFilterInfo(filteredData.length === allData.length ? `Menampilkan semua data (${allData.length})` : `Menampilkan ${filteredData.length} dari ${allData.length} data`, filteredData.length !== allData.length || allData.length === 0);
}

async function resetFilters() {
    document.getElementById('searchUser').value = '';
    document.getElementById('serviceType').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('cityRegency').value = '';
    filteredData = [...allData];
    await updateMapMarkers(filteredData);

    if (globalStats) {
        updateStatistics(globalStats);
    } else {
        updateFilteredStatistics(filteredData);
    }

    setFilterInfo(allData.length ? `Menampilkan semua data (${allData.length})` : 'Belum ada data untuk ditampilkan.', allData.length === 0);
}

async function loadData() {
    showLoading(true);

    try {
        const response = await fetch(spectrumDataUrl);
        const result = await response.json();

        if (!result.success) {
            throw new Error('Data tidak tersedia.');
        }

        allData = (result.data || []).map((item) => ({
            ...item,
            lat: Number.parseFloat(item.lat),
            lng: Number.parseFloat(item.lng),
        })).filter((item) => Number.isFinite(item.lat) && Number.isFinite(item.lng));
        filteredData = [...allData];
        hydrateFilterOptions(allData);
        await updateMapMarkers(allData, { manageLoading: false });
        updateFilteredStatistics(allData);
        setFilterInfo(allData.length ? `Menampilkan semua data (${allData.length})` : 'Belum ada data untuk ditampilkan.', allData.length === 0);
    } catch (error) {
        allData = [];
        filteredData = [];
        hydrateFilterOptions([]);
        await updateMapMarkers([], { manageLoading: false });
        renderServiceSummary({});
        setFilterInfo('Gagal memuat data.', true);
    } finally {
        showLoading(false);
    }
}

async function loadStatistics() {
    try {
        const response = await fetch(spectrumStatsUrl);
        const result = await response.json();

        if (result.success) {
            globalStats = result.data || null;

            if (filteredData.length === allData.length) {
                updateStatistics(result.data || {});
            }
        }
    } catch (error) {
        if (allData.length) {
            updateFilteredStatistics(allData);
        }
    }
}

document.getElementById('searchUser').addEventListener('input', markFiltersAsPending);
document.getElementById('serviceType').addEventListener('change', markFiltersAsPending);
document.getElementById('statusFilter').addEventListener('change', markFiltersAsPending);
document.getElementById('cityRegency').addEventListener('change', markFiltersAsPending);
document.getElementById('applyFilterBtn').addEventListener('click', () => {
    void applyFilters();
});
document.getElementById('resetFilterBtn').addEventListener('click', () => {
    void resetFilters();
});
window.addEventListener('load', initMap);
window.addEventListener('resize', () => {
    if (map) {
        setTimeout(() => map.invalidateSize(), 120);
    }
});
</script>
@endpush
