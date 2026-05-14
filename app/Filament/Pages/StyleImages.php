<?php

namespace App\Filament\Pages;

use App\Models\WebsiteStyle;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class StyleImages extends Page
{
    protected string $view = 'filament.pages.style-images';

    protected static ?string $slug = 'settings/style/images';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Setel Gambar';

    public function getHeading(): string | Htmlable
    {
        return 'Setel Gambar';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Atur gambar, opasitas, posisi, dan ukuran aset visual website.';
    }

    public function getStyleProperty(): WebsiteStyle
    {
        return WebsiteStyle::singleton();
    }
}