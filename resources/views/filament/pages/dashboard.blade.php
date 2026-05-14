<x-filament-panels::page>
    <style>
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .sf-dashboard-shell,
        .sf-dashboard-stats,
        .sf-dashboard-panels {
            display: grid;
            gap: 1rem;
        }

        .sf-dashboard-shell {
            grid-template-columns: minmax(0, 1fr);
        }

        .sf-dashboard-stats {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .sf-dashboard-panels {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sf-dashboard-filter-card,
        .sf-dashboard-stat-card,
        .sf-dashboard-chart-card {
            border: 1px solid #dce5ef;
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, #ffffff 100%);
            box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
        }

        .sf-dashboard-filter-card,
        .sf-dashboard-chart-card {
            padding: 1.2rem;
        }

        .sf-dashboard-stat-card {
            padding: 1.15rem;
        }

        .sf-dashboard-filter-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(240px, 320px) auto;
            align-items: end;
            gap: 0.9rem;
        }

        .sf-dashboard-filter-copy {
            display: grid;
            gap: 0.2rem;
        }

        .sf-dashboard-kicker,
        .sf-chart-kicker {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7f8ea2;
        }

        .sf-dashboard-filter-copy strong,
        .sf-dashboard-stat-card h3,
        .sf-dashboard-chart-head h3 {
            font-family: 'Space Grotesk', 'Manrope', sans-serif;
            letter-spacing: -0.04em;
            color: #18253c;
        }

        .sf-dashboard-filter-copy strong {
            font-size: 1rem;
        }

        .sf-dashboard-filter-copy p,
        .sf-dashboard-stat-card p,
        .sf-dashboard-chart-head p {
            margin: 0;
            color: #6d7c92;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .sf-dashboard-filter-field select {
            width: 100%;
            min-height: 54px;
            padding: 0.92rem 1rem;
            border: 1px solid #d7e2ee;
            border-radius: 18px;
            background: #f8fbff;
            color: #18253c;
            font-weight: 700;
            outline: none;
            box-shadow: none;
        }

        .sf-dashboard-filter-field select:focus {
            border-color: rgba(78, 124, 247, 0.48);
            box-shadow: 0 0 0 4px rgba(78, 124, 247, 0.12);
        }

        .sf-dashboard-filter-actions {
            display: inline-flex;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .sf-dashboard-button,
        .sf-dashboard-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-height: 54px;
            padding: 0.88rem 1.1rem;
            border-radius: 18px;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .sf-dashboard-button {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, #182740, #4e7cf7);
            box-shadow: 0 18px 30px rgba(41, 95, 183, 0.18);
        }

        .sf-dashboard-link {
            border: 1px solid #dce5ef;
            color: #22334f;
            background: #fff;
        }

        .sf-dashboard-button:hover,
        .sf-dashboard-link:hover {
            transform: translateY(-1px);
        }

        .sf-dashboard-button svg,
        .sf-dashboard-link svg {
            width: 1rem;
            height: 1rem;
        }

        .sf-dashboard-stat-icon {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 0.95rem;
            color: #fff;
        }

        .sf-dashboard-stat-icon svg {
            width: 1.45rem;
            height: 1.45rem;
        }

        .sf-dashboard-stat-card h3 {
            margin: 0 0 0.14rem;
            font-size: 1.95rem;
            font-weight: 800;
        }

        .sf-dashboard-chart-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.1rem;
        }

        .sf-dashboard-chart-head h3 {
            margin: 0.2rem 0 0;
            font-size: 1.02rem;
            font-weight: 800;
        }

        .sf-chart-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            padding: 0.58rem 0.82rem;
            border-radius: 999px;
            background: #eef5ff;
            color: #295fb7;
            font-size: 0.76rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .sf-chart-badge svg {
            width: 1rem;
            height: 1rem;
        }

        .sf-dashboard-chart-stage {
            position: relative;
            min-height: 320px;
            padding: 0.35rem;
        }

        .sf-dashboard-chart-stage canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .sf-dashboard-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 288px;
            border: 1px dashed #dce5ef;
            border-radius: 22px;
            background: #f8fbff;
            color: #6d7c92;
            font-size: 0.9rem;
            font-weight: 700;
            text-align: center;
        }

        @media (max-width: 1280px) {
            .sf-dashboard-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sf-dashboard-panels {
                grid-template-columns: 1fr;
            }

            .sf-dashboard-filter-form {
                grid-template-columns: 1fr;
                align-items: stretch;
            }
        }

        @media (max-width: 640px) {
            .sf-dashboard-stats {
                grid-template-columns: 1fr;
            }

            .sf-dashboard-chart-head {
                flex-direction: column;
            }

            .sf-dashboard-filter-actions {
                display: grid;
            }
        }
    </style>

    <div class="sf-dashboard-shell">
        <section class="sf-dashboard-filter-card">
            <form action="{{ \App\Filament\Pages\Dashboard::getUrl() }}" method="GET" class="sf-dashboard-filter-form">
                <div class="sf-dashboard-filter-copy">
                    <span class="sf-dashboard-kicker">Filter Kota</span>
                    <strong>{{ $selectedCityLabel }}</strong>
                    <p>Pilih kota lalu klik terapkan untuk memperbarui seluruh ringkasan dashboard.</p>
                </div>

                <label class="sf-dashboard-filter-field" for="sf-dashboard-city">
                    <span class="sr-only">Filter kota dashboard</span>
                    <select name="city" id="sf-dashboard-city" aria-label="Filter kota dashboard">
                        <option value="">Semua kota</option>
                        @foreach ($cityOptions as $cityValue => $cityLabel)
                            <option value="{{ $cityValue }}" @selected($selectedCity === $cityValue)>{{ $cityLabel }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="sf-dashboard-filter-actions">
                    <button type="submit" class="sf-dashboard-button">
                        <x-heroicon-o-adjustments-horizontal />
                        <span>Terapkan</span>
                    </button>
                    <a href="{{ \App\Filament\Pages\Dashboard::getUrl() }}" class="sf-dashboard-link">
                        <x-heroicon-o-arrow-path />
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </section>

        <div class="sf-dashboard-stats">
            <section class="sf-dashboard-stat-card">
                <div class="sf-dashboard-stat-icon" style="background: #295fb7;">
                    <x-heroicon-o-circle-stack />
                </div>
                <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
                <p>Total data</p>
            </section>

            <section class="sf-dashboard-stat-card">
                <div class="sf-dashboard-stat-icon" style="background: #34c38f;">
                    <x-heroicon-o-check-badge />
                </div>
                <h3>{{ number_format($stats['granted'] ?? 0) }}</h3>
                <p>Granted</p>
            </section>

            <section class="sf-dashboard-stat-card">
                <div class="sf-dashboard-stat-icon" style="background: #fb923c;">
                    <x-heroicon-o-exclamation-triangle />
                </div>
                <h3>{{ number_format($stats['denda'] ?? 0) }}</h3>
                <p>Denda</p>
            </section>

            <section class="sf-dashboard-stat-card">
                <div class="sf-dashboard-stat-icon" style="background: #fb7185;">
                    <x-heroicon-o-x-circle />
                </div>
                <h3>{{ number_format($stats['pre_cancel'] ?? 0) }}</h3>
                <p>Pre-cancel</p>
            </section>
        </div>

        <div class="sf-dashboard-panels">
            <section class="sf-dashboard-chart-card">
                <div class="sf-dashboard-chart-head">
                    <div>
                        <span class="sf-chart-kicker">Chart Status</span>
                        <h3>Komposisi Status SIMF</h3>
                        <p>Distribusi status dari dataset aktif pada dashboard.</p>
                    </div>
                    <span class="sf-chart-badge">
                        <x-heroicon-o-chart-pie />
                        <span>Doughnut</span>
                    </span>
                </div>

                <div class="sf-dashboard-chart-stage">
                    @if (($stats['total'] ?? 0) > 0)
                        <canvas id="sf-status-chart"></canvas>
                    @else
                        <div class="sf-dashboard-empty">Belum ada data untuk divisualisasikan.</div>
                    @endif
                </div>
            </section>

            <section class="sf-dashboard-chart-card">
                <div class="sf-dashboard-chart-head">
                    <div>
                        <span class="sf-chart-kicker">Chart Layanan</span>
                        <h3>Distribusi Jenis Layanan</h3>
                        <p>Jenis layanan terbesar dari kota yang sedang dipilih.</p>
                    </div>
                    <span class="sf-chart-badge">
                        <x-heroicon-o-presentation-chart-bar />
                        <span>Bar</span>
                    </span>
                </div>

                <div class="sf-dashboard-chart-stage">
                    @if ($serviceChart !== [])
                        <canvas id="sf-service-chart"></canvas>
                    @else
                        <div class="sf-dashboard-empty">Belum ada data layanan untuk divisualisasikan.</div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    @script
        <script>
            (() => {
                const statusChartPayload = @js($statusChart);
                const serviceChartPayload = @js($serviceChart);
                const chartState = window.__sfDashboardCharts ??= {
                    status: null,
                    service: null,
                    loader: null,
                };

                const ensureChartJs = () => {
                    if (window.Chart) {
                        return Promise.resolve(window.Chart);
                    }

                    if (chartState.loader) {
                        return chartState.loader;
                    }

                    chartState.loader = new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
                        script.onload = () => resolve(window.Chart);
                        script.onerror = reject;
                        document.head.appendChild(script);
                    });

                    return chartState.loader;
                };

                const renderCharts = () => {
                    if (! window.Chart) {
                        return;
                    }

                    Chart.defaults.font.family = 'Manrope, sans-serif';
                    Chart.defaults.color = '#4a5f7d';

                    const statusCanvas = document.getElementById('sf-status-chart');
                    const serviceCanvas = document.getElementById('sf-service-chart');

                    chartState.status?.destroy();
                    chartState.service?.destroy();
                    chartState.status = null;
                    chartState.service = null;

                    const statusEntries = Object.entries(statusChartPayload).filter(([, value]) => Number(value) > 0);

                    if (statusCanvas && statusEntries.length) {
                        chartState.status = new Chart(statusCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: statusEntries.map(([label]) => label),
                                datasets: [{
                                    data: statusEntries.map(([, value]) => value),
                                    backgroundColor: ['#34c38f', '#fb923c', '#f6ad55'],
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

                    const serviceEntries = Object.entries(serviceChartPayload).filter(([, value]) => Number(value) > 0);

                    if (serviceCanvas && serviceEntries.length) {
                        chartState.service = new Chart(serviceCanvas, {
                            type: 'bar',
                            data: {
                                labels: serviceEntries.map(([label]) => label),
                                datasets: [{
                                    data: serviceEntries.map(([, value]) => value),
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
                };

                ensureChartJs().then(renderCharts).catch(() => {});
            })();
        </script>
    @endscript
</x-filament-panels::page>
