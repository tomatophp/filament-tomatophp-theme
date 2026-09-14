<?php

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use TomatoPHP\FilamentTomatoPHPTheme\FilamentTomatoPHPThemePlugin;

it('registers the plugin on the panel', function () {
    expect(Filament::getPanel('admin')->getPlugin('filament-tomatophp-theme'))
        ->toBeInstanceOf(FilamentTomatoPHPThemePlugin::class);
});

it('applies the TomatoPHP brand colors', function () {
    $panel = Filament::getPanel('admin');
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect($panel->getColors())
        ->toHaveKeys(['primary', 'success'])
        ->and($panel->getColors()['primary'])->toEqual(Color::hex(FilamentTomatoPHPThemePlugin::BRAND))
        ->and($panel->getColors()['success'])->toEqual(Color::hex(FilamentTomatoPHPThemePlugin::ACCENT));
});

it('registers the brand colors Filament renders with', function () {
    $panel = Filament::getPanel('admin');
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect(FilamentColor::getColor('primary'))->toEqual(Color::hex(FilamentTomatoPHPThemePlugin::BRAND))
        ->and(FilamentColor::getColor('success'))->toEqual(Color::hex(FilamentTomatoPHPThemePlugin::ACCENT));
});

it('uses the TomatoPHP mark as the logo and favicon', function () {
    $panel = Filament::getPanel('admin');
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect((string) $panel->getBrandLogo())
        ->toContain('<svg')
        ->toContain('aria-label="TomatoPHP"')
        ->toContain('#D64524')
        ->and($panel->getFavicon())->toStartWith('data:image/svg+xml;base64,');
});

it('registers every stylesheet with Filament assets', function () {
    foreach (FilamentTomatoPHPThemePlugin::STYLESHEETS as $id) {
        expect(FilamentAsset::getStyleHref($id, FilamentTomatoPHPThemePlugin::PACKAGE))->toContain("{$id}.css")
            ->and(__DIR__ . "/../../resources/dist/{$id}.css")->toBeFile();
    }
});

it('injects the theme stylesheets into the panel head', function () {
    $panel = Filament::getPanel('admin');
    Filament::setCurrentPanel($panel);
    $panel->boot();

    expect(FilamentView::renderHook(PanelsRenderHook::HEAD_END)->toHtml())
        ->toContain('tomatophp-theme.css')
        ->toContain('tomatophp-theme-hatch.css')
        ->toContain('tomatophp-theme-square.css');
});

it('busts CDN caches with a content hash on every stylesheet url', function () {
    $plugin = Filament::getPanel('admin')->getPlugin('filament-tomatophp-theme');

    foreach (FilamentTomatoPHPThemePlugin::STYLESHEETS as $id) {
        $hash = substr(md5_file(__DIR__ . "/../../resources/dist/{$id}.css"), 0, 8);

        expect($plugin->stylesheetHref($id))->toEndWith("&h={$hash}");
    }
});

it('can turn off the optional parts of the theme', function () {
    $plugin = FilamentTomatoPHPThemePlugin::make()
        ->brandColors(false)
        ->brandLogo(false)
        ->hatch(false)
        ->squareCorners(false);

    $panel = Panel::make()->id('plain')->path('plain');
    $plugin->register($panel);
    $plugin->boot($panel);

    expect($panel->getColors())->not->toHaveKey('primary')
        ->and($panel->getBrandLogo())->toBeNull()
        ->and($plugin->getStylesheets())->toBe(['tomatophp-theme'])
        ->and($plugin->renderStylesheets())->toContain('tomatophp-theme.css')->not->toContain('hatch');
});
