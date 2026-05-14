<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportHistory;
use App\Models\SpektrumFrekuensi;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportController extends Controller
{
    private const TABLE_COLUMNS = [
        'id',
        'CLNT_ID',
        'NO_SIMF',
        'APPL_ID',
        'AP_PRJ_IDENT',
        'SITE_ID',
        'CLNT_NAME',
        'AD_NAME',
        'AD_FIRST_NAME',
        'STATUS_SIMF',
        'CORR_ADDR',
        'PHONE',
        'FAX',
        'SERVICE',
        'SUBSERVICE',
        'TRANS_TYPE',
        'FREQ',
        'FREQ_PAIR',
        'NUM_STATION',
        'ERP_PWR_DBM',
        'EQ_PWR',
        'GAIN',
        'LOSS',
        'BWIDTH',
        'IB',
        'IP',
        'HDDP',
        'HDLP',
        'ZONA',
        'STN_TYPE',
        'EQUIP_TYPE',
        'BHP',
        'EQ_MFR',
        'TX_EQP_ID',
        'CONFIG_PLZN_CODE',
        'EQ_MDL',
        'ANT_MFR',
        'ANT_MDL',
        'HGT_ANT',
        'AZIM',
        'ELEV_ANGLE',
        'MASTER_PLZN_CODE',
        'EMIS_CLASS_1',
        'STN_NAME',
        'STN_ADDR',
        'CALLSIGN',
        'SID_LONG',
        'SID_LAT',
        'CIRCUIT_LEN',
        'LAT_DEG',
        'LAT_MIN',
        'LAT_SEC',
        'LAT_DIR_IND',
        'LONG_DEG',
        'LONG_MIN',
        'LONG_SEC',
        'LONG_DIR_IND',
        'AREA_OF_SERVICE',
        'MAX_COV_RADIUS',
        'H_ASL',
        'NUM_SETS',
        'CURR_LIC_NUM',
        'APPL_DATE',
        'LICENCE_DATE',
        'VALIDITY_DATE',
        'UMUR_ISR',
        'VILLAGE',
        'CITY',
        'DISTRICT',
        'PROVINCE',
        'SV_ID',
        'SS_ID',
        'TO_APPL_ID',
        'TO_CLNT_ID',
        'LINK_ID',
        'MASA_LAKU',
        'ARCHIV_DATE',
        'TO_SITE_ID',
        'STASIUN_LAWAN',
        'RECEV_TYPE',
        'TO_CALLSIGN',
        'TO_LAT_DEG',
        'TO_LAT_MIN',
        'TO_LAT_SEC',
        'TO_LAT_DIR_IND',
        'TO_LONG_DEG',
        'TO_LONG_MIN',
        'TO_LONG_SEC',
        'TO_LONG_DIR_IND',
        'COST_CAT',
        'AP_REQUEST_TYPE',
        'TGL_QUERY',
        'created_at',
        'updated_at',
    ];

    private const INTEGER_COLUMNS = [
        'id',
        'CLNT_ID',
        'SITE_ID',
        'NUM_STATION',
        'LAT_DEG',
        'LONG_DEG',
        'MAX_COV_RADIUS',
        'NUM_SETS',
        'UMUR_ISR',
        'SV_ID',
        'SS_ID',
        'TO_LAT_DEG',
        'TO_LAT_MIN',
        'TO_LONG_DEG',
        'TO_LONG_MIN',
    ];

    private const DECIMAL_COLUMNS = [
        'FREQ',
        'FREQ_PAIR',
        'ERP_PWR_DBM',
        'EQ_PWR',
        'GAIN',
        'LOSS',
        'BWIDTH',
        'IB',
        'IP',
        'HDDP',
        'HDLP',
        'HGT_ANT',
        'AZIM',
        'ELEV_ANGLE',
        'SID_LONG',
        'SID_LAT',
        'CIRCUIT_LEN',
        'LAT_MIN',
        'LAT_SEC',
        'LONG_MIN',
        'LONG_SEC',
        'H_ASL',
        'TO_LAT_SEC',
        'TO_LONG_SEC',
    ];

    private const DATE_COLUMNS = [
        'APPL_DATE',
        'LICENCE_DATE',
        'VALIDITY_DATE',
        'MASA_LAKU',
        'ARCHIV_DATE',
        'TGL_QUERY',
    ];

    private const DATETIME_COLUMNS = [
        'created_at',
        'updated_at',
    ];

    private const HEADER_ALIASES = [
        'NAMA' => 'CLNT_NAME',
        'NAMA_PENGGUNA' => 'CLNT_NAME',
        'CLIENT_NAME' => 'CLNT_NAME',
        'NAMA_STASIUN' => 'STN_NAME',
        'STATION_NAME' => 'STN_NAME',
        'LAT' => 'SID_LAT',
        'LATITUDE' => 'SID_LAT',
        'LNG' => 'SID_LONG',
        'LONG' => 'SID_LONG',
        'LONGITUDE' => 'SID_LONG',
        'ALAMAT' => 'STN_ADDR',
        'KOTA' => 'CITY',
        'KABUPATEN' => 'CITY',
        'FREKUENSI' => 'FREQ',
    ];

    public function template(): StreamedResponse
    {
        $rows = [self::TABLE_COLUMNS, $this->sampleRow()];

        return $this->streamCsv('template-spektrum-data.csv', $rows);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'wb');
            fputcsv($output, self::TABLE_COLUMNS);

            foreach (SpektrumFrekuensi::query()->orderBy('id')->cursor() as $record) {
                fputcsv($output, $this->formatExportRow($record));
            }

            fclose($output);
        }, 'spektrum-data-export.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:20480', 'mimes:csv,txt,xlsx,xls'],
            'replace_existing' => ['nullable', 'boolean'],
        ]);

        [$headers, $rows] = $this->readUploadedRows($validated['file']);

        if ($headers === [] || count(array_intersect($headers, array_merge(self::TABLE_COLUMNS, array_keys(self::HEADER_ALIASES)))) === 0) {
            return redirect('/admin/kelola-data')
                ->withErrors(['file' => 'Header file tidak sesuai template spektrum_data.']);
        }

        $summary = [
            'total_rows' => 0,
            'success_count' => 0,
            'failed_count' => 0,
            'skipped_count' => 0,
            'errors' => [],
        ];

        if ($request->boolean('replace_existing')) {
            SpektrumFrekuensi::query()->delete();
        }

        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2;

            if ($this->rowIsEmpty($row)) {
                $summary['skipped_count']++;
                continue;
            }

            $summary['total_rows']++;

            try {
                $payload = $this->normalizeRow($headers, $row);
                if ($payload === null) {
                    $summary['skipped_count']++;
                    continue;
                }

                $identity = $this->resolveIdentity($payload);
                if ($identity === []) {
                    $summary['failed_count']++;
                    $this->pushError($summary, "Baris {$lineNumber}: identitas data tidak cukup untuk update/import.");
                    continue;
                }

                SpektrumFrekuensi::query()->updateOrCreate($identity, $payload);
                $summary['success_count']++;
            } catch (\Throwable $exception) {
                $summary['failed_count']++;
                $this->pushError($summary, "Baris {$lineNumber}: {$exception->getMessage()}");
            }
        }

        ImportHistory::create([
            'user_id' => $request->user()->id,
            'file_name' => $validated['file']->getClientOriginalName(),
            'total_rows' => $summary['total_rows'],
            'success_count' => $summary['success_count'],
            'failed_count' => $summary['failed_count'],
            'skipped_count' => $summary['skipped_count'],
            'imported_by' => $request->user()->username,
            'import_date' => now(),
        ]);

        SpektrumFrekuensi::clearPublicCache();

        return redirect('/admin/kelola-data')
            ->with('import_result', $summary)
            ->with('status', 'Import data selesai diproses.');
    }

    public function destroyAll(): RedirectResponse
    {
        SpektrumFrekuensi::query()->delete();
        SpektrumFrekuensi::clearPublicCache();

        return redirect('/admin/kelola-data')
            ->with('status', 'Semua data spektrum berhasil dihapus.');
    }

    private function readUploadedRows(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['csv', 'txt'], true)) {
            return $this->readCsvRows($file);
        }

        if (! class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            throw new \RuntimeException('Upload XLSX/XLS memerlukan paket PhpSpreadsheet.');
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, false, false, false);
        $headers = array_map([$this, 'normalizeHeader'], array_shift($rows) ?? []);

        return [$headers, $rows];
    }

    private function readCsvRows(UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'rb');

        if (! $handle) {
            throw new \RuntimeException('File tidak dapat dibaca.');
        }

        $headers = [];
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            if ($headers === []) {
                $headers = array_map([$this, 'normalizeHeader'], $row);
                continue;
            }

            $rows[] = $row;
        }

        fclose($handle);

        return [$headers, $rows];
    }

    private function normalizeRow(array $headers, array $row): ?array
    {
        $indexed = [];
        foreach ($headers as $index => $header) {
            if ($header === '') {
                continue;
            }

            $indexed[$header] = $row[$index] ?? null;
        }

        $payload = [];
        foreach (self::TABLE_COLUMNS as $column) {
            $payload[$column] = $this->normalizeColumnValue($column, $indexed[$column] ?? null);
        }

        if ($payload['SID_LAT'] === null) {
            $payload['SID_LAT'] = $this->buildCoordinate(
                $payload['LAT_DEG'] ?? null,
                $payload['LAT_MIN'] ?? null,
                $payload['LAT_SEC'] ?? null,
                $payload['LAT_DIR_IND'] ?? null,
            );
        }

        if ($payload['SID_LONG'] === null) {
            $payload['SID_LONG'] = $this->buildCoordinate(
                $payload['LONG_DEG'] ?? null,
                $payload['LONG_MIN'] ?? null,
                $payload['LONG_SEC'] ?? null,
                $payload['LONG_DIR_IND'] ?? null,
            );
        }

        if ($payload['id'] === null) {
            unset($payload['id']);
        }

        if ($payload['created_at'] === null) {
            unset($payload['created_at']);
        }

        if ($payload['updated_at'] === null) {
            unset($payload['updated_at']);
        }

        return $this->rowIsEmpty($payload) ? null : $payload;
    }

    private function normalizeColumnValue(string $column, mixed $value): mixed
    {
        if (in_array($column, self::INTEGER_COLUMNS, true)) {
            return $this->normalizeInteger($value);
        }

        if (in_array($column, self::DECIMAL_COLUMNS, true)) {
            return $this->normalizeDecimal($value);
        }

        if (in_array($column, self::DATE_COLUMNS, true)) {
            return $this->normalizeDate($value);
        }

        if (in_array($column, self::DATETIME_COLUMNS, true)) {
            return $this->normalizeDateTime($value);
        }

        return $this->normalizeString($value);
    }

    private function resolveIdentity(array $payload): array
    {
        if (isset($payload['id'])) {
            return ['id' => $payload['id']];
        }

        $primary = $this->identitySubset($payload, ['NO_SIMF', 'SITE_ID', 'STN_NAME', 'CALLSIGN', 'TO_SITE_ID']);
        if (count($primary) >= 3 && isset($primary['NO_SIMF'], $primary['STN_NAME'])) {
            return $primary;
        }

        $secondary = $this->identitySubset($payload, ['APPL_ID', 'CLNT_ID', 'STN_NAME', 'CALLSIGN', 'SITE_ID']);
        if (count($secondary) >= 3 && isset($secondary['STN_NAME'])) {
            return $secondary;
        }

        $tertiary = $this->identitySubset($payload, ['CLNT_NAME', 'STN_ADDR', 'CITY', 'FREQ', 'CALLSIGN']);
        if (count($tertiary) >= 3) {
            return $tertiary;
        }

        return [];
    }

    private function identitySubset(array $payload, array $columns): array
    {
        $identity = [];

        foreach ($columns as $column) {
            if (! array_key_exists($column, $payload) || blank($payload[$column])) {
                continue;
            }

            $identity[$column] = $payload[$column];
        }

        return $identity;
    }

    private function normalizeHeader(mixed $value): string
    {
        $normalized = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value) ?? (string) $value;
        $normalized = Str::of($normalized)
            ->trim()
            ->upper()
            ->replace([' ', '.', '-', '/'], '_')
            ->replace('__', '_')
            ->toString();

        $normalized = trim($normalized, '_');

        return self::HEADER_ALIASES[$normalized] ?? $normalized;
    }

    private function normalizeString(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return trim((string) $value);
    }

    private function normalizeInteger(mixed $value): ?int
    {
        if (blank($value)) {
            return null;
        }

        $normalized = str_replace([',', '.0'], ['', ''], (string) $value);

        return is_numeric($normalized) ? (int) round((float) $normalized) : null;
    }

    private function normalizeDecimal(mixed $value): ?float
    {
        if (blank($value)) {
            return null;
        }

        $normalized = str_replace(',', '.', (string) $value);

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (is_numeric($value) && class_exists(\PhpOffice\PhpSpreadsheet\Shared\Date::class)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeDateTime(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (is_numeric($value) && class_exists(\PhpOffice\PhpSpreadsheet\Shared\Date::class)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d H:i:s');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    private function buildCoordinate(mixed $degrees, mixed $minutes, mixed $seconds, mixed $direction): ?float
    {
        if ($degrees === null || $minutes === null || $seconds === null || blank($direction)) {
            return null;
        }

        $sign = in_array(strtoupper((string) $direction), ['S', 'W'], true) ? -1 : 1;
        $value = abs((float) $degrees) + (((float) $minutes) / 60) + (((float) $seconds) / 3600);

        return round($value * $sign, 6);
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (! blank($value)) {
                return false;
            }
        }

        return true;
    }

    private function pushError(array &$summary, string $message): void
    {
        if (count($summary['errors']) < 10) {
            $summary['errors'][] = $message;
        }
    }

    private function formatExportRow(SpektrumFrekuensi $record): array
    {
        $row = [];

        foreach (self::TABLE_COLUMNS as $column) {
            $value = $record->{$column};

            if ($value instanceof Carbon) {
                $row[] = $value->format(in_array($column, self::DATETIME_COLUMNS, true) ? 'Y-m-d H:i:s' : 'Y-m-d');
                continue;
            }

            $row[] = $value;
        }

        return $row;
    }

    private function sampleRow(): array
    {
        $sample = array_fill_keys(self::TABLE_COLUMNS, '');
        $sample['id'] = 1;
        $sample['CLNT_ID'] = 11001;
        $sample['NO_SIMF'] = '0760217';
        $sample['APPL_ID'] = '15025092019';
        $sample['SITE_ID'] = 1;
        $sample['CLNT_NAME'] = 'PT Radio Lokal Minahasa';
        $sample['STATUS_SIMF'] = 'DENDA';
        $sample['PHONE'] = '0431-111222';
        $sample['SERVICE'] = 'Broadcast';
        $sample['SUBSERVICE'] = 'FM';
        $sample['TRANS_TYPE'] = 'Transmitter';
        $sample['FREQ'] = 99.5;
        $sample['STN_NAME'] = 'Radio FM Minahasa';
        $sample['STN_ADDR'] = 'Jl. Trans Sulawesi';
        $sample['CALLSIGN'] = 'YKZZ';
        $sample['SID_LONG'] = 124.92;
        $sample['SID_LAT'] = 1.38;
        $sample['VALIDITY_DATE'] = '2024-12-31';
        $sample['CITY'] = 'MINAHASA';
        $sample['DISTRICT'] = 'TONDANO SELATAN';
        $sample['PROVINCE'] = 'SULAWESI UTARA';
        $sample['created_at'] = now()->format('Y-m-d H:i:s');
        $sample['updated_at'] = now()->format('Y-m-d H:i:s');

        return array_map(fn (string $column) => $sample[$column] ?? '', self::TABLE_COLUMNS);
    }

    private function streamCsv(string $fileName, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'wb');

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
