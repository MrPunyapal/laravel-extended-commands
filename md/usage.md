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
