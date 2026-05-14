<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SpektrumFrekuensi extends Model
{
    public const MAP_CACHE_KEY = 'spektrum.public.map';

    public const STATISTICS_CACHE_KEY = 'spektrum.public.statistics';

    public const SERVICE_CATALOG_CACHE_KEY = 'spektrum.public.services';

    public const CITY_CATALOG_CACHE_KEY = 'spektrum.public.cities';

    private const SULAWESI_PROVINCES = [
        'GORONTALO',
        'SULAWESI BARAT',
        'SULAWESI SELATAN',
        'SULAWESI TENGAH',
        'SULAWESI TENGGARA',
        'SULAWESI UTARA',
    ];

    private const MAP_COLUMNS = [
        'id',
        'CLNT_NAME',
        'SERVICE',
        'STATUS_SIMF',
        'CITY',
        'STN_ADDR',
        'FREQ',
        'CALLSIGN',
        'SID_LAT',
        'SID_LONG',
        'LAT_DEG',
        'LAT_MIN',
        'LAT_SEC',
        'LAT_DIR_IND',
        'LONG_DEG',
        'LONG_MIN',
        'LONG_SEC',
        'LONG_DIR_IND',
        'VALIDITY_DATE',
    ];

    private const DEFAULT_SERVICES = [
        'Aeronautical',
        'Broadcast',
        'Fixed Service',
        'Land Mobile (private)',
        'Land Mobile (public)',
        'Maritim',
        'Satellite',
    ];

    private const DEFAULT_CITIES = [
        'Bitung',
        'Bolaang Mongondow',
        'Bolaang Mongondow Selatan',
        'Bolaang Mongondow Timur',
        'Bolaang Mongondow Utara',
        'Kepulauan Sangihe',
        'Kepulauan Siau Tagulandang Biaro',
        'Kepulauan Talaud',
        'Kotamobagu',
        'Manado',
        'Minahasa',
        'Minahasa Selatan',
        'Minahasa Tenggara',
        'Minahasa Utara',
        'Tomohon',
    ];

    protected $table = 'spektrum_data';

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'CLNT_ID' => 'integer',
        'SITE_ID' => 'integer',
        'FREQ' => 'float',
        'FREQ_PAIR' => 'float',
        'NUM_STATION' => 'integer',
        'ERP_PWR_DBM' => 'float',
        'EQ_PWR' => 'float',
        'GAIN' => 'float',
        'LOSS' => 'float',
        'BWIDTH' => 'float',
        'IB' => 'float',
        'IP' => 'float',
        'HDDP' => 'float',
        'HDLP' => 'float',
        'HGT_ANT' => 'float',
        'AZIM' => 'float',
        'ELEV_ANGLE' => 'float',
        'SID_LONG' => 'float',
        'SID_LAT' => 'float',
        'CIRCUIT_LEN' => 'float',
        'LAT_DEG' => 'integer',
        'LAT_MIN' => 'float',
        'LAT_SEC' => 'float',
        'LONG_DEG' => 'integer',
        'LONG_MIN' => 'float',
        'LONG_SEC' => 'float',
        'MAX_COV_RADIUS' => 'integer',
        'H_ASL' => 'float',
        'NUM_SETS' => 'integer',
        'APPL_DATE' => 'date',
        'LICENCE_DATE' => 'date',
        'VALIDITY_DATE' => 'date',
        'UMUR_ISR' => 'integer',
        'SV_ID' => 'integer',
        'SS_ID' => 'integer',
        'MASA_LAKU' => 'date',
        'ARCHIV_DATE' => 'date',
        'TO_LAT_DEG' => 'integer',
        'TO_LAT_MIN' => 'integer',
        'TO_LAT_SEC' => 'float',
        'TO_LONG_DEG' => 'integer',
        'TO_LONG_MIN' => 'integer',
        'TO_LONG_SEC' => 'float',
        'TGL_QUERY' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeSulawesi(Builder $query): Builder
    {
        return $query->whereIn('PROVINCE', self::SULAWESI_PROVINCES);
    }

    public function scopeMapDataset(Builder $query): Builder
    {
        return $query
            ->select(self::MAP_COLUMNS)
            ->whereNotNull('SID_LAT')
            ->whereNotNull('SID_LONG');
    }

    public static function serviceCatalog(): array
    {
        return Cache::remember(
            self::SERVICE_CATALOG_CACHE_KEY,
            now()->addMinutes(10),
            fn () => static::distinctCatalog('SERVICE', self::DEFAULT_SERVICES),
        );
    }

    public static function cityCatalog(): array
    {
        return Cache::remember(
            self::CITY_CATALOG_CACHE_KEY,
            now()->addMinutes(10),
            fn () => static::distinctCatalog('CITY', self::DEFAULT_CITIES, true),
        );
    }

    public static function cityFilterOptions(): array
    {
        if (! Schema::hasTable('spektrum_data')) {
            return collect(self::DEFAULT_CITIES)
                ->mapWithKeys(fn (string $city) => [$city => $city])
                ->all();
        }

        return static::query()
            ->sulawesi()
            ->select('CITY')
            ->distinct()
            ->whereNotNull('CITY')
            ->where('CITY', '!=', '')
            ->orderBy('CITY')
            ->pluck('CITY')
            ->mapWithKeys(function (mixed $value): array {
                $clean = static::cleanValue($value);
                $formatted = static::formatLocation($value);

                if ($clean === null || $formatted === null) {
                    return [];
                }

                return [$clean => $formatted];
            })
            ->all();
    }

    public function toMapPayload(): array
    {
        return [
            'id' => $this->id,
            'nama' => static::formatEntityName($this->CLNT_NAME),
            'jenis_layanan' => static::cleanValue($this->SERVICE),
            'status' => static::cleanValue($this->STATUS_SIMF),
            'city' => static::formatLocation($this->CITY),
            'stn_address' => static::formatAddress($this->STN_ADDR),
            'frekuensi' => static::formatFrequency($this->FREQ),
            'callsign' => static::cleanValue($this->CALLSIGN),
            'lat' => $this->resolveLatitude(),
            'lng' => $this->resolveLongitude(),
            'masa_berlaku' => $this->VALIDITY_DATE?->format('Y-m-d'),
        ];
    }

    public static function statistics(): array
    {
        return Cache::remember(self::STATISTICS_CACHE_KEY, now()->addMinutes(10), function (): array {
            $serviceSummary = [];
            $baseQuery = static::query()->sulawesi();
            $statusSummary = (array) (clone $baseQuery)
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%GRANTED%' THEN 1 ELSE 0 END) as granted")
                ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%DENDA%' THEN 1 ELSE 0 END) as denda")
                ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%PRELIM%' THEN 1 ELSE 0 END) as pre_elim")
                ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%CANCEL%' THEN 1 ELSE 0 END) as canceled")
                ->first()
                ?->toArray() ?? [
                    'total' => 0,
                    'granted' => 0,
                    'denda' => 0,
                    'pre_elim' => 0,
                    'canceled' => 0,
                ];

            $serviceRows = (clone $baseQuery)
                ->select(['SERVICE'])
                ->selectRaw('COUNT(*) as aggregate_total')
                ->whereNotNull('SERVICE')
                ->where('SERVICE', '!=', '')
                ->groupBy('SERVICE')
                ->get();

            foreach ($serviceRows as $row) {
                $service = static::cleanValue($row->SERVICE);
                if ($service === null) {
                    continue;
                }

                $serviceSummary[$service] = ($serviceSummary[$service] ?? 0) + (int) $row->aggregate_total;
            }

            uksort($serviceSummary, 'strnatcasecmp');

            return [
                'total' => (int) ($statusSummary['total'] ?? 0),
                'granted' => (int) ($statusSummary['granted'] ?? 0),
                'denda' => (int) ($statusSummary['denda'] ?? 0),
                'pre_elim' => (int) ($statusSummary['pre_elim'] ?? 0),
                'canceled' => (int) ($statusSummary['canceled'] ?? 0),
                'pre_cancel' => (int) ($statusSummary['pre_elim'] ?? 0) + (int) ($statusSummary['canceled'] ?? 0),
                'by_service' => $serviceSummary,
            ];
        });
    }

    public static function dashboardSummary(?string $city = null): array
    {
        $baseQuery = static::query()->sulawesi();

        if ($city !== null) {
            $baseQuery->where('CITY', $city);
        }

        $statusSummary = (array) (clone $baseQuery)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%GRANTED%' THEN 1 ELSE 0 END) as granted")
            ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%DENDA%' THEN 1 ELSE 0 END) as denda")
            ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%PRELIM%' THEN 1 ELSE 0 END) as pre_elim")
            ->selectRaw("SUM(CASE WHEN UPPER(COALESCE(STATUS_SIMF, '')) LIKE '%CANCEL%' THEN 1 ELSE 0 END) as canceled")
            ->first()
            ?->toArray() ?? [
                'total' => 0,
                'granted' => 0,
                'denda' => 0,
                'pre_elim' => 0,
                'canceled' => 0,
            ];

        $serviceRows = (clone $baseQuery)
            ->select(['SERVICE'])
            ->selectRaw('COUNT(*) as aggregate_total')
            ->whereNotNull('SERVICE')
            ->where('SERVICE', '!=', '')
            ->groupBy('SERVICE')
            ->orderByDesc('aggregate_total')
            ->get();

        $serviceSummary = [];

        foreach ($serviceRows as $row) {
            $service = static::cleanValue($row->SERVICE);

            if ($service === null) {
                continue;
            }

            $serviceSummary[$service] = (int) $row->aggregate_total;
        }

        return [
            'total' => (int) ($statusSummary['total'] ?? 0),
            'granted' => (int) ($statusSummary['granted'] ?? 0),
            'denda' => (int) ($statusSummary['denda'] ?? 0),
            'pre_elim' => (int) ($statusSummary['pre_elim'] ?? 0),
            'canceled' => (int) ($statusSummary['canceled'] ?? 0),
            'pre_cancel' => (int) ($statusSummary['pre_elim'] ?? 0) + (int) ($statusSummary['canceled'] ?? 0),
            'by_service' => $serviceSummary,
        ];
    }

    public static function clearPublicCache(): void
    {
        Cache::forget(self::MAP_CACHE_KEY);
        Cache::forget(self::STATISTICS_CACHE_KEY);
        Cache::forget(self::SERVICE_CATALOG_CACHE_KEY);
        Cache::forget(self::CITY_CATALOG_CACHE_KEY);
    }

    private static function distinctCatalog(string $column, array $fallback, bool $titleCase = false): array
    {
        if (! Schema::hasTable('spektrum_data')) {
            return $fallback;
        }

        $values = static::query()
            ->sulawesi()
            ->select($column)
            ->distinct()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->orderBy($column)
            ->pluck($column)
            ->map(fn (mixed $value) => $titleCase ? static::formatLocation($value) : static::cleanValue($value))
            ->filter()
            ->unique(fn (string $value) => mb_strtolower($value, 'UTF-8'))
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        return $values !== [] ? $values : $fallback;
    }

    private function resolveLatitude(): ?float
    {
        return $this->resolveCoordinate(
            $this->SID_LAT,
            $this->LAT_DEG,
            $this->LAT_MIN,
            $this->LAT_SEC,
            $this->LAT_DIR_IND,
        );
    }

    private function resolveLongitude(): ?float
    {
        return $this->resolveCoordinate(
            $this->SID_LONG,
            $this->LONG_DEG,
            $this->LONG_MIN,
            $this->LONG_SEC,
            $this->LONG_DIR_IND,
        );
    }

    private function resolveCoordinate(mixed $decimalValue, mixed $degrees, mixed $minutes, mixed $seconds, mixed $direction): ?float
    {
        if ($decimalValue !== null && $decimalValue !== '') {
            return round((float) $decimalValue, 6);
        }

        if ($degrees === null || $minutes === null || $seconds === null || blank($direction)) {
            return null;
        }

        $sign = in_array(strtoupper((string) $direction), ['S', 'W'], true) ? -1 : 1;
        $value = abs((float) $degrees) + (((float) $minutes) / 60) + (((float) $seconds) / 3600);

        return round($value * $sign, 6);
    }

    private static function cleanValue(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return Str::of((string) $value)->squish()->toString();
    }

    private static function formatEntityName(mixed $value): ?string
    {
        return static::formatHumanText($value, ['AM', 'FM', 'HF', 'PT', 'CV', 'RRI', 'LPP', 'VHF', 'UHF', 'VSAT', 'PLN']);
    }

    private static function formatAddress(mixed $value): ?string
    {
        return static::formatHumanText($value, ['KM']);
    }

    private static function formatLocation(mixed $value): ?string
    {
        return static::formatHumanText($value);
    }

    private static function formatHumanText(mixed $value, array $acronyms = []): ?string
    {
        $clean = static::cleanValue($value);
        if ($clean === null) {
            return null;
        }

        $formatted = Str::of($clean)->lower()->title()->squish()->toString();

        foreach ($acronyms as $acronym) {
            $titleForm = Str::of($acronym)->lower()->title()->toString();
            $formatted = preg_replace('/\b' . preg_quote($titleForm, '/') . '\b/u', $acronym, $formatted) ?? $formatted;
        }

        return $formatted;
    }

    private static function formatFrequency(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $formatted = rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.');

            return $formatted . ' MHz';
        }

        return static::cleanValue($value);
    }
}
