# Installation

## Requirements

- PHP `^8.3`, `^8.4`, or `^8.5`
- Laravel 11, 12, or 13

## Install the package

```bash
composer require mrpunyapal/laravel-extended-commands
```

The package's service provider is registered automatically through Laravel's package discovery, so no manual registration is needed. All commands are available right away:

```bash
php artisan list make
```

## Publish the config file

Publishing the config is optional. To publish it:

```bash
php artisan vendor:publish --tag="laravel-extended-commands-config"
```

This copies the package config to `config/extended-commands.php`.

## Laravel Boost

The package ships a Laravel Boost skill for its generators. In a Laravel app that has `laravel/boost` installed, add this package and then discover or refresh the packaged skill:

```bash
php artisan boost:install
php artisan boost:update --discover
```

Boost can then install the `laravel-extended-commands-development` skill for tasks involving this package's custom Artisan generators.

## Verify the installation

Check that the commands are registered:

```bash
php artisan make:action --help
```

You should see the command help output, including the `--invokable` and `--force` options.
