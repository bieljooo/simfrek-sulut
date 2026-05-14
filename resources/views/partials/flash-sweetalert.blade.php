@php
    $flashStatus = session('status');
    $flashErrors = $errors->any() ? $errors->all() : [];
    $importResult = session('import_result');
    $importHtml = null;
    $importIcon = 'success';
    $errorHtml = null;

    if (is_array($importResult)) {
        $importIcon = ($importResult['failed_count'] ?? 0) > 0 ? 'warning' : 'success';
        $importHtml = '<div style="text-align:left;">'
            . '<p style="margin:0 0 10px;"><strong>Total:</strong> ' . number_format((int) ($importResult['total_rows'] ?? 0)) . '</p>'
            . '<p style="margin:0 0 10px;"><strong>Berhasil:</strong> ' . number_format((int) ($importResult['success_count'] ?? 0)) . '</p>'
            . '<p style="margin:0 0 10px;"><strong>Gagal:</strong> ' . number_format((int) ($importResult['failed_count'] ?? 0)) . '</p>'
            . '<p style="margin:0;"><strong>Dilewati:</strong> ' . number_format((int) ($importResult['skipped_count'] ?? 0)) . '</p>';

        if (! empty($importResult['errors']) && is_array($importResult['errors'])) {
            $items = collect($importResult['errors'])
                ->map(fn (mixed $error): string => '<li>' . e((string) $error) . '</li>')
                ->implode('');

            if ($items !== '') {
                $importHtml .= '<div style="margin-top:14px;"><strong>Catatan:</strong><ul style="margin:8px 0 0 18px; padding:0;">' . $items . '</ul></div>';
            }
        }

        $importHtml .= '</div>';
    }

    if ($flashErrors !== []) {
        $items = collect($flashErrors)
            ->map(fn (string $error): string => '<li>' . e($error) . '</li>')
            ->implode('');

        $errorHtml = '<ul style="margin:0; padding-left:18px; text-align:left;">' . $items . '</ul>';
    }
@endphp

@if ($flashStatus || $importHtml || $errorHtml)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Swal) {
                return;
            }

            const statusText = @json($flashStatus);
            const importHtml = @json($importHtml);
            const importIcon = @json($importIcon);
            const errorHtml = @json($errorHtml);

            if (importHtml) {
                Swal.fire({
                    icon: importIcon,
                    title: 'Import Data',
                    html: importHtml,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#295fb7',
                });
                return;
            }

            if (errorHtml) {
                Swal.fire({
                    icon: 'error',
                    title: 'Periksa Data',
                    html: errorHtml,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#295fb7',
                });
                return;
            }

            if (statusText) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: statusText,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#295fb7',
                });
            }
        });
    </script>
@endif