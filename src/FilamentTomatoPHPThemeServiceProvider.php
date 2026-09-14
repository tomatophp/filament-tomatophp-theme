<?php

namespace TomatoPHP\FilamentTomatoPHPTheme;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentTomatoPHPThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Loaded on request so only panels that register the plugin receive the theme.
        FilamentAsset::register(
            collect(FilamentTomatoPHPThemePlugin::STYLESHEETS)
                ->map(fn (string $id): Css => Css::make($id, __DIR__ . "/../resources/dist/{$id}.css")->loadedOnRequest())
                ->values()
                ->all(),
            package: FilamentTomatoPHPThemePlugin::PACKAGE,
        );
    }
}
