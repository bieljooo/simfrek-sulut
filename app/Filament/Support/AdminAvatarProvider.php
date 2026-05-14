<?php

namespace App\Filament\Support;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Database\Eloquent\Model;

class AdminAvatarProvider implements AvatarProvider
{
    public function get(Model $record): string
    {
        $svg = <<<'SVG'
<svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect width="96" height="96" rx="48" fill="#EAF2FF"/>
    <circle cx="48" cy="36" r="16" fill="#7AAEFF"/>
    <path d="M24 76C24 61.6406 35.6406 50 50 50H46C60.3594 50 72 61.6406 72 76V80H24V76Z" fill="#295FB7"/>
</svg>
SVG;

        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}