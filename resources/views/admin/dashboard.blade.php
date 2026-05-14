@extends('layouts.admin')

@section('title', 'Dashboard Admin SISFREK SULUT')

@php
    $statusChart = [
        'Granted' => $stats['granted'],
        'Denda' => $stats['denda'],
        'Pre Elim' => $stats['pre_elim'],
        'Canceled' => $stats['canceled'],
    ];
    $serviceChart = array_slice($stats['by_service'], 0, 8, true);
@endphp

@push('styles')
<style>
    .dashboard-stats,
    .dashboard-panels {
        display: grid;
        gap: 1rem;
    }

    .dashboard-stats {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .dashboard-panels {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-filter-card,
    .dashboard-stat-card,
    .dashboard-chart-card {
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 28px;
        box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
    }

    .dashboard-filter-card,
    .dashboard-chart-card {
        padding: 1.2rem;
    }

    .dashboard-stat-card {
        padding: 1.15rem;
    }

    .dashboard-filter-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(220px, 320px) auto;
        align-items: end;
        gap: 0.9rem;
    }

    .dashboard-filter-copy {
        display: grid;
        gap: 0.18rem;
    }

    .dashboard-filter-label,
    .chart-kicker {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #7f8ea2;
    }

    .dashboard-filter-copy strong {
        font-size: 1rem;
        font-weight: 800;
        color: #18253c;
        letter-spacing: -0.03em;
    }

    .dashboard-filter-copy p {
        margin: 0;
        color: #6d7c92;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .dashboard-filter-field .form-select {
        min-height: 54px;
        border-radius: 16px;
        border: 1px solid #d7e2ee;
        box-shadow: none;
        font-weight: 700;
        color: #18253c;
    }

    .dashboard-filter-field .form-select:focus {
        border-color: #9cb7ff;
        box-shadow: 0 0 0 0.2rem rgba(41, 95, 183, 0.12);
    }

    .dashboard-filter-actions {
        display: inline-flex;
        gap: 0.7rem;
        flex-wrap: wrap;
    }

    .dashboard-filter-actions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 54px;
        border-radius: 16px;
        font-weight: 800;
    }

    .dashboard-stat-icon {
        width: 52px;
        height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        margin-bottom: 0.95rem;
        color: #fff;
        font-size: 1.2rem;
    }

    .dashboard-stat-card h2 {
        margin: 0 0 0.15rem;
        font-size: 1.95rem;
        font-weight: 800;
        color: #18253c;
        letter-spacing: -0.04em;
    }

    .dashboard-stat-card p {
        margin: 0;
        color: #6d7c92;
        font-weight: 700;
    }

    .dashboard-chart-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .dashboard-chart-head h3 {
        margin: 0.2rem 0 0;
        font-size: 1.02rem;
        font-weight: 800;
        color: #18253c;
    }

    .dashboard-chart-head p {
        margin: 0.24rem 0 0;
        color: #6d7c92;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .chart-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.56rem 0.82rem;
        border-radius: 999px;
        background: #eef5ff;
        color: #295fb7;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .dashboard-chart-stage {
        position: relative;
        min-height: 320px;
        padding: 0.35rem;
    }

    .dashboard-chart-stage canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .dashboard-empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 288px;
        border-radius: 22px;
        background: #f8fbff;
        border: 1px dashed #dce5ef;
        color: #6d7c92;
        font-size: 0.9rem;
        font-weight: 700;
        text-align: center;
    }

    @media (max-width: 1199.98px) {
        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-panels {
            grid-template-columns: 1fr;
        }

        .dashboard-filter-form {
            grid-template-columns: 1fr;
            align-items: stretch;
        }
    }

    @media (max-width: 575.98px) {
        .dashboard-stats {
            grid-template-columns: 1fr;
        }

        .dashboard-chart-head {
            flex-direction: column;
        }

        .dashboard-filter-actions {
            display: grid;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0 admin-app">
    <div class="row g-0">
        @include('admin.partials.sidebar')

        <main class="col-lg-8 col-xl-9 admin-main">
            <div class="admin-main-shell">
                <header class="admin-main-header">
                    <div>
                        <span class="admin-kicker">Analitik</span>
                        <h1 class="admin-title">Dashboard Admin</h1>
                        <p class="admin-subtitle">Ringkasan data {{ $selectedCityLabel }}</p>
                    </div>
                    <div class="admin-actions">
                        <a href="{{ route('admin.data.index') }}" class="btn btn-sf-outline"><i class="ti ti-database-import"></i>Kelola Data</a>
                        <a href="{{ route('home') }}" class="btn btn-sf-primary"><i class="ti ti-map-2"></i>Halaman Utama</a>
                    </div>
                </header>

                <section class="dashboard-filter-card">
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="dashboard-filter-form">
                        <div class="dashboard-filter-copy">
                            <span class="dashboard-filter-label">Filter Kota</span>
                            <strong>{{ $selectedCityLabel }}</strong>
                            <p>Pilih kota untuk menyesuaikan seluruh kartu dan chart pada dashboard.</p>
                        </div>
                        <div class="dashboard-filter-field">
                            <select name="city" class="form-select" aria-label="Filter kota dashboard">
                                <option value="">Semua kota</option>
                                @foreach ($cityOptions as $cityValue => $cityLabel)
                                    <option value="{{ $cityValue }}" @selected($selectedCity === $cityValue)>{{ $cityLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dashboard-filter-actions">
                            <button type="submit" class="btn btn-sf-primary"><i class="ti ti-adjustments-check"></i>Terapkan</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sf-outline"><i class="ti ti-refresh"></i>Reset</a>
                        </div>
                    </form>
                </section>

                <div class="dashboard-stats">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-icon" style="background:linear-gradient(135deg,#182740,#4e7cf7)"><i class="ti ti-database"></i></div>
                        <h2>{{ number_format($stats['total']) }}</h2>
                        <p>Total data</p>
                    </div>
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-icon" style="background:linear-gradient(135deg,#166534,#34c38f)"><i class="ti ti-circle-check"></i></div>
                        <h2>{{ number_format($stats['granted']) }}</h2>
                        <p>Granted</p>
                    </div>
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-icon" style="background:linear-gradient(135deg,#c2410c,#fb923c)"><i class="ti ti-alert-triangle"></i></div>
                        <h2>{{ number_format($stats['denda']) }}</h2>
                        <p>Denda</p>
                    </div>
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-icon" style="background:linear-gradient(135deg,#be123c,#fb7185)"><i class="ti ti-circle-x"></i></div>
                        <h2>{{ number_format($stats['pre_cancel']) }}</h2>
                        <p>Pre-cancel</p>
                    </div>
                </div>

                <div class="dashboard-panels">
                    <section class="dashboard-chart-card">
                        <div class="dashboard-chart-head">
                            <div>
                                <span class="chart-kicker">Chart Status</span>
                                <h3>Komposisi Status SIMF</h3>
                                <p>Distribusi status dari dataset aktif pada dashboard.</p>
                            </div>
                            <span class="chart-badge"><i class="ti ti-chart-donut"></i>Doughnut</span>
                        </div>
                        <div class="dashboard-chart-stage">
                            @if ($stats['total'] > 0)
                                <canvas id="statusChart"></canvas>
                            @else
                                <div class="dashboard-empty-state">Belum ada data untuk divisualisasikan.</div>
                            @endif
                        </div>
                    </section>

                    <section class="dashboard-chart-card">
                        <div class="dashboard-chart-head">
                            <div>
                                <span class="chart-kicker">Chart Layanan</span>
                                <h3>Distribusi Jenis Layanan</h3>
                                <p>Jenis layanan terbesar dari kota yang sedang dipilih.</p>
                            </div>
                            <span class="chart-badge"><i class="ti ti-chart-bar"></i>Bar</span>
                        </div>
                        <div class="dashboard-chart-stage">
                            @if ($serviceChart !== [])
                                <canvas id="serviceChart"></canvas>
                            @else
                                <div class="dashboard-empty-state">Belum ada data layanan untuk divisualisasikan.</div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@if ($stats['total'] > 0 || $serviceChart !== [])
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        Chart.defaults.font.family = 'Manrope, sans-serif';
        Chart.defaults.color = '#4a5f7d';

        const statusChartCanvas = document.getElementById('statusChart');
        const serviceChartCanvas = document.getElementById('serviceChart');

        if (statusChartCanvas) {
            new Chart(statusChartCanvas, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($statusChart)),
                    datasets: [{
                        data: @json(array_values($statusChart)),
                        backgroundColor: ['#34c38f', '#fb923c', '#f6ad55', '#fb7185'],
                        borderColor: '#ffffff',
                        borderWidth: 4,
                        hoverOffset: 8,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 18,
                                font: {
                                    weight: '700',
                                },
                            },
                        },
                    },
                },
            });
        }

        if (serviceChartCanvas) {
            new Chart(serviceChartCanvas, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($serviceChart)),
                    datasets: [{
                        data: @json(array_values($serviceChart)),
                        borderRadius: 12,
                        borderSkipped: false,
                        backgroundColor: ['#295fb7', '#4e7cf7', '#78b5ff', '#0891b2', '#0f766e', '#c2410c', '#d55e00', '#7c3aed'],
                        maxBarThickness: 22,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(220, 229, 239, 0.9)',
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    weight: '700',
                                },
                            },
                        },
                        y: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                font: {
                                    weight: '700',
                                },
                            },
                        },
                    },
                },
            });
        }
    </script>
    @endpush
@endif
