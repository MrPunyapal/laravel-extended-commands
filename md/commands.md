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
