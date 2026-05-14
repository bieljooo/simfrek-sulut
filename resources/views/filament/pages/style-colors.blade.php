<x-filament-panels::page>
    @include('filament.pages.partials.admin-page-styles')

    <div class="sf-admin-page-shell">
        <section class="sf-dashboard-filter-card sf-admin-page-hero">
            <div class="sf-admin-page-copy">
                <span class="sf-dashboard-kicker">Style Website</span>
                <strong>Pengaturan warna utama</strong>
                <p>Halaman ini disiapkan agar skema warna utama tetap satu arah dengan tampilan publik dan admin.</p>
            </div>
            <span class="sf-admin-pill"><x-heroicon-o-swatch /><span>Warna</span></span>
        </section>

        <section class="sf-dashboard-chart-card">
            <div class="sf-admin-card-head">
                <div class="sf-admin-card-copy">
                    <span class="sf-chart-kicker">Placeholder</span>
                    <strong>Segera tersedia</strong>
                    <p>Fokus saat ini tetap pada pengaturan gambar agar tampilan utama dan admin konsisten.</p>
                </div>
                <a href="{{ url('/admin/settings/style/images') }}" class="sf-dashboard-link"><x-heroicon-o-photo /><span>Setel Gambar</span></a>
            </div>
        </section>
    </div>

    @include('filament.partials.sweetalert')
</x-filament-panels::page>