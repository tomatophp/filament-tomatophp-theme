<?php

namespace TomatoPHP\FilamentTomatoPHPTheme;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Facades\FilamentAsset;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class FilamentTomatoPHPThemePlugin implements Plugin
{
    use EvaluatesClosures;

    public const PACKAGE = 'tomatophp/filament-tomatophp-theme';

    /** Brand palette from https://tomatophp.com/en/brand */
    public const BRAND = '#D64524';

    public const ACCENT = '#4E9A3E';

    public const INK = '#0B1429';

    public const PAPER = '#F0EBE1';

    public const STYLESHEETS = [
        'base' => 'tomatophp-theme',
        'hatch' => 'tomatophp-theme-hatch',
        'square' => 'tomatophp-theme-square',
    ];

    protected bool | Closure $hasBrandColors = true;

    protected bool | Closure $hasBrandLogo = true;

    protected bool | Closure $hasHatch = true;

    protected bool | Closure $hasSquareCorners = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-tomatophp-theme';
    }

    /** Use Brand #D64524 as primary and Accent #4E9A3E as success. */
    public function brandColors(bool | Closure $condition = true): static
    {
        $this->hasBrandColors = $condition;

        return $this;
    }

    /** Use the TomatoPHP mark as the panel logo and favicon. */
    public function brandLogo(bool | Closure $condition = true): static
    {
        $this->hasBrandLogo = $condition;

        return $this;
    }

    /** Hatched ground on guest pages and hatched page header bands. */
    public function hatch(bool | Closure $condition = true): static
    {
        $this->hasHatch = $condition;

        return $this;
    }

    /** Square corners on cards, inputs, buttons and badges. */
    public function squareCorners(bool | Closure $condition = true): static
    {
        $this->hasSquareCorners = $condition;

        return $this;
    }

    public function hasBrandColors(): bool
    {
        return (bool) $this->evaluate($this->hasBrandColors);
    }

    public function hasBrandLogo(): bool
    {
        return (bool) $this->evaluate($this->hasBrandLogo);
    }

    public function hasHatch(): bool
    {
        return (bool) $this->evaluate($this->hasHatch);
    }

    public function hasSquareCorners(): bool
    {
        return (bool) $this->evaluate($this->hasSquareCorners);
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::HEAD_END,
            fn (): Htmlable => new HtmlString($this->renderStylesheets()),
        );

        // Panels register their colors before plugins boot, so branding must be applied here.
        // register() runs when ->plugin() is called, after the options chained on make().
        if ($this->hasBrandColors()) {
            $panel->colors([
                'primary' => Color::hex(self::BRAND),
                'success' => Color::hex(self::ACCENT),
            ]);
        }

        if ($this->hasBrandLogo()) {
            $panel
                ->brandLogo(fn (): Htmlable => new HtmlString(static::markSvg()))
                ->brandLogoHeight('2.5rem')
                ->favicon(static::markDataUri());
        }
    }

    public function boot(Panel $panel): void {}

    /**
     * @return array<int, string>
     */
    public function getStylesheets(): array
    {
        return array_values(array_filter([
            self::STYLESHEETS['base'],
            $this->hasHatch() ? self::STYLESHEETS['hatch'] : null,
            $this->hasSquareCorners() ? self::STYLESHEETS['square'] : null,
        ]));
    }

    public function renderStylesheets(): string
    {
        return collect($this->getStylesheets())
            ->map(fn (string $id): string => '<link rel="stylesheet" href="' . e($this->stylesheetHref($id)) . '" data-navigate-track />')
            ->implode('');
    }

    /**
     * Filament versions asset URLs by package version only; a content hash keeps CDNs from
     * serving a stale stylesheet after the CSS changes within the same version.
     */
    public function stylesheetHref(string $id): string
    {
        $path = __DIR__ . "/../resources/dist/{$id}.css";
        $hash = is_file($path) ? substr((string) md5_file($path), 0, 8) : null;

        return FilamentAsset::getStyleHref($id, self::PACKAGE) . ($hash ? "&h={$hash}" : '');
    }

    public static function markSvg(): string
    {
        return str_replace(
            '<svg ',
            '<svg role="img" aria-label="TomatoPHP" style="height: 100%; width: auto;" ',
            (string) file_get_contents(__DIR__ . '/../resources/images/mark.svg'),
        );
    }

    public static function markDataUri(): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode((string) file_get_contents(__DIR__ . '/../resources/images/mark.svg'));
    }
}
