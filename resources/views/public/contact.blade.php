@extends('layouts.public')

@section('title', 'Kontak UPT Monitor Frekuensi Sulawesi Utara')

@push('styles')
<style>
    .contact-hero {
        padding: 1.4rem 0 4.6rem;
        color: #fff;
        background:
            radial-gradient(circle at top right, rgba(135, 177, 255, 0.28), transparent 30%),
            linear-gradient(135deg, #182740 0%, #295fb7 48%, #4e7cf7 100%);
    }

    .contact-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .contact-nav a.link-back {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        color: #fff;
        text-decoration: none;
        font-weight: 800;
    }

    .contact-nav .btn-sf-outline {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.12);
    }

    .contact-nav .btn-sf-outline:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.18);
    }

    .contact-title h1 {
        margin: 0;
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 800;
        letter-spacing: -0.04em;
    }

    .contact-title p {
        margin: 0.85rem 0 0;
        max-width: 720px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.7;
        font-weight: 600;
    }

    .contact-wrap {
        margin-top: -2.8rem;
        padding-bottom: 3rem;
    }

    .contact-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 28px;
        box-shadow: 0 26px 64px rgba(24, 39, 64, 0.12);
    }

    .contact-side {
        height: 100%;
        padding: 2rem;
        color: #fff;
        background: linear-gradient(180deg, #182740 0%, #295fb7 100%);
    }

    .contact-side h2 {
        margin: 0 0 0.7rem;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .contact-side p {
        margin: 0 0 1.3rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.7;
        font-weight: 600;
    }

    .contact-item {
        display: flex;
        gap: 0.8rem;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .contact-item i {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 1rem;
    }

    .contact-item strong {
        display: block;
        margin-bottom: 0.1rem;
    }

    .contact-form {
        padding: 2rem;
    }

    .contact-form .form-control,
    .contact-form .form-select,
    .contact-form textarea {
        border-radius: 16px;
        padding: 0.92rem 1rem;
        border: 1px solid #d7e2ee;
        box-shadow: none;
    }

    .contact-form .form-control:focus,
    .contact-form .form-select:focus,
    .contact-form textarea:focus {
        border-color: #9cb7ff;
        box-shadow: 0 0 0 0.2rem rgba(41, 95, 183, 0.12);
    }

    .contact-form h3 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 800;
        color: #18253c;
    }

    .contact-form p {
        margin: 0.45rem 0 0;
        color: #6d7c92;
        font-weight: 700;
    }

    .contact-map {
        width: 100%;
        min-height: 340px;
        border: 0;
    }

    @media (max-width: 767.98px) {
        .contact-nav {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<section class="contact-hero">
    <div class="container">
        <div class="contact-nav">
            <a href="{{ route('home') }}" class="link-back"><i class="ti ti-arrow-left"></i>Kembali ke Peta</a>
            <div class="d-flex gap-2 flex-wrap">
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sf-outline"><i class="ti ti-layout-grid me-2"></i>Dashboard Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-sf-outline"><i class="ti ti-shield-lock me-2"></i>Login Admin</a>
                @endauth
            </div>
        </div>

        <div class="contact-title">
            <h1>Kontak Balai Monitor SFR Kelas II Manado</h1>
            <p>Kirim pertanyaan, kebutuhan layanan, atau pengaduan melalui formulir berikut.</p>
        </div>
    </div>
</section>

<section class="contact-wrap">
    <div class="container">


        <div class="contact-card">
            <div class="row g-0">
                <div class="col-lg-4">
                    <div class="contact-side">
                        <h2>Informasi Kantor</h2>
                        <p>UPT monitor frekuensi radio wilayah Manado.</p>

                        <div class="contact-item">
                            <i class="ti ti-mail"></i>
                            <div>
                                <strong>Email</strong>
                                <div>upt_manado@postel.go.id</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="ti ti-phone"></i>
                            <div>
                                <strong>Telepon</strong>
                                <div>(0431) 821511</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="ti ti-map-pin"></i>
                            <div>
                                <strong>Alamat</strong>
                                <div>Jl. Raya Manado - Tomohon KM. 8, Pineleng Satu, Minahasa</div>
                            </div>
                        </div>
                        <div class="contact-item mb-0">
                            <i class="ti ti-clock-hour-8"></i>
                            <div>
                                <strong>Jam Layanan</strong>
                                <div>Senin - Jumat, 08:00 - 16:00 WITA</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact-form">
                        <h3>Kirim Pesan</h3>
                        <p>Isi data di bawah ini.</p>

                        <form action="{{ route('contact.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Depan *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Belakang</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Telepon</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Subjek</label>
                                    <select name="subject" class="form-select">
                                        <option value="">Pilih topik</option>
                                        <option value="Informasi Layanan" @selected(old('subject') === 'Informasi Layanan')>Informasi Layanan</option>
                                        <option value="Perizinan Frekuensi" @selected(old('subject') === 'Perizinan Frekuensi')>Perizinan Frekuensi</option>
                                        <option value="Pengaduan" @selected(old('subject') === 'Pengaduan')>Pengaduan</option>
                                        <option value="Lainnya" @selected(old('subject') === 'Lainnya')>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pesan *</label>
                                    <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sf-primary"><i class="ti ti-send-2 me-2"></i>Kirim Pesan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-card mt-4 overflow-hidden">
            <iframe class="contact-map" loading="lazy" allowfullscreen src="https://www.google.com/maps?q=Balai%20Monitor%20Spectrum%20Frekuensi%20Radio%20Kelas%20II%20Manado&output=embed"></iframe>
        </div>
    </div>
</section>
@endsection
