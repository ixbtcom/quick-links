# Quickly open resource, and other files from within filament panel in your PHPstorm editor.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/niladam/quick-links.svg?style=flat-square)](https://packagist.org/packages/niladam/quick-links)
[![Total Downloads](https://img.shields.io/packagist/dt/niladam/quick-links.svg?style=flat-square)](https://packagist.org/packages/niladam/quick-links)

Quickly open resource, models, and other files from within your FilamentPHP table in your PHPstorm editor.

> [!IMPORTANT]
> By default quick links render inside the table `description` (see [docs](https://filamentphp.com/docs/3.x/tables/advanced#customizing-the-table-header)). The package appends links and keeps any existing description.

## Permissions

- The package shows a compact list of permissions/Gates for the resource. All observed gate checks (ability plus subject, when available) are listed first; if none were recorded it falls back to Filament Shield resource permissions (when installed).
- Toggle via `QUICK_LINKS_SHOW_PERMISSIONS` (default `true`) or `config/quick-links.php`.
- To record `hasPermissionTo()` / `checkPermissionTo()` calls from Spatie’s trait, optionally add `Niladam\QuickLinks\Concerns\RecordsPermissionChecks` to your `User` instead of the base `HasPermissions` trait. This will keep functionality unchanged but log permission checks for display.

## Installation

You can install the package via composer:

```bash
composer require niladam/quick-links
```

Run the install command:

```bash
php artisan quick-links:install
```

## Configuration

These are the contents of the published config file:

```php

return [
    'enabled' => env('QUICK_LINKS_ENABLED', true),

    /**
     * Currently the supported links that are automatically added to your table are:
     *
     * resource - Opens the resource.
     * model    - Opens the model.
     * env      - Opens the env file.
     */
    'links' => [
        'resource' => env('QUICK_LINKS_SHOW_RESOURCE', true),
        'model' => env('QUICK_LINKS_SHOW_MODEL', true),
        'env' => env('QUICK_LINKS_SHOW_ENV', true),
    ],

    'prefix' => env('QUICK_LINKS_PREFIX', 'Open in PHPStorm:'),
    'separator' => env('QUICK_LINKS_SEPARATOR', ' &bull; '),

    /**
     * Add your resources here that you want to disable the quick links for.
     *
     * Please make sure to add the FQCN of your resource here.
     *
     * Eg: \App\Filament\Resources\OrderResource::class
     */
    'disabled' => [
        //
    ],

    /**
     * Add your files here that you want to enable quick links for.
     *
     * These will be added at the after the resource links.
     *
     * Please make sure to add the full path to your file here.
     *
     * Missing files will be ignored.
     *
     * Eg:
     *      base_path('config/quick-links.php') => 'quick config'
     *      will generate a link with the name 'quick config'
     *      and open the file at base_path('config/quick-links.php')
     */
    'files' => [
        // base_path('config/quick-links.php') => 'quick config',
    ],
];
```

## Conditional disabling

While you can disablee the package entirely by setting the `QUICK_LINKS_ENABLED` environment variable to `false` you can also use a closure to conditionally disable it.

#### Somewhere in a Service Provider..

```php
use Niladam\QuickLinks\Facades\QuickLinks;

// Disable for the user with ID 1
QuickLinks::disableIf(fn() => auth()->id() === 1);

// Disable for a specific role:
QuickLinks::disableIf(fn() => auth()->user()->hasRole('moderator'));
```

#### Disabling for a specific resource using code:

```php
use Niladam\QuickLinks\Facades\QuickLinks;

QuickLinks::disableOn(App\Filament\Resources\OrderResource::class);
```

#### Disabling quicklinks on a specific resource(s) using config

Simply add the FQCN(s) (fully qualified class name) to your resource in the `quick-links.disabled` config option.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Madalin Tache](https://github.com/niladam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
