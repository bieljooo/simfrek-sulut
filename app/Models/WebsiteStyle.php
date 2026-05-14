<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class WebsiteStyle extends Model
{
    protected $table = 'website_styles';

    protected $guarded = [];

    protected $casts = [
        'map_background_opacity' => 'integer',
        'map_background_position_x' => 'integer',
        'map_background_position_y' => 'integer',
        'map_background_size' => 'integer',
        'brand_logo_opacity' => 'integer',
        'brand_logo_position_x' => 'integer',
        'brand_logo_position_y' => 'integer',
        'brand_logo_size' => 'integer',
    ];

    public static function singleton(): self
    {
        if (! Schema::hasTable('website_styles')) {
            return new static(static::defaultAttributes());
        }

        return static::query()->firstOrCreate(
            ['id' => 1],
            static::defaultAttributes(),
        );
    }

    public function mapBackgroundUrl(): ?string
    {
        return $this->map_background_image_path ? asset($this->map_background_image_path) : null;
    }

    public function brandLogoUrl(): ?string
    {
        return $this->brand_logo_image_path ? asset($this->brand_logo_image_path) : null;
    }

    public function mapBackgroundOpacityDecimal(): string
    {
        return number_format($this->normalizeOpacity($this->map_background_opacity), 2, '.', '');
    }

    public function mapBackgroundPositionCss(): string
    {
        return $this->normalizePosition($this->map_background_position_x) . ' ' . $this->normalizePosition($this->map_background_position_y);
    }

    public function mapBackgroundSizeCss(): string
    {
        return $this->normalizeImageSize($this->map_background_size) . '%';
    }

    public function brandLogoOpacityDecimal(): string
    {
        return number_format($this->normalizeOpacity($this->brand_logo_opacity), 2, '.', '');
    }

    public function brandLogoPositionCss(): string
    {
        return $this->normalizePosition($this->brand_logo_position_x) . ' ' . $this->normalizePosition($this->brand_logo_position_y);
    }

    public function brandLogoScaleDecimal(): string
    {
        return number_format($this->normalizeImageSize($this->brand_logo_size) / 100, 2, '.', '');
    }

    public function brandLogoFrameStyle(int $baseWidth, int $baseHeight): string
    {
        $scale = $this->normalizeImageSize($this->brand_logo_size) / 100;

        return 'width: ' . (int) round($baseWidth * $scale) . 'px; height: ' . (int) round($baseHeight * $scale) . 'px;';
    }

    public function brandLogoHeightRem(float $baseRem): string
    {
        $scale = $this->normalizeImageSize($this->brand_logo_size) / 100;

        return number_format($baseRem * $scale, 2, '.', '') . 'rem';
    }

    private static function defaultAttributes(): array
    {
        return [
            'map_background_opacity' => 18,
            'map_background_position_x' => 50,
            'map_background_position_y' => 50,
            'map_background_size' => 100,
            'brand_logo_opacity' => 100,
            'brand_logo_position_x' => 50,
            'brand_logo_position_y' => 50,
            'brand_logo_size' => 100,
        ];
    }

    private function normalizeOpacity(?int $value): float
    {
        $clamped = max(0, min(100, (int) $value));

        return $clamped / 100;
    }

    private function normalizePosition(?int $value): string
    {
        $clamped = max(0, min(100, (int) $value));

        return $clamped . '%';
    }

    private function normalizeImageSize(?int $value): int
    {
        return max(40, min(180, (int) $value));
    }
}