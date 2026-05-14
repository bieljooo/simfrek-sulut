@php($styleMenuOpen = request()->routeIs('admin.settings.style.*'))
@php($websiteStyle = \App\Models\WebsiteStyle::singleton())

<aside class="col-lg-4 col-xl-3 admin-sidebar">
    <div class="admin-sidebar-header">
        <div class="admin-brand">
            <span class="admin-brand-mark {{ $websiteStyle->brandLogoUrl() ? 'is-image' : '' }}" @if ($websiteStyle->brandLogoUrl()) style="{{ $websiteStyle->brandLogoFrameStyle(96, 52) }}" @endif>
                @if ($websiteStyle->brandLogoUrl())
                    <img
                        src="{{ $websiteStyle->brandLogoUrl() }}"
                        alt="Logo SISFREK SULUT"
                        style="width: 100%; height: 100%; object-fit: contain; object-position: {{ $websiteStyle->brandLogoPositionCss() }}; display: block; opacity: {{ $websiteStyle->brandLogoOpacityDecimal() }}"
                    >
                @else
                    <i class="ti ti-wave-sine"></i>
                @endif
            </span>
            <div class="admin-brand-copy">
                <h1>SISFREK SULUT</h1>
                <p>Admin Workspace</p>
            </div>
        </div>
    </div>

    <div class="admin-sidebar-body">
        <div class="admin-nav-label">Menu</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <span class="admin-nav-copy"><i class="ti ti-layout-dashboard"></i><span>Dashboard</span></span>
                <i class="ti ti-chevron-right"></i>
            </a>
            <a href="{{ route('admin.data.index') }}" class="admin-nav-link {{ request()->routeIs('admin.data.*') ? 'is-active' : '' }}">
                <span class="admin-nav-copy"><i class="ti ti-database-import"></i><span>Kelola Data</span></span>
                <i class="ti ti-chevron-right"></i>
            </a>
            <a href="{{ route('home') }}" class="admin-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                <span class="admin-nav-copy"><i class="ti ti-home-2"></i><span>Halaman Utama</span></span>
                <i class="ti ti-chevron-right"></i>
            </a>
        </nav>

        <div class="admin-nav-label">Pengaturan</div>
        <details class="admin-nav-accordion {{ $styleMenuOpen ? 'is-open' : '' }}" @if ($styleMenuOpen) open @endif>
            <summary class="admin-nav-summary">
                <span class="admin-nav-copy"><i class="ti ti-brush"></i><span>Style Website</span></span>
                <i class="ti ti-chevron-down"></i>
            </summary>
            <div class="admin-subnav">
                <a href="{{ route('admin.settings.style.images.edit') }}" class="admin-subnav-link {{ request()->routeIs('admin.settings.style.images.*') ? 'is-active' : '' }}">Setel Gambar</a>
                <a href="{{ route('admin.settings.style.colors.edit') }}" class="admin-subnav-link {{ request()->routeIs('admin.settings.style.colors.*') ? 'is-active' : '' }}">Atur Warna</a>
            </div>
        </details>

        <form action="{{ route('logout') }}" method="POST" class="admin-logout-form">
            @csrf
            <button type="submit" class="admin-nav-button">
                <span class="admin-nav-copy"><i class="ti ti-logout-2"></i><span>Logout</span></span>
                <i class="ti ti-chevron-right"></i>
            </button>
        </form>
    </div>
</aside>