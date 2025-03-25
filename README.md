# This package will have some commands which are not available in laravel.

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

### Make Builder

```bash
php artisan make:builder {name}
```
### Make Builder with Model

```bash
php artisan make:builder {name} --model={model}
```

### Make Model with Builder

```bash
php artisan make:model {name} --builder
```

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
