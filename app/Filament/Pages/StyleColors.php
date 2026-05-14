<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class StyleColors extends Page
{
    protected string $view = 'filament.pages.style-colors';

    protected static ?string $slug = 'settings/style/colors';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Atur Warna';

    public function getHeading(): string | Htmlable
    {
        return 'Atur Warna';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Ruang pengaturan warna utama website.';
    }
}