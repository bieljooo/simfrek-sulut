@extends('layouts.admin')

@section('title', 'Kelola Data Admin SISFREK SULUT')

@push('styles')
<style>
    .tool-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 1rem;
    }

    .tool-card,
    .history-card {
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 28px;
        box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
    }

    .tool-card {
        padding: 1.2rem;
    }

    .tool-head {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .tool-head span {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #edf4ff;
        color: #295fb7;
        font-size: 1.1rem;
    }

    .tool-head h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #18253c;
    }

    .tool-actions {
        display: grid;
        gap: 0.75rem;
    }

    .tool-actions .btn,
    .tool-card .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        border-radius: 16px;
        font-weight: 800;
    }

    .import-form .form-control {
        border-radius: 16px;
        padding: 0.95rem 1rem;
        border: 1px dashed #c8d5e2;
        background: #f8fbff;
        box-shadow: none;
    }

    .replace-box {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.85rem 0.95rem;
        border-radius: 16px;
        background: #f7f9fc;
        border: 1px solid #e3ebf4;
        color: #43556f;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .history-card {
        overflow: hidden;
    }

    .history-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #edf2f7;
    }

    .history-head h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #18253c;
    }

    .history-head span {
        color: #6d7c92;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .history-table {
        margin-bottom: 0;
    }

    .history-table thead th {
        font-size: 0.76rem;
        font-weight: 800;
        color: #6d7c92;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: #f8fbff;
        border-bottom-color: #edf2f7;
    }

    .history-table td {
        color: #22334f;
        font-weight: 700;
        border-bottom-color: #edf2f7;
    }

    .history-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .badge-soft {
        padding: 0.4rem 0.7rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .badge-success {
        background: #eef8f0;
        color: #1f7a47;
    }

    .badge-danger {
        background: #fff1f2;
        color: #be123c;
    }

    .badge-warning {
        background: #fff7ed;
        color: #c2410c;
    }

    @media (max-width: 1199.98px) {
        .tool-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .history-head {
            flex-direction: column;
            align-items: flex-start;
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
                <section class="admin-hero-card">
                    <div class="admin-hero-copy">
                        <span class="admin-kicker">Operasional</span>
                        <h1 class="admin-title">Kelola Data</h1>
                        <p class="admin-subtitle">Import, export, template, dan riwayat pembaruan data.</p>
                    </div>
                    <div class="admin-hero-actions">
                        <span class="admin-hero-chip"><i class="ti ti-database-import"></i>Pembaruan Data</span>
                    </div>
                </section>

                <div class="tool-grid">
                    <section class="tool-card import-form">
                        <div class="tool-head">
                            <span><i class="ti ti-file-import"></i></span>
                            <h3>Import Data</h3>
                        </div>
                        <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data" class="tool-actions">
                            @csrf
                            <input type="file" name="file" class="form-control" accept=".csv,.txt,.xlsx,.xls" required>
                            <label class="replace-box">
                                <input type="checkbox" name="replace_existing" value="1">
                                <span>Kosongkan data lama sebelum import</span>
                            </label>
                            <button type="submit" class="btn btn-sf-primary"><i class="ti ti-upload"></i>Upload dan Import</button>
                        </form>
                    </section>

                    <section class="tool-card">
                        <div class="tool-head">
                            <span><i class="ti ti-settings"></i></span>
                            <h3>Aksi Data</h3>
                        </div>
                        <div class="tool-actions">
                            <a href="{{ route('admin.export') }}" class="btn btn-sf-outline"><i class="ti ti-download"></i>Export CSV</a>
                            <a href="{{ route('admin.template') }}" class="btn btn-sf-outline"><i class="ti ti-table-export"></i>Download Template</a>
                            <form action="{{ route('admin.data.destroy') }}" method="POST" data-sweet-confirm="Hapus semua data spektrum?" data-sweet-title="Hapus Semua Data" data-sweet-confirm-button="Ya, hapus">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sf-outline w-100"><i class="ti ti-trash"></i>Hapus Semua Data</button>
                            </form>
                        </div>
                    </section>
                </div>

                <section class="history-card">
                    <div class="history-head">
                        <h3>Riwayat Import</h3>
                        <span>10 data terbaru</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table history-table align-middle">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="py-3">File</th>
                                    <th class="py-3">Total</th>
                                    <th class="py-3">Berhasil</th>
                                    <th class="py-3">Gagal</th>
                                    <th class="py-3">Dilewati</th>
                                    <th class="py-3">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($importHistory as $history)
                                    <tr>
                                        <td class="px-4 py-3">{{ $history->import_date?->translatedFormat('d M Y H:i') }}</td>
                                        <td>{{ $history->file_name }}</td>
                                        <td>{{ number_format($history->total_rows) }}</td>
                                        <td><span class="badge-soft badge-success">{{ number_format($history->success_count) }}</span></td>
                                        <td><span class="badge-soft badge-danger">{{ number_format($history->failed_count) }}</span></td>
                                        <td><span class="badge-soft badge-warning">{{ number_format($history->skipped_count) }}</span></td>
                                        <td>{{ $history->imported_by }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-5 text-center text-secondary">Belum ada riwayat import.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
</div>
@endsection
