<?php

namespace App\Filament\Pages;

use App\Models\ImportHistory;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;

class DataManagement extends Page
{
    protected string $view = 'filament.pages.data-management';

    protected static ?string $slug = 'kelola-data';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Kelola Data';

    public function getHeading(): string | Htmlable
    {
        return 'Kelola Data';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Import, export, template, dan riwayat pembaruan data.';
    }

    public function getImportHistoryProperty(): Collection
    {
        return ImportHistory::query()
            ->latest('import_date')
            ->limit(10)
            ->get();
    }
}