@extends('layouts.admin')

@section('title', 'Atur Warna | Admin SISFREK SULUT')

@push('styles')
<style>
    .placeholder-card {
        padding: 1.35rem;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 28px;
        box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
    }

    .placeholder-card h2 {
        margin: 0 0 0.5rem;
        font-size: 1.15rem;
        font-weight: 800;
        color: #18253c;
    }

    .placeholder-card p {
        margin: 0;
        color: #6d7c92;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 admin-app">
    <div class="row g-0">
        @include('admin.partials.sidebar')

        <main class="col-lg-8 col-xl-9 admin-main">
            <div class="admin-main-shell">
                <section class="admin-hero-card">
                    <div class="admin-hero-copy">
                        <span class="admin-kicker">Pengaturan</span>
                        <h1 class="admin-title">Atur Warna</h1>
                        <p class="admin-subtitle">Ruang ini disiapkan untuk pengaturan warna utama website.</p>
                    </div>
                    <div class="admin-hero-actions">
                        <span class="admin-hero-chip"><i class="ti ti-palette"></i>Style Website</span>
                    </div>
                </section>

                <section class="placeholder-card">
                    <h2>Segera tersedia</h2>
                    <p>Fokus saat ini ada pada pengaturan gambar agar tampilan publik dan admin tetap konsisten.</p>
                </section>
            </div>
        </main>
    </div>
</div>
@endsection
