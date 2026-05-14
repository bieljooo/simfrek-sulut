<x-filament-panels::page>
    @include('filament.pages.partials.admin-page-styles')

    @php($importHistory = $this->importHistory)

    <div class="sf-admin-page-shell">
        <section class="sf-dashboard-filter-card sf-admin-page-hero">
            <div class="sf-admin-page-copy">
                <span class="sf-dashboard-kicker">Operasional</span>
                <strong>Pembaruan dataset spektrum</strong>
                <p>Import periodik, export CSV, template, dan riwayat 10 update terakhir dalam satu tampilan.</p>
            </div>
            <span class="sf-admin-pill">
                <x-heroicon-o-circle-stack />
                <span>Data Spektrum</span>
            </span>
        </section>

        <div class="sf-admin-tool-grid">
            <section class="sf-dashboard-chart-card">
                <div class="sf-admin-card-head">
                    <div class="sf-admin-card-copy">
                        <span class="sf-chart-kicker">Import</span>
                        <strong>Upload Data Baru</strong>
                        <p>Gunakan file CSV, TXT, XLSX, atau XLS dengan field yang sama seperti template.</p>
                    </div>
                    <span class="sf-admin-pill">
                        <x-heroicon-o-arrow-up-tray />
                        <span>Periode Baru</span>
                    </span>
                </div>

                <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data" class="sf-admin-tool-actions">
                    @csrf
                    <input type="file" name="file" class="sf-admin-upload-input" accept=".csv,.txt,.xlsx,.xls" required>
                    <label class="sf-admin-check">
                        <input type="checkbox" name="replace_existing" value="1">
                        <span>Kosongkan data lama sebelum import</span>
                    </label>
                    <button type="submit" class="sf-dashboard-button">
                        <x-heroicon-o-cloud-arrow-up />
                        <span>Upload dan Import</span>
                    </button>
                </form>
            </section>

            <section class="sf-dashboard-chart-card">
                <div class="sf-admin-card-head">
                    <div class="sf-admin-card-copy">
                        <span class="sf-chart-kicker">Aksi</span>
                        <strong>Utilitas Data</strong>
                        <p>Export data aktif, ambil template, atau hapus seluruh dataset jika diperlukan.</p>
                    </div>
                    <span class="sf-admin-pill">
                        <x-heroicon-o-wrench-screwdriver />
                        <span>Tools</span>
                    </span>
                </div>

                <div class="sf-admin-tool-actions">
                    <a href="{{ route('admin.export') }}" class="sf-dashboard-link">
                        <x-heroicon-o-arrow-down-tray />
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('admin.template') }}" class="sf-dashboard-link">
                        <x-heroicon-o-document-arrow-down />
                        <span>Download Template</span>
                    </a>
                    <form action="{{ route('admin.data.destroy') }}" method="POST" data-sweet-confirm="Hapus semua data spektrum?" data-sweet-title="Hapus Semua Data" data-sweet-confirm-button="Ya, hapus">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="sf-dashboard-link" style="width: 100%;">
                            <x-heroicon-o-trash />
                            <span>Hapus Semua Data</span>
                        </button>
                    </form>
                </div>
            </section>
        </div>

        <section class="sf-dashboard-chart-card">
            <div class="sf-admin-table-head">
                <div class="sf-admin-table-head-copy">
                    <span class="sf-chart-kicker">Riwayat Import</span>
                    <strong>10 data terbaru</strong>
                    <p>Ringkasan file yang sudah diimport ke sistem.</p>
                </div>
                <span class="sf-admin-pill">
                    <x-heroicon-o-clock />
                    <span>Terbaru</span>
                </span>
            </div>

            @if ($importHistory->isEmpty())
                <div class="sf-admin-empty">Belum ada riwayat import.</div>
            @else
                <div class="sf-admin-table-wrap">
                    <table class="sf-admin-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>File</th>
                                <th>Total</th>
                                <th>Berhasil</th>
                                <th>Gagal</th>
                                <th>Dilewati</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($importHistory as $history)
                                <tr>
                                    <td>{{ $history->import_date?->translatedFormat('d M Y H:i') }}</td>
                                    <td>{{ $history->file_name }}</td>
                                    <td>{{ number_format($history->total_rows) }}</td>
                                    <td><span class="sf-admin-badge-soft is-success">{{ number_format($history->success_count) }}</span></td>
                                    <td><span class="sf-admin-badge-soft is-danger">{{ number_format($history->failed_count) }}</span></td>
                                    <td><span class="sf-admin-badge-soft is-warning">{{ number_format($history->skipped_count) }}</span></td>
                                    <td>{{ $history->imported_by }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    @include('filament.partials.sweetalert')
</x-filament-panels::page>