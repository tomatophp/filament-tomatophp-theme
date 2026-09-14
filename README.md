![TomatoPHP Theme](https://raw.githubusercontent.com/tomatophp/filament-tomatophp-theme/master/arts/tomatophp-theme.jpg)

# TomatoPHP Theme for Filament

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-tomatophp-theme/version.svg)](https://packagist.org/packages/tomatophp/filament-tomatophp-theme)
[![License](https://poser.pugx.org/tomatophp/filament-tomatophp-theme/license.svg)](https://packagist.org/packages/tomatophp/filament-tomatophp-theme)
[![Downloads](https://poser.pugx.org/tomatophp/filament-tomatophp-theme/d/total.svg)](https://packagist.org/packages/tomatophp/filament-tomatophp-theme)

The TomatoPHP brand for your FilamentPHP panels: the TomatoPHP mark as logo and favicon, the official brand colors,
and a screen-line look with hairline borders, square corners and 315° hatched dividers.

No build step: the theme ships plain CSS through Filament's asset system and is only loaded on panels that register the plugin.

## Screenshots

![Login dark](https://raw.githubusercontent.com/tomatophp/filament-tomatophp-theme/master/arts/login-dark.png)
![Login light](https://raw.githubusercontent.com/tomatophp/filament-tomatophp-theme/master/arts/login-light.png)
![User menu dark](https://raw.githubusercontent.com/tomatophp/filament-tomatophp-theme/master/arts/user-menu-dark.png)
![User menu light](https://raw.githubusercontent.com/tomatophp/filament-tomatophp-theme/master/arts/user-menu-light.png)

## Requirements

- PHP 8.2+
- Laravel 12 or 13
- Filament 5

## Installation

```bash
composer require tomatophp/filament-tomatophp-theme
php artisan filament:assets
```

Register the plugin on your panel, for example in `app/Providers/Filament/AdminPanelProvider.php`:

```php
use TomatoPHP\FilamentTomatoPHPTheme\FilamentTomatoPHPThemePlugin;

$panel->plugin(FilamentTomatoPHPThemePlugin::make());
```

## Options

Every part of the theme is on by default and can be switched off:

```php
FilamentTomatoPHPThemePlugin::make()
    ->brandColors(false)    // keep your own primary/success colors
    ->brandLogo(false)      // keep your own logo and favicon
    ->hatch(false)          // no hatched guest pages and page header bands
    ->squareCorners(false); // keep Filament's rounded corners
```

The brand colors and logo are applied when the plugin is registered, so if you also set `->colors()` or `->brandLogo()`
on the panel, turn the matching option off instead of relying on call order.

## Brand

| Token  | Hex       | Used for                         |
|--------|-----------|----------------------------------|
| Brand  | `#D64524` | Primary color and the mark body  |
| Accent | `#4E9A3E` | Success color and the accent cube |
| Ink    | `#0B1429` | Dark ground                      |
| Paper  | `#F0EBE1` | Light ground                     |

The mark follows the [TomatoPHP brand guide](https://tomatophp.com/en/brand): it stands alone, without a wordmark lockup.
The line work follows the screen-line layout of [fadymondy.com](https://fadymondy.com).

## Testing

```bash
composer test
```

## Code Style

```bash
composer format
```

## Other Filament Packages

Check out our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)

## Security

Please see [SECURITY](.github/SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](https://github.com/fadymondy)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
