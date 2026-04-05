# This package has some commands which are not available in laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mrpunyapal/laravel-extended-commands.svg?style=flat-square)](https://packagist.org/packages/mrpunyapal/laravel-extended-commands)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mrpunyapal/laravel-extended-commands/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mrpunyapal/laravel-extended-commands/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mrpunyapal/laravel-extended-commands.svg?style=flat-square)](https://packagist.org/packages/mrpunyapal/laravel-extended-commands)

## Installation

You can install the package via composer:

```bash
composer require mrpunyapal/laravel-extended-commands
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-extended-commands-config"
```

This is the contents of the published config file:

```php
return [
    // Nothing to configure yet.
];
```

## Usage

### Make View

Create a new Blade view file. By default, the view is generated with a layout structure using `@extends` and automatically creates the layout file if it doesn't exist.

```bash
php artisan make:view {name}
```

This generates a view in `resources/views/{name}.blade.php` with the default `app` layout and automatically creates `resources/views/layouts/app.blade.php` if it doesn't exist.

**Example:**
```bash
php artisan make:view dashboard
```

Creates:
- `resources/views/dashboard.blade.php` (extends 'layouts.app')
- `resources/views/layouts/app.blade.php` (if it doesn't exist)

Create a view with a custom layout:

```bash
php artisan make:view {name} --layout={layout}
```

**Example:**
```bash
php artisan make:view admin/users --layout=admin/main
```

Creates:
- `resources/views/admin/users.blade.php` (extends 'layouts.admin/main')
- `resources/views/layouts/admin/main.blade.php` (if it doesn't exist)

Create a simple view without layout (no `@extends`):

```bash
php artisan make:view {name} --simple
```

**Example:**
```bash
php artisan make:view components/alert --simple
```

Creates only `resources/views/components/alert.blade.php` with basic HTML structure, no layout.

Force overwrite an existing view:

```bash
php artisan make:view {name} --force
```

**Nested Views:**

The command supports dot notation or slash notation for nested directories:

```bash
php artisan make:view admin.users.index
# or
php artisan make:view admin/users/index
```

Both create `resources/views/admin/users/index.blade.php`.

### Make Builder

```bash
php artisan make:builder {name}
```
### Make Builder with Model

```bash
php artisan make:builder {name} --model={model}
```

### Make Collection

Create a new Eloquent custom collection class. By default the class is generated into the `App\Models\Collections` namespace and extends `Illuminate\Database\Eloquent\Collection`.

```bash
php artisan make:collection {name}
```

Create a collection with a model generic PHPDoc (adds `@template` and `@extends` in the generated class):

```bash
php artisan make:collection {name} --model={model}
```

When generating a model you can also scaffold a collection for it and inject a `newCollection()` method into the model using the `--collection` flag:

```bash
php artisan make:model {name} --collection
```

This will generate `App\Models\Collections\{Name}Collection` and add a `newCollection(array $models = [])` method to the model which returns the new collection instance.


### Make Model with Builder

```bash
php artisan make:model {name} --builder
```

### Make Action

Create a new action class. By default the class is generated into the `App\Actions` namespace and contains a `handle()` method.

```bash
php artisan make:action {name}
```

Create an invokable action (generates an `__invoke` method):

```bash
php artisan make:action {name} --invokable
```

Force overwrite an existing action file:

```bash
php artisan make:action {name} --force
```

### Make Concern

Create a new concern (trait). By default the trait is generated into the `App\\Concerns` namespace.

```bash
php artisan make:concern {name}
```

Force overwrite an existing concern file:

```bash
php artisan make:concern {name} --force
```

### Make Contract

Create a new contract (interface). By default the interface is generated into the `App\\Contracts` namespace.

```bash
php artisan make:contract {name}
```

Force overwrite an existing contract file:

```bash
php artisan make:contract {name} --force
```

### Make Facade

Create a new facade class. By default the class is generated into the `App\Facades` namespace and extends `Illuminate\Support\Facades\Facade`.

```bash
php artisan make:facade {name}
```

The command will automatically generate a snake_case accessor based on the facade name. For example:

```bash
php artisan make:facade FileUpload
```

This generates `App\Facades\FileUpload` with accessor `file_upload`.

Force overwrite an existing facade file:

```bash
php artisan make:facade {name} --force
```

If the name argument is not provided, you will be prompted to enter it:

```bash
php artisan make:facade
# Enter FacadeName (ex. FileUpload): Payment
```

You can also create facades with namespaces:

```bash
php artisan make:facade Payment\Stripe
```

This generates `App\Facades\Payment\Stripe` with accessor `stripe`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Punyapal Shah](https://github.com/MrPunyapal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
