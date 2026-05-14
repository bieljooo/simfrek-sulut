<?php

namespace App\Filament\Pages;

use App\Models\SpektrumFrekuensi;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static string | UnitEnum | null $navigationGroup = 'Menu';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -10;

    protected static ?string $title = 'Dashboard';

    protected string $view = 'filament.pages.dashboard';

    public ?string $selectedCity = null;

    public string $selectedCityLabel = 'Semua Kota';

    /**
     * @var array<string, string>
     */
    public array $cityOptions = [];

    /**
     * @var array<string, mixed>
     */
    public array $stats = [];

    /**
     * @var array<string, int>
     */
    public array $statusChart = [];

    /**
     * @var array<string, int>
     */
    public array $serviceChart = [];

    public function mount(): void
    {
        $this->cityOptions = SpektrumFrekuensi::cityFilterOptions();

        $requestedCity = trim((string) request()->query('city'));
        $this->selectedCity = ($requestedCity !== '' && array_key_exists($requestedCity, $this->cityOptions))
            ? $requestedCity
            : null;

        $this->hydrateDashboardData();
    }

    public function getHeading(): string | Htmlable
    {
        return 'Dashboard';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Ringkasan data ' . $this->selectedCityLabel;
    }

    protected function hydrateDashboardData(): void
    {
        $this->stats = SpektrumFrekuensi::dashboardSummary($this->selectedCity);
        $this->selectedCityLabel = $this->selectedCity ? $this->cityOptions[$this->selectedCity] : 'Semua Kota';
        $this->statusChart = [
            'Granted' => (int) ($this->stats['granted'] ?? 0),
            'Denda' => (int) ($this->stats['denda'] ?? 0),
            'Pre Elim Cancelled' => (int) (($this->stats['pre_elim'] ?? 0) + ($this->stats['canceled'] ?? 0)),
        ];
        $this->serviceChart = array_slice($this->stats['by_service'] ?? [], 0, 8, true);
    }
}