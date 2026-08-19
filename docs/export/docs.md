# Commands

# Commands

All commands extend Laravel's `GeneratorCommand`, so they accept the standard options such as `--force` and will prompt for missing arguments.

## make:action

Creates a new action class under `App\Actions`.

```bash
php artisan make:action ChargeAction
# app/Actions/ChargeAction.php
```

### Invokable actions

By default the generated action has a `handle()` method. Pass `--invokable` to generate an `__invoke()` method instead:

```bash
php artisan make:action ChargeAction --invokable
```

```php
class ChargeAction
{
    public function __invoke()
    {
        //
    }
}
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--invokable` | | Generate an invokable action with an `__invoke` method |
| `--force` | `-f` | Create the class even if the action already exists |

## make:builder

Creates a new Eloquent query builder under `App\Models\Builders`. The generated class extends `Illuminate\Database\Eloquent\Builder`.

```bash
php artisan make:builder OrderBuilder
# app/Models/Builders/OrderBuilder.php
```

### Typing a builder to a model

Pass `--model` to add `@template` and `@extends` PHPDoc for IDE support:

```bash
php artisan make:builder OrderBuilder --model=Order
```

```php
/** @template TModel of Order */
/** @extends Builder<Order> */
class OrderBuilder extends Builder
{
    //
}
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--model` | `-m` | The model that the builder applies to |
| `--force` | `-f` | Create the class even if the builder already exists |

## make:collection

Creates a new Eloquent collection under `App\Models\Collections`. The generated class extends `Illuminate\Database\Eloquent\Collection`.

```bash
php artisan make:collection OrderCollection
# app/Models/Collections/OrderCollection.php
```

### Typing a collection to a model

Pass `--model` to add `@template` and `@extends` PHPDoc:

```bash
php artisan make:collection OrderCollection --model=Order
```

```php
/** @template TModel of Order */
/** @extends Collection<Order> */
class OrderCollection extends Collection
{
    //
}
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--model` | `-m` | The model that the collection contains |
| `--force` | `-f` | Create the class even if the collection already exists |

## make:concern

Creates a new concern (trait) under `App\Concerns`.

```bash
php artisan make:concern HasTenant
# app/Concerns/HasTenant.php
```

```php
trait HasTenant
{
    //
}
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--force` | `-f` | Create the trait even if the concern already exists |

## make:contract

Creates a new contract (interface) under `App\Contracts`.

```bash
php artisan make:contract PaymentGateway
# app/Contracts/PaymentGateway.php
```

```php
interface PaymentGateway
{
    //
}
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--force` | `-f` | Create the interface even if the contract already exists |

## make:facade

Creates a new facade under `App\Facades`, extending `Illuminate\Support\Facades\Facade`.

```bash
php artisan make:facade FileUpload
# app/Facades/FileUpload.php
```

The accessor is derived automatically as the snake_case form of the class name:

```php
protected static function getFacadeAccessor(): mixed
{
    return "file_upload";
}
```

### Prompting

The `name` argument is required but interactive. If you run the command without it, you are prompted:

```bash
php artisan make:facade
# Enter FacadeName (ex. FileUpload): Payment
```

### Nested namespaces

The accessor always uses the class basename:

```bash
php artisan make:facade Payment\Stripe
# App\Facades\Payment\Stripe with accessor "stripe"
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--force` | `-f` | Create the class even if the facade already exists |

## make:model

Extends Laravel's built-in `make:model` with two new flags: `--builder` and `--collection`.

```bash
php artisan make:model Order
# app/Models/Order.php
```

### Model with a builder

```bash
php artisan make:model Order --builder
```

Creates `app/Models/Builders/OrderBuilder.php` and adds a `newEloquentBuilder()` method to the model.

### Model with a collection

```bash
php artisan make:model Order --collection
```

Creates `app/Models/Collections/OrderCollection.php` and adds a `newCollection()` method to the model.

### Model with both

```bash
php artisan make:model Order --builder --collection
```

### All scaffold

The `--all` flag generates every related file — factory, migration, seeder, controller, policy, form request, resource — plus the builder and collection:

```bash
php artisan make:model Order --all
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--builder` | `-b` | Create a new builder for the model |
| `--collection` | | Create a new collection for the model |
| `--force` | `-f` | (inherited) Create the class even if the model already exists |

All other options from Laravel's `make:model` (`--factory`, `--migration`, `--seeder`, `--controller`, `--policy`, `--form-request`, `--resource`, `--all`, `--api`) continue to work unchanged.

## make:service

Creates a new service class under `App\Services`.

```bash
php artisan make:service PaymentService
# app/Services/PaymentService.php
```

```php
class PaymentService
{
    //
}
```

### Options

| Option | Short | Description |
| --- | --- | --- |
| `--force` | `-f` | Create the class even if the service already exists |


---

# Configuration

# Configuration

The package ships with a small config file at `config/extended-commands.php`.

## Publishing the config

```bash
php artisan vendor:publish --tag="laravel-extended-commands-config"
```

## Contents

The config is currently empty — there is nothing to configure yet:

```php
<?php

declare(strict_types=1);

// config for MrPunyapal/LaravelExtendedCommands
return [

];
```

Once the file is published to `config/extended-commands.php`, you can adjust it just like any other Laravel config file. If you do not publish it, the package falls back to its own defaults, so nothing breaks either way.


---

# Installation

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


---

# Laravel Extended Commands

# Laravel Extended Commands

A Laravel package that adds Artisan generator commands missing from the framework — actions, builders, collections, concerns, contracts, facades, and services — plus builder/collection scaffolding for your models.

Built on top of the standard Laravel generator commands, so every generated file follows your project's existing conventions for namespaces and paths. The package registers its commands automatically and works with Laravel 11, 12, and 13.

## Commands

| Command | Generates |
| --- | --- |
| `make:action` | An action class under `App\Actions` |
| `make:builder` | An Eloquent query builder under `App\Models\Builders` |
| `make:collection` | An Eloquent collection under `App\Models\Collections` |
| `make:concern` | A trait under `App\Concerns` |
| `make:contract` | An interface under `App\Contracts` |
| `make:facade` | A facade under `App\Facades` |
| `make:model` | A model, optionally with a builder and/or collection |
| `make:service` | A service class under `App\Services` |

## Highlights

- **Model + builder/collection in one step** — `make:model Foo --builder --collection` wires `newEloquentBuilder()` and `newCollection()` into the model automatically.
- **Generics-aware stubs** — passing `--model` to `make:builder` or `make:collection` adds `@template` and `@extends` PHPDoc for IDE support.
- **Fully auto-discovered** — no manual service provider registration required.

## Next steps

- [Installation](installation/) — install the package in your Laravel app.
- [Configuration](configuration/) — the package config file.
- [Usage](usage/) — namespaces, sub-namespaces, and model integration.
- [Commands](commands/) — every command with examples and options.


---

# Usage

# Usage

Every generator creates a class in a fixed namespace and adds an empty method body (`//`) for you to fill in.

## Default namespaces

| Command | Default namespace | Generated file |
| --- | --- | --- |
| `make:action` | `App\Actions` | `app/Actions/{Name}.php` |
| `make:builder` | `App\Models\Builders` | `app/Models/Builders/{Name}Builder.php` |
| `make:collection` | `App\Models\Collections` | `app/Models/Collections/{Name}Collection.php` |
| `make:concern` | `App\Concerns` | `app/Concerns/{Name}Concern.php` |
| `make:contract` | `App\Contracts` | `app/Contracts/{Name}Contract.php` |
| `make:facade` | `App\Facades` | `app/Facades/{Name}.php` |
| `make:model` | `App\Models` | `app/Models/{Name}.php` |
| `make:service` | `App\Services` | `app/Services/{Name}Service.php` |

## Sub-namespaces

Pass a namespaced name to nest the generated class under a sub-directory:

```bash
php artisan make:action Billing\ChargeAction
```

This generates `app/Actions/Billing/ChargeAction.php` in the `App\Actions\Billing` namespace. Nested names work for every command:

```bash
php artisan make:facade Payment\Stripe
# app/Facades/Payment/Stripe.php — App\Facades\Payment\Stripe
```

## Model integration

### Model with a custom builder

```bash
php artisan make:model Order --builder
```

This generates two files:

1. `app/Models/Order.php` — the model, with a `newEloquentBuilder()` method added:

```php
public function newEloquentBuilder($query): OrderBuilder
{
    return new OrderBuilder($query);
}
```

2. `app/Models/Builders/OrderBuilder.php` — a builder extending `Illuminate\Database\Eloquent\Builder`, typed against the model:

```php
/** @template TModel of \App\Models\Order */
/** @extends Builder<\App\Models\Order> */
class OrderBuilder extends Builder
{
    //
}
```

### Model with a custom collection

```bash
php artisan make:model Order --collection
```

This generates `app/Models/Collections/OrderCollection.php` and adds a `newCollection()` method to the model:

```php
public function newCollection(array $models = []): OrderCollection
{
    return new OrderCollection($models);
}
```

### Both at once

```bash
php artisan make:model Order --builder --collection
```

The `--all` flag from Laravel's built-in `make:model` also scaffolds the builder and collection, alongside the factory, migration, seeder, controller, and other files:

```bash
php artisan make:model Order --all
```

## Overwriting existing files

Every generator refuses to overwrite an existing file unless you pass `--force` (short: `-f`):

```bash
php artisan make:service PaymentService --force
```

Without `--force`, an existing class fails with a message such as `Service already exists.`

