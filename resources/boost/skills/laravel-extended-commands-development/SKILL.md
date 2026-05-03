---
name: laravel-extended-commands-development
description: "Use this skill when working with mrpunyapal/laravel-extended-commands in a Laravel application. Trigger when the task involves this package's generators for actions, custom Eloquent builders, custom Eloquent collections, concerns, contracts, facades, or the extended `make:model` workflow with `--builder` or `--collection`. Teach the package conventions and generated structure, then use `php artisan help <command>` for full command syntax and options."
license: MIT
metadata:
  author: mrpunyapal
---

# Laravel Extended Commands Development

## When to use this skill

Use this skill when a Laravel task should use this package's generators instead of handwritten boilerplate.

Typical triggers:

- the request mentions `make:action`, `make:builder`, `make:collection`, `make:concern`, `make:contract`, or `make:facade`
- the request wants a model plus its custom builder or collection via `make:model --builder` or `make:model --collection`
- the request needs the package's default namespaces, generated methods, or generated facade accessor behavior

Do not use this skill for unrelated Laravel generators or when the project is not using `mrpunyapal/laravel-extended-commands`.

## Core package conventions

This package exists to generate common Laravel structures in the package's preferred locations instead of leaving those classes to manual boilerplate.

- `make:action` creates actions in `App\Actions`
- `make:builder` creates custom builders in `App\Models\Builders`
- `make:collection` creates custom collections in `App\Models\Collections`
- `make:concern` creates traits in `App\Concerns`
- `make:contract` creates interfaces in `App\Contracts`
- `make:facade` creates facades in `App\Facades`

Prefer the generator over hand-writing the file so the namespace, base class, and method skeleton match the package's conventions.

## High-value behavior to remember

- `make:action` generates `handle()` by default and switches to `__invoke()` with `--invokable`
- `make:builder` and `make:collection` accept `--model` to add generic PHPDoc for the related model
- `make:facade` derives a snake_case accessor from the generated class name
- nested names should be passed directly to the generator instead of creating the file and moving it later
- the extended `make:model` command can scaffold the matching builder and collection and wire `newEloquentBuilder()` or `newCollection()` onto the model

When exact syntax, options, or prompts matter, use Artisan help instead of guessing:

```bash
php artisan help make:action
php artisan help make:builder
php artisan help make:collection
php artisan help make:facade
php artisan help make:model
```

## Workflow guidance

If the task starts from a model and needs custom Eloquent primitives, prefer the model workflow first so the generated imports and methods stay aligned.

If the task starts from a standalone class, use the dedicated generator for that class type.

If the task wants namespaced output, pass the nested name directly to the generator.

## Pitfalls

- do not recreate package-generated boilerplate manually unless the user explicitly wants a custom shape
- do not assume standard Laravel `make:model` also adds builder or collection wiring without this package's flags
- do not invent facade accessors manually when the generator already derives them from the class name

## Verification

1. Confirm the generated file landed in the expected namespace directory.
2. For model-driven scaffolding, confirm both the model and companion builder or collection were generated.
3. If you changed generator behavior, run the relevant package tests.
