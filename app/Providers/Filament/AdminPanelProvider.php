<?php

namespace App\Providers\Filament;

use App\Filament\Support\AdminAvatarProvider;
use App\Http\Middleware\AuthenticateFilamentPanel;
use App\Http\Middleware\EnsureAdmin;
use App\Models\WebsiteStyle;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->colors([
                'primary' => Color::hex('#295fb7'),
                'gray' => Color::Slate,
            ])
            ->darkMode(false)
            ->font(
                'Manrope',
                'https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|space-grotesk:500,700&display=swap',
            )
            ->brandName('SISFREK SULUT')
            ->brandLogo(fn (): ?string => WebsiteStyle::singleton()->brandLogoUrl())
            ->brandLogoHeight(fn (): string => WebsiteStyle::singleton()->brandLogoHeightRem(2.5))
            ->favicon(fn (): ?string => WebsiteStyle::singleton()->brandLogoUrl())
            ->defaultAvatarProvider(AdminAvatarProvider::class)
            ->maxContentWidth(Width::Full)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->navigationItems([
                NavigationItem::make('Kelola Data')
                    ->group('Menu')
                    ->icon('heroicon-o-circle-stack')
                    ->sort(10)
                    ->isActiveWhen(fn (): bool => request()->is('admin/kelola-data*'))
                    ->url(fn (): string => url('/admin/kelola-data')),
                NavigationItem::make('Halaman Utama')
                    ->group('Menu')
                    ->icon('heroicon-o-globe-alt')
                    ->sort(20)
                    ->isActiveWhen(fn (): bool => request()->routeIs('home'))
                    ->url(fn (): string => route('home')),
                NavigationItem::make('Style Website')
                    ->group('Pengaturan')
                    ->icon('heroicon-o-swatch')
                    ->sort(30)
                    ->isActiveWhen(fn (): bool => request()->is('admin/settings/style/images*') || request()->is('admin/settings/style/colors*'))
                    ->url(fn (): string => url('/admin/settings/style/images'))
                    ->childItems([
                        NavigationItem::make('Setel Gambar')
                            ->isActiveWhen(fn (): bool => request()->is('admin/settings/style/images*'))
                            ->url(fn (): string => url('/admin/settings/style/images')),
                        NavigationItem::make('Atur Warna')
                            ->isActiveWhen(fn (): bool => request()->is('admin/settings/style/colors*'))
                            ->url(fn (): string => url('/admin/settings/style/colors')),
                    ]),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                AuthenticateFilamentPanel::class,
                EnsureAdmin::class,
            ]);
    }
}