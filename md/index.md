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
