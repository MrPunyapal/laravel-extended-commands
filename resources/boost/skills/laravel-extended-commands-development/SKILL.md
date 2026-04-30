---
name: laravel-extended-commands-development
description: "Use this skill when working with mrpunyapal/laravel-extended-commands in a Laravel application. Trigger whenever the task involves generating or updating actions, custom Eloquent builders, custom Eloquent collections, concerns, contracts, facades, or using the package's extended `make:model` command with `--builder` or `--collection`. Covers: `make:action`, `make:builder`, `make:collection`, `make:concern`, `make:contract`, `make:facade`, nested namespaces, invokable actions, facade accessor generation, and model-generated `newEloquentBuilder()` and `newCollection()` methods. Do not use for generic Laravel generator tasks that do not rely on this package."
license: MIT
metadata:
  author: mrpunyapal
---

# Laravel Extended Commands Development

## When to use this skill

Use this skill when a Laravel task should be solved with this package's generators instead of writing boilerplate by hand.

Activate it when the request mentions any of these workflows:

- `make:action` for action classes in `app/Actions`
- `make:builder` for custom Eloquent builders in `app/Models/Builders`
- `make:collection` for custom Eloquent collections in `app/Models/Collections`
- `make:concern` for traits in `app/Concerns`
- `make:contract` for interfaces in `app/Contracts`
- `make:facade` for facades in `app/Facades`
- `make:model --builder` or `make:model --collection` for model-driven scaffolding

Do not use this skill for unrelated Laravel generators or when the application is not using `mrpunyapal/laravel-extended-commands`.

## Package overview

This package adds focused Artisan generators for common Laravel structures and extends Laravel's `make:model` command so a model can scaffold its matching builder and collection.

Prefer these generators over handwritten boilerplate so the generated files land in the package's expected namespaces and include the right method skeletons or generic PHPDoc.

## Quick reference

### Actions

Generate an action in `App\Actions`:

```bash
php artisan make:action ProcessOrderAction
```

Generate an invokable action:

```bash
php artisan make:action SyncInventoryAction --invokable
```

`make:action` generates a `handle()` method by default and switches to `__invoke()` when `--invokable` is used.

### Builders

Generate a builder in `App\Models\Builders`:

```bash
php artisan make:builder OrderBuilder
```

Generate a builder with model generics in the PHPDoc:

```bash
php artisan make:builder OrderBuilder --model="\\App\\Models\\Order"
```

### Collections

Generate a collection in `App\Models\Collections`:

```bash
php artisan make:collection OrderCollection
```

Generate a collection with model generics in the PHPDoc:

```bash
php artisan make:collection OrderCollection --model="\\App\\Models\\Order"
```

### Concerns and contracts

Generate a trait in `App\Concerns`:

```bash
php artisan make:concern TracksOrderState
```

Generate an interface in `App\Contracts`:

```bash
php artisan make:contract SendsOrderNotifications
```

### Facades

Generate a facade in `App\Facades`:

```bash
php artisan make:facade Billing
```

Nested names are supported. This generates `App\Facades\Payment\Stripe` and uses `stripe` as the accessor:

```bash
php artisan make:facade Payment\\Stripe
```

If no name is provided, the command prompts for one.

### Extended models

Generate a model and scaffold its custom builder:

```bash
php artisan make:model Order --builder
```

Generate a model and scaffold its custom collection:

```bash
php artisan make:model Order --collection
```

When you use `--builder`, the model gets a `newEloquentBuilder()` method and a matching builder class is generated in `App\Models\Builders`.

When you use `--collection`, the model gets a `newCollection()` method and a matching collection class is generated in `App\Models\Collections`.

## Common patterns

### Use nested names instead of moving files manually

Pass a nested name directly to the generator when you want grouped classes:

```bash
php artisan make:action Billing\\CapturePaymentAction
php artisan make:builder Billing\\InvoiceBuilder
php artisan make:collection Billing\\InvoiceCollection
php artisan make:facade Payment\\Stripe
```

### Let the model command wire the builder or collection

If the request is to create a model plus its custom Eloquent primitives, prefer the model workflow over running every command by hand:

```bash
php artisan make:model Product --builder --collection
```

That keeps the generated model imports and methods aligned with the scaffolded classes.

## Common pitfalls

- Do not hand-write empty boilerplate classes when one of these generators already provides the correct namespace and base class.
- Use `--builder` and `--collection` explicitly on `make:model`; do not assume unrelated `make:model` flags will scaffold these classes.
- When using `--model` with `make:builder` or `make:collection`, pass the exact type you want in the generated PHPDoc.
- Remember that `make:facade` derives the accessor automatically from the generated class name. Nested facades use the final segment for that accessor.
- If the user wants an invokable action, use `--invokable` instead of generating `handle()` and editing it afterward.

## Verification

1. Confirm the generated file was created in the expected namespace directory.
2. For `make:model --builder` and `make:model --collection`, confirm both the model and companion class were generated.
3. Check the generated builder or collection PHPDoc when `--model` was supplied.
4. Run the relevant package tests if you changed generator behavior.
