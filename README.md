# Laravel Admin Panel Inertia Vue
Laravel package to create laravel admin panel using inertia vue

## Installation

Require this package with composer.

```bash
composer require mark-villudo/laravel-admin-panel-inertia-vue
```


## Setup

[@resources](https://github.com/MarkVilludo/cms-template-laravel-inertia-vue-custom)


### Publish assets and layouts
Register Service Provider in bootstrap/providers.php (optional) because all providers in package is autoload in laravel

```bash
    MarkVilludo\AdminPanelInertiaVue\CMSServicedProvider::class,
```

### Publish `resources/assets` and `resources/js/Layouts`

```bash
php artisan vendor:publish --tag=cms-assets
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
