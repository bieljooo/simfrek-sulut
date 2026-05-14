@extends('layouts.admin')

@section('title', 'Setel Gambar | Admin SISFREK SULUT')

@php
    $mapBackgroundUrl = $style->mapBackgroundUrl();
    $brandLogoUrl = $style->brandLogoUrl();
    $mapBackgroundImageCss = $mapBackgroundUrl ? "url('{$mapBackgroundUrl}')" : 'none';
@endphp

@push('styles')
<style>
    .style-form {
        display: grid;
        gap: 1rem;
    }

    .style-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .style-card {
        padding: 1.25rem;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 28px;
        box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
    }

    .style-card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .style-card-head h2 {
        margin: 0 0 0.3rem;
        font-size: 1.08rem;
        font-weight: 800;
        color: #18253c;
    }

    .style-card-head p {
        margin: 0;
        color: #6d7c92;
        font-weight: 700;
    }

    .style-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.8rem;
        border-radius: 999px;
        background: #edf4ff;
        color: #295fb7;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .style-preview {
        position: relative;
        min-height: 250px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e4ecf4;
        background:
            radial-gradient(circle at top right, rgba(120, 181, 255, 0.22), transparent 34%),
            linear-gradient(180deg, #eef4fb 0%, #f8fbff 100%);
    }

    .style-preview-map-stage {
        position: relative;
        height: 100%;
        min-height: 218px;
        overflow: hidden;
        border-radius: 22px;
        background:
            linear-gradient(180deg, rgba(17, 35, 66, 0.16), rgba(17, 35, 66, 0.08)),
            url('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/8/100/200');
        background-size: cover;
        background-position: center;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.32);
    }

    .style-preview-map-art {
        position: absolute;
        inset: 0;
        background-position: center;
        background-repeat: no-repeat;
        background-size: 100%;
        pointer-events: none;
        transition:
            background-position 0.32s cubic-bezier(0.22, 1, 0.36, 1),
            background-size 0.32s cubic-bezier(0.22, 1, 0.36, 1),
            opacity 0.24s ease;
    }

    .style-preview-map-copy {
        position: absolute;
        top: 1rem;
        left: 1rem;
        right: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        z-index: 2;
    }

    .style-preview-map-copy span:first-child {
        display: inline-block;
        margin-bottom: 0.22rem;
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.84);
    }

    .style-preview-map-copy strong {
        display: block;
        color: #fff;
        font-size: 1.02rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .style-preview-map-copy em {
        display: inline-flex;
        align-items: center;
        padding: 0.65rem 0.9rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        color: #29415f;
        font-style: normal;
        font-size: 0.8rem;
        font-weight: 800;
        box-shadow: 0 16px 28px rgba(15, 36, 72, 0.18);
    }

    .style-preview-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-height: 218px;
        padding: 1.15rem;
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(243, 247, 252, 0.94));
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.54);
    }

    .style-preview-brand-mark {
        width: 144px;
        height: 84px;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex: 0 0 auto;
        color: #295fb7;
        background: rgba(240, 245, 251, 0.92);
        border: 1px solid #dbe5ef;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
        transition: width 0.32s cubic-bezier(0.22, 1, 0.36, 1), height 0.32s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .style-preview-brand-mark img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        display: block;
        transition: object-position 0.32s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.24s ease;
    }

    .style-preview-brand-mark i {
        width: 100%;
        height: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .style-preview-brand-copy h3 {
        margin: 0 0 0.35rem;
        font-size: 1.45rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: #18253c;
    }

    .style-preview-brand-copy p {
        margin: 0;
        color: #6d7c92;
        font-size: 0.98rem;
        font-weight: 800;
    }

    .style-field {
        display: grid;
        gap: 0.55rem;
        margin-bottom: 0.85rem;
    }

    .style-field span,
    .style-range-head span {
        color: #43556f;
        font-size: 0.84rem;
        font-weight: 800;
    }

    .style-file {
        width: 100%;
        border-radius: 18px;
        padding: 0.9rem 1rem;
        border: 1px dashed #c7d6e6;
        background: #f8fbff;
        color: #22334f;
        font-weight: 700;
    }

    .style-check {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        color: #54657d;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .style-control-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
        margin-top: 0.8rem;
    }

    .style-range {
        display: grid;
        gap: 0.55rem;
        margin-top: 0.8rem;
    }

    .style-range.is-inline {
        margin-top: 0;
    }

    .style-range-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .style-range-head strong {
        color: #295fb7;
        font-size: 0.84rem;
        font-weight: 800;
    }

    .style-range input[type="range"] {
        width: 100%;
        accent-color: #4e7cf7;
    }

    .style-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    @media (max-width: 1199.98px) {
        .style-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .style-card-head,
        .style-preview-map-copy,
        .style-preview-brand {
            flex-direction: column;
            align-items: flex-start;
        }

        .style-preview {
            min-height: 220px;
        }

        .style-control-grid {
            grid-template-columns: 1fr;
        }

        .style-preview-brand-mark {
            width: 100%;
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
                        <span class="admin-kicker">Pengaturan</span>
                        <h1 class="admin-title">Setel Gambar</h1>
                        <p class="admin-subtitle">Atur gambar, opasitas, posisi, dan ukurannya secara langsung.</p>
                    </div>
                    <div class="admin-hero-actions">
                        <span class="admin-hero-chip"><i class="ti ti-brush"></i>Style Website</span>
                    </div>
                </section>

                <form action="{{ route('admin.settings.style.images.update') }}" method="POST" enctype="multipart/form-data" class="style-form">
                    @csrf
                    @method('PUT')

                    <div class="style-grid">
                        <section class="style-card">
                            <div class="style-card-head">
                                <div>
                                    <h2>Background Belakang Map</h2>
                                    <p>Atur posisi dan ukuran akhir gambar latar yang tampil di belakang area peta.</p>
                                </div>
                                <span class="style-badge">Halaman Utama</span>
                            </div>

                            <div class="style-preview">
                                <div class="style-preview-map-stage">
                                    <div
                                        class="style-preview-map-art"
                                        id="mapBackgroundPreview"
                                        data-initial-image="{{ $mapBackgroundUrl ?? '' }}"
                                        style="background-image: {{ $mapBackgroundImageCss }}; opacity: {{ $mapBackgroundUrl ? $style->mapBackgroundOpacityDecimal() : '0' }}; background-position: {{ $style->mapBackgroundPositionCss() }}; background-size: {{ $style->mapBackgroundSizeCss() }};"
                                    ></div>
                                    <div class="style-preview-map-copy">
                                        <div>
                                            <span>Monitoring</span>
                                            <strong>Frekuensi Radio Sulawesi Utara</strong>
                                        </div>
                                        <em>8331 data</em>
                                    </div>
                                </div>
                            </div>

                            <label class="style-field">
                                <span>Upload gambar</span>
                                <input type="file" name="map_background_image" id="mapBackgroundInput" class="style-file" accept=".jpg,.jpeg,.png,.webp">
                            </label>

                            <label class="style-check">
                                <input type="checkbox" name="remove_map_background_image" id="removeMapBackgroundImage" value="1">
                                <span>Hapus gambar yang sedang dipakai</span>
                            </label>

                            <label class="style-range">
                                <div class="style-range-head">
                                    <span>Opasitas gambar</span>
                                    <strong id="mapBackgroundOpacityOutput">{{ old('map_background_opacity', $style->map_background_opacity) }}%</strong>
                                </div>
                                <input type="range" name="map_background_opacity" id="mapBackgroundOpacity" min="0" max="100" value="{{ old('map_background_opacity', $style->map_background_opacity) }}">
                            </label>

                            <div class="style-control-grid">
                                <label class="style-range is-inline">
                                    <div class="style-range-head">
                                        <span>Posisi horizontal</span>
                                        <strong id="mapBackgroundPositionXOutput">{{ old('map_background_position_x', $style->map_background_position_x) }}%</strong>
                                    </div>
                                    <input type="range" name="map_background_position_x" id="mapBackgroundPositionX" min="0" max="100" value="{{ old('map_background_position_x', $style->map_background_position_x) }}">
                                </label>

                                <label class="style-range is-inline">
                                    <div class="style-range-head">
                                        <span>Posisi vertikal</span>
                                        <strong id="mapBackgroundPositionYOutput">{{ old('map_background_position_y', $style->map_background_position_y) }}%</strong>
                                    </div>
                                    <input type="range" name="map_background_position_y" id="mapBackgroundPositionY" min="0" max="100" value="{{ old('map_background_position_y', $style->map_background_position_y) }}">
                                </label>
                            </div>

                            <label class="style-range">
                                <div class="style-range-head">
                                    <span>Ukuran gambar</span>
                                    <strong id="mapBackgroundSizeOutput">{{ old('map_background_size', $style->map_background_size) }}%</strong>
                                </div>
                                <input type="range" name="map_background_size" id="mapBackgroundSize" min="40" max="180" value="{{ old('map_background_size', $style->map_background_size) }}">
                            </label>
                        </section>

                        <section class="style-card">
                            <div class="style-card-head">
                                <div>
                                    <h2>Logo Spektrum</h2>
                                    <p>Atur posisi dan ukuran logo untuk area brand publik dan admin dengan preview langsung.</p>
                                </div>
                                <span class="style-badge">Brand</span>
                            </div>

                            <div class="style-preview">
                                <div class="style-preview-brand">
                                    <div class="style-preview-brand-mark" id="brandLogoFrame" data-base-width="144" data-base-height="84" style="{{ $style->brandLogoFrameStyle(144, 84) }}">
                                        @if ($brandLogoUrl)
                                            <img
                                                src="{{ $brandLogoUrl }}"
                                                alt="Logo preview"
                                                id="brandLogoPreview"
                                                data-initial-src="{{ $brandLogoUrl }}"
                                                style="opacity: {{ $style->brandLogoOpacityDecimal() }}; object-position: {{ $style->brandLogoPositionCss() }};"
                                            >
                                        @else
                                            <i class="ti ti-wave-sine" id="brandLogoFallback" style="font-size: 1.75rem;"></i>
                                            <img
                                                src=""
                                                alt="Logo preview"
                                                id="brandLogoPreview"
                                                data-initial-src=""
                                                style="display: none; opacity: {{ $style->brandLogoOpacityDecimal() }}; object-position: {{ $style->brandLogoPositionCss() }};"
                                            >
                                        @endif
                                        @if ($brandLogoUrl)
                                            <i class="ti ti-wave-sine" id="brandLogoFallback" style="display: none; font-size: 1.75rem;"></i>
                                        @endif
                                    </div>
                                    <div class="style-preview-brand-copy">
                                        <h3>SISFREK SULUT</h3>
                                        <p>Monitoring Spektrum Frekuensi</p>
                                    </div>
                                </div>
                            </div>

                            <label class="style-field">
                                <span>Upload gambar</span>
                                <input type="file" name="brand_logo_image" id="brandLogoInput" class="style-file" accept=".jpg,.jpeg,.png,.webp">
                            </label>

                            <label class="style-check">
                                <input type="checkbox" name="remove_brand_logo_image" id="removeBrandLogoImage" value="1">
                                <span>Hapus logo yang sedang dipakai</span>
                            </label>

                            <label class="style-range">
                                <div class="style-range-head">
                                    <span>Opasitas gambar</span>
                                    <strong id="brandLogoOpacityOutput">{{ old('brand_logo_opacity', $style->brand_logo_opacity) }}%</strong>
                                </div>
                                <input type="range" name="brand_logo_opacity" id="brandLogoOpacity" min="0" max="100" value="{{ old('brand_logo_opacity', $style->brand_logo_opacity) }}">
                            </label>

                            <div class="style-control-grid">
                                <label class="style-range is-inline">
                                    <div class="style-range-head">
                                        <span>Posisi horizontal</span>
                                        <strong id="brandLogoPositionXOutput">{{ old('brand_logo_position_x', $style->brand_logo_position_x) }}%</strong>
                                    </div>
                                    <input type="range" name="brand_logo_position_x" id="brandLogoPositionX" min="0" max="100" value="{{ old('brand_logo_position_x', $style->brand_logo_position_x) }}">
                                </label>

                                <label class="style-range is-inline">
                                    <div class="style-range-head">
                                        <span>Posisi vertikal</span>
                                        <strong id="brandLogoPositionYOutput">{{ old('brand_logo_position_y', $style->brand_logo_position_y) }}%</strong>
                                    </div>
                                    <input type="range" name="brand_logo_position_y" id="brandLogoPositionY" min="0" max="100" value="{{ old('brand_logo_position_y', $style->brand_logo_position_y) }}">
                                </label>
                            </div>

                            <label class="style-range">
                                <div class="style-range-head">
                                    <span>Ukuran gambar</span>
                                    <strong id="brandLogoSizeOutput">{{ old('brand_logo_size', $style->brand_logo_size) }}%</strong>
                                </div>
                                <input type="range" name="brand_logo_size" id="brandLogoSize" min="40" max="180" value="{{ old('brand_logo_size', $style->brand_logo_size) }}">
                            </label>
                        </section>
                    </div>

                    <div class="style-actions">
                        <a href="{{ route('admin.settings.style.colors.edit') }}" class="btn btn-sf-outline"><i class="ti ti-palette"></i>Atur Warna</a>
                        <button type="submit" class="btn btn-sf-primary"><i class="ti ti-device-floppy"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function bindRangeOutput(rangeId, outputId) {
        const range = document.getElementById(rangeId);
        const output = document.getElementById(outputId);

        if (!range || !output) {
            return;
        }

        const sync = () => {
            output.textContent = `${Number(range.value || 0)}%`;
        };

        range.addEventListener('input', sync);
        sync();
    }

    function bindMapBackgroundPreview() {
        const preview = document.getElementById('mapBackgroundPreview');
        const input = document.getElementById('mapBackgroundInput');
        const remove = document.getElementById('removeMapBackgroundImage');
        const opacity = document.getElementById('mapBackgroundOpacity');
        const positionX = document.getElementById('mapBackgroundPositionX');
        const positionY = document.getElementById('mapBackgroundPositionY');
        const size = document.getElementById('mapBackgroundSize');

        if (!preview || !input || !opacity || !positionX || !positionY || !size) {
            return;
        }

        const initialImage = preview.dataset.initialImage || '';
        let objectUrl = '';

        const apply = () => {
            const activeImage = objectUrl || (!remove?.checked ? initialImage : '');
            preview.style.backgroundImage = activeImage ? `url('${activeImage}')` : 'none';
            preview.style.opacity = activeImage ? `${Number(opacity.value || 0) / 100}` : '0';
            preview.style.backgroundPosition = `${Number(positionX.value || 50)}% ${Number(positionY.value || 50)}%`;
            preview.style.backgroundSize = `${Number(size.value || 100)}%`;
        };

        input.addEventListener('change', () => {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = '';
            }

            const [file] = input.files || [];
            if (file) {
                objectUrl = URL.createObjectURL(file);
                if (remove) {
                    remove.checked = false;
                }
            }

            apply();
        });

        remove?.addEventListener('change', apply);
        opacity.addEventListener('input', apply);
        positionX.addEventListener('input', apply);
        positionY.addEventListener('input', apply);
        size.addEventListener('input', apply);
        window.addEventListener('beforeunload', () => objectUrl && URL.revokeObjectURL(objectUrl), { once: true });

        apply();
    }

    function bindBrandLogoPreview() {
        const frame = document.getElementById('brandLogoFrame');
        const preview = document.getElementById('brandLogoPreview');
        const fallback = document.getElementById('brandLogoFallback');
        const input = document.getElementById('brandLogoInput');
        const remove = document.getElementById('removeBrandLogoImage');
        const opacity = document.getElementById('brandLogoOpacity');
        const positionX = document.getElementById('brandLogoPositionX');
        const positionY = document.getElementById('brandLogoPositionY');
        const size = document.getElementById('brandLogoSize');

        if (!frame || !preview || !input || !opacity || !positionX || !positionY || !size) {
            return;
        }

        const initialSource = preview.dataset.initialSrc || '';
        const baseWidth = Number(frame.dataset.baseWidth || 144);
        const baseHeight = Number(frame.dataset.baseHeight || 84);
        let objectUrl = '';

        const apply = () => {
            const activeSource = objectUrl || (!remove?.checked ? initialSource : '');
            const scale = Number(size.value || 100) / 100;

            frame.style.width = `${Math.round(baseWidth * scale)}px`;
            frame.style.height = `${Math.round(baseHeight * scale)}px`;
            preview.style.objectPosition = `${Number(positionX.value || 50)}% ${Number(positionY.value || 50)}%`;

            if (activeSource) {
                preview.src = activeSource;
                preview.style.display = 'block';
                preview.style.opacity = `${Number(opacity.value || 0) / 100}`;
                if (fallback) {
                    fallback.style.display = 'none';
                }
                return;
            }

            preview.removeAttribute('src');
            preview.style.display = 'none';
            if (fallback) {
                fallback.style.display = 'inline-flex';
            }
        };

        input.addEventListener('change', () => {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = '';
            }

            const [file] = input.files || [];
            if (file) {
                objectUrl = URL.createObjectURL(file);
                if (remove) {
                    remove.checked = false;
                }
            }

            apply();
        });

        remove?.addEventListener('change', apply);
        opacity.addEventListener('input', apply);
        positionX.addEventListener('input', apply);
        positionY.addEventListener('input', apply);
        size.addEventListener('input', apply);
        window.addEventListener('beforeunload', () => objectUrl && URL.revokeObjectURL(objectUrl), { once: true });

        apply();
    }

    [
        ['mapBackgroundOpacity', 'mapBackgroundOpacityOutput'],
        ['mapBackgroundPositionX', 'mapBackgroundPositionXOutput'],
        ['mapBackgroundPositionY', 'mapBackgroundPositionYOutput'],
        ['mapBackgroundSize', 'mapBackgroundSizeOutput'],
        ['brandLogoOpacity', 'brandLogoOpacityOutput'],
        ['brandLogoPositionX', 'brandLogoPositionXOutput'],
        ['brandLogoPositionY', 'brandLogoPositionYOutput'],
        ['brandLogoSize', 'brandLogoSizeOutput'],
    ].forEach(([rangeId, outputId]) => bindRangeOutput(rangeId, outputId));

    bindMapBackgroundPreview();
    bindBrandLogoPreview();
</script>
@endpush