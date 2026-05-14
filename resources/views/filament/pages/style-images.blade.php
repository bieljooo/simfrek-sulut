<x-filament-panels::page>
    @include('filament.pages.partials.admin-page-styles')

    @php
        $style = $this->style;
        $mapBackgroundUrl = $style->mapBackgroundUrl();
        $mapBannerDefaultUrl = asset('images/map-banner-reference.jpg');
        $mapPreviewImageUrl = $mapBackgroundUrl ?: $mapBannerDefaultUrl;
        $brandLogoUrl = $style->brandLogoUrl();
        $mapBackgroundImageCss = $mapPreviewImageUrl ? "url('{$mapPreviewImageUrl}')" : 'none';
        $mapBackgroundScale = number_format(max(40, min(180, (int) $style->map_background_size)) / 100, 2, '.', '');
    @endphp

    <style>
        .sf-admin-style-card { padding: 1.25rem; }
        .sf-admin-style-preview {
            position: relative; min-height: 250px; padding: 1rem; margin-bottom: 1rem; border-radius: 24px;
            overflow: hidden; border: 1px solid #e4ecf4;
            background: radial-gradient(circle at top right, rgba(120, 181, 255, 0.22), transparent 34%), linear-gradient(180deg, #eef4fb 0%, #f8fbff 100%);
        }
        .sf-admin-style-map-stage {
            position: relative; min-height: 218px; aspect-ratio: 1042 / 353; overflow: hidden; border-radius: 22px;
            background: linear-gradient(180deg, rgba(17, 35, 66, 0.22), rgba(17, 35, 66, 0.08));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.32);
        }
        .sf-admin-style-map-art {
            position: absolute; inset: 0; background-position: center; background-repeat: no-repeat; background-size: cover; pointer-events: none;
            transform: scale(1); transform-origin: center;
            transition: background-position 0.32s cubic-bezier(0.22, 1, 0.36, 1), transform 0.32s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.24s ease;
        }
        .sf-admin-style-map-copy {
            position: absolute; top: 1rem; left: 1rem; right: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; z-index: 2;
        }
        .sf-admin-style-map-copy span:first-child {
            display: inline-block; margin-bottom: 0.22rem; font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase; font-weight: 800; color: rgba(255, 255, 255, 0.84);
        }
        .sf-admin-style-map-copy strong { display: block; color: #fff; font-size: 1.02rem; font-weight: 800; letter-spacing: -0.03em; }
        .sf-admin-style-map-copy em {
            display: inline-flex; align-items: center; padding: 0.65rem 0.9rem; border-radius: 999px; background: rgba(255, 255, 255, 0.92); color: #29415f;
            font-style: normal; font-size: 0.8rem; font-weight: 800; box-shadow: 0 16px 28px rgba(15, 36, 72, 0.18);
        }
        .sf-admin-style-brand {
            display: flex; align-items: center; gap: 1rem; min-height: 218px; padding: 1.15rem; border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(243, 247, 252, 0.94)); box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.54);
        }
        .sf-admin-style-brand-mark {
            width: 144px; height: 84px; border-radius: 24px; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; flex: 0 0 auto;
            color: #295fb7; background: rgba(240, 245, 251, 0.92); border: 1px solid #dbe5ef; box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
            transition: width 0.32s cubic-bezier(0.22, 1, 0.36, 1), height 0.32s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .sf-admin-style-brand-mark img {
            width: 100%; height: 100%; object-fit: contain; object-position: center; display: block;
            transition: object-position 0.32s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.24s ease;
        }
        .sf-admin-fallback-icon { width: 100%; height: 100%; display: inline-flex; align-items: center; justify-content: center; }
        .sf-admin-fallback-icon svg { width: 1.75rem; height: 1.75rem; }
        .sf-admin-style-brand-copy h3 {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 0.4rem;
            margin: 0 0 0.35rem;
        }
        .sf-admin-style-brand-copy h3 span:first-child {
            font-family: 'Space Grotesk', 'Manrope', sans-serif;
            font-size: 1.38rem;
            font-weight: 700;
            letter-spacing: -0.05em;
            color: #18253c;
        }
        .sf-admin-style-brand-copy h3 span:last-child {
            font-family: 'Manrope', sans-serif;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #295fb7;
        }
        .sf-admin-style-brand-copy p {
            margin: 0;
            color: #6d7c92;
            font-size: 0.88rem;
            line-height: 1.45;
            font-weight: 800;
        }
        .sf-admin-style-brand-copy .sf-admin-style-brand-agency {
            display: block;
            margin-top: 0.42rem;
            font-family: 'Space Grotesk', 'Manrope', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #2f537d;
        }
        .sf-admin-style-field, .sf-admin-style-range { display: grid; gap: 0.55rem; }
        .sf-admin-style-field { margin-bottom: 0.85rem; }
        .sf-admin-style-field span, .sf-admin-style-range-head span { color: #43556f; font-size: 0.84rem; font-weight: 800; }
        .sf-admin-style-range { margin-top: 0.8rem; }
        .sf-admin-style-range-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .sf-admin-style-range-head strong { color: #295fb7; font-size: 0.84rem; font-weight: 800; }
        .sf-admin-style-range input[type="range"] { width: 100%; accent-color: #4e7cf7; }
        .sf-admin-style-control-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem; margin-top: 0.8rem; }
        .sf-admin-style-actions { grid-template-columns: repeat(2, max-content); justify-content: end; }
        @media (max-width: 640px) {
            .sf-admin-style-map-copy, .sf-admin-style-brand, .sf-admin-style-card-head { flex-direction: column; align-items: flex-start; }
            .sf-admin-style-control-grid, .sf-admin-style-actions { grid-template-columns: 1fr; }
            .sf-admin-style-brand-mark { width: 100%; }
        }
    </style>

    <div class="sf-admin-page-shell">
        <section class="sf-dashboard-filter-card sf-admin-page-hero">
            <div class="sf-admin-page-copy">
                <span class="sf-dashboard-kicker">Style Website</span>
                <strong>Atur aset visual utama</strong>
                <p>Gambar map dan logo bisa disetel langsung dengan preview posisi, opasitas, dan ukuran.</p>
            </div>
            <span class="sf-admin-pill"><x-heroicon-o-swatch /><span>Visual</span></span>
        </section>

        <form action="{{ route('admin.settings.style.images.update') }}" method="POST" enctype="multipart/form-data" class="sf-admin-style-form">
            @csrf
            @method('PUT')

            <div class="sf-admin-style-grid">
                <section class="sf-dashboard-chart-card sf-admin-style-card">
                    <div class="sf-admin-style-card-head">
                        <div class="sf-admin-style-copy">
                            <span class="sf-chart-kicker">Halaman Utama</span>
                            <h3>Banner Atas Map</h3>
                            <p>Atur gambar banner halaman utama agar selalu fit di atas area map.</p>
                        </div>
                        <span class="sf-admin-pill"><x-heroicon-o-photo /><span>Map</span></span>
                    </div>

                    <div class="sf-admin-style-preview">
                        <div class="sf-admin-style-map-stage">
                            <div class="sf-admin-style-map-art" id="mapBackgroundPreview" data-initial-image="{{ $mapBackgroundUrl ?? '' }}" data-default-image="{{ $mapBannerDefaultUrl }}" style="background-image: {{ $mapBackgroundImageCss }}; opacity: {{ $style->mapBackgroundOpacityDecimal() }}; background-position: {{ $style->mapBackgroundPositionCss() }}; transform: scale({{ $mapBackgroundScale }});"></div>
                            <div class="sf-admin-style-map-copy">
                                <div><span>Radar Monitor</span><strong>Spektrum Frekuensi Radio Sulawesi Utara</strong></div>
                                <em>8331 data</em>
                            </div>
                        </div>
                    </div>

                    <label class="sf-admin-style-field"><span>Upload gambar</span><input type="file" name="map_background_image" id="mapBackgroundInput" class="sf-admin-upload-input" accept=".jpg,.jpeg,.png,.webp"></label>
                    <label class="sf-admin-check"><input type="checkbox" name="remove_map_background_image" id="removeMapBackgroundImage" value="1"><span>Kembalikan ke banner default</span></label>
                    <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Opasitas gambar</span><strong id="mapBackgroundOpacityOutput">{{ old('map_background_opacity', $style->map_background_opacity) }}%</strong></div><input type="range" name="map_background_opacity" id="mapBackgroundOpacity" min="0" max="100" value="{{ old('map_background_opacity', $style->map_background_opacity) }}"></label>
                    <div class="sf-admin-style-control-grid">
                        <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Posisi horizontal</span><strong id="mapBackgroundPositionXOutput">{{ old('map_background_position_x', $style->map_background_position_x) }}%</strong></div><input type="range" name="map_background_position_x" id="mapBackgroundPositionX" min="0" max="100" value="{{ old('map_background_position_x', $style->map_background_position_x) }}"></label>
                        <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Posisi vertikal</span><strong id="mapBackgroundPositionYOutput">{{ old('map_background_position_y', $style->map_background_position_y) }}%</strong></div><input type="range" name="map_background_position_y" id="mapBackgroundPositionY" min="0" max="100" value="{{ old('map_background_position_y', $style->map_background_position_y) }}"></label>
                    </div>
                    <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Ukuran gambar</span><strong id="mapBackgroundSizeOutput">{{ old('map_background_size', $style->map_background_size) }}%</strong></div><input type="range" name="map_background_size" id="mapBackgroundSize" min="40" max="180" value="{{ old('map_background_size', $style->map_background_size) }}"></label>
                </section>

                <section class="sf-dashboard-chart-card sf-admin-style-card">
                    <div class="sf-admin-style-card-head">
                        <div class="sf-admin-style-copy">
                            <span class="sf-chart-kicker">Brand</span>
                            <h3>Logo Spektrum</h3>
                            <p>Atur posisi dan ukuran logo untuk area brand publik dan admin dengan preview langsung.</p>
                        </div>
                        <span class="sf-admin-pill"><x-heroicon-o-sparkles /><span>Logo</span></span>
                    </div>

                    <div class="sf-admin-style-preview">
                        <div class="sf-admin-style-brand">
                            <div class="sf-admin-style-brand-mark" id="brandLogoFrame" data-base-width="144" data-base-height="84" style="{{ $style->brandLogoFrameStyle(144, 84) }}">
                                @if ($brandLogoUrl)
                                    <img src="{{ $brandLogoUrl }}" alt="Logo preview" id="brandLogoPreview" data-initial-src="{{ $brandLogoUrl }}" style="opacity: {{ $style->brandLogoOpacityDecimal() }}; object-position: {{ $style->brandLogoPositionCss() }};">
                                @else
                                    <span class="sf-admin-fallback-icon" id="brandLogoFallback"><x-heroicon-o-signal /></span>
                                    <img src="" alt="Logo preview" id="brandLogoPreview" data-initial-src="" style="display: none; opacity: {{ $style->brandLogoOpacityDecimal() }}; object-position: {{ $style->brandLogoPositionCss() }};">
                                @endif
                                @if ($brandLogoUrl)
                                    <span class="sf-admin-fallback-icon" id="brandLogoFallback" style="display: none;"><x-heroicon-o-signal /></span>
                                @endif
                            </div>
                            <div class="sf-admin-style-brand-copy"><h3><span>SIMFREK</span><span>SULUT</span></h3><p>(Sistem Informasi Monitoring Spektrum Frekuensi Sulut)</p><span class="sf-admin-style-brand-agency">Balmon Manado</span></div>
                        </div>
                    </div>

                    <label class="sf-admin-style-field"><span>Upload gambar</span><input type="file" name="brand_logo_image" id="brandLogoInput" class="sf-admin-upload-input" accept=".jpg,.jpeg,.png,.webp"></label>
                    <label class="sf-admin-check"><input type="checkbox" name="remove_brand_logo_image" id="removeBrandLogoImage" value="1"><span>Hapus logo yang sedang dipakai</span></label>
                    <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Opasitas gambar</span><strong id="brandLogoOpacityOutput">{{ old('brand_logo_opacity', $style->brand_logo_opacity) }}%</strong></div><input type="range" name="brand_logo_opacity" id="brandLogoOpacity" min="0" max="100" value="{{ old('brand_logo_opacity', $style->brand_logo_opacity) }}"></label>
                    <div class="sf-admin-style-control-grid">
                        <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Posisi horizontal</span><strong id="brandLogoPositionXOutput">{{ old('brand_logo_position_x', $style->brand_logo_position_x) }}%</strong></div><input type="range" name="brand_logo_position_x" id="brandLogoPositionX" min="0" max="100" value="{{ old('brand_logo_position_x', $style->brand_logo_position_x) }}"></label>
                        <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Posisi vertikal</span><strong id="brandLogoPositionYOutput">{{ old('brand_logo_position_y', $style->brand_logo_position_y) }}%</strong></div><input type="range" name="brand_logo_position_y" id="brandLogoPositionY" min="0" max="100" value="{{ old('brand_logo_position_y', $style->brand_logo_position_y) }}"></label>
                    </div>
                    <label class="sf-admin-style-range"><div class="sf-admin-style-range-head"><span>Ukuran gambar</span><strong id="brandLogoSizeOutput">{{ old('brand_logo_size', $style->brand_logo_size) }}%</strong></div><input type="range" name="brand_logo_size" id="brandLogoSize" min="40" max="180" value="{{ old('brand_logo_size', $style->brand_logo_size) }}"></label>
                </section>
            </div>

            <div class="sf-admin-style-actions">
                <a href="{{ url('/admin/settings/style/colors') }}" class="sf-dashboard-link"><x-heroicon-o-swatch /><span>Atur Warna</span></a>
                <button type="submit" class="sf-dashboard-button"><x-heroicon-o-check /><span>Simpan Perubahan</span></button>
            </div>
        </form>
    </div>

    @include('filament.partials.sweetalert')

    <script>
        function bindRangeOutput(rangeId, outputId) {
            const range = document.getElementById(rangeId);
            const output = document.getElementById(outputId);
            if (!range || !output) return;
            const sync = () => output.textContent = `${Number(range.value || 0)}%`;
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
            if (!preview || !input || !opacity || !positionX || !positionY || !size) return;
            const initialImage = preview.dataset.initialImage || '';
            const defaultImage = preview.dataset.defaultImage || '';
            let objectUrl = '';
            const apply = () => {
                const activeImage = objectUrl || (remove?.checked ? defaultImage : (initialImage || defaultImage));
                preview.style.backgroundImage = activeImage ? `url('${activeImage}')` : 'none';
                preview.style.opacity = activeImage ? `${Number(opacity.value || 0) / 100}` : '0';
                preview.style.backgroundPosition = `${Number(positionX.value || 50)}% ${Number(positionY.value || 50)}%`;
                preview.style.transform = activeImage ? 'scale(' + (Number(size.value || 100) / 100) + ')' : 'scale(1)';
            };
            input.addEventListener('change', () => {
                if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = ''; }
                const [file] = input.files || [];
                if (file) { objectUrl = URL.createObjectURL(file); if (remove) remove.checked = false; }
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
            if (!frame || !preview || !input || !opacity || !positionX || !positionY || !size) return;
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
                    preview.src = activeSource; preview.style.display = 'block'; preview.style.opacity = `${Number(opacity.value || 0) / 100}`;
                    if (fallback) fallback.style.display = 'none';
                    return;
                }
                preview.removeAttribute('src'); preview.style.display = 'none'; if (fallback) fallback.style.display = 'inline-flex';
            };
            input.addEventListener('change', () => {
                if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = ''; }
                const [file] = input.files || [];
                if (file) { objectUrl = URL.createObjectURL(file); if (remove) remove.checked = false; }
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
        [['mapBackgroundOpacity','mapBackgroundOpacityOutput'],['mapBackgroundPositionX','mapBackgroundPositionXOutput'],['mapBackgroundPositionY','mapBackgroundPositionYOutput'],['mapBackgroundSize','mapBackgroundSizeOutput'],['brandLogoOpacity','brandLogoOpacityOutput'],['brandLogoPositionX','brandLogoPositionXOutput'],['brandLogoPositionY','brandLogoPositionYOutput'],['brandLogoSize','brandLogoSizeOutput']].forEach(([rangeId, outputId]) => bindRangeOutput(rangeId, outputId));
        bindMapBackgroundPreview();
        bindBrandLogoPreview();
    </script>
</x-filament-panels::page>
