<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function () {
    $this->files = [
        'app/Models/Foo.php',
        'app/Models/Builders/FooBuilder.php',
        'app/Models/Collections/FooCollection.php',
    ];
});

it('can generate model file', function () {
    $this->artisan('make:model', ['name' => 'Foo'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models;',
        'class Foo extends Model',
    ], 'app/Models/Foo.php');

    $this->assertFilenameNotExists('app/Models/Builders/FooBuilder.php');
    $this->assertFilenameNotExists('app/Models/Collections/FooCollection.php');
});

it('can generate model with builder', function (): void {
    $this->artisan('make:model', ['name' => 'Foo', '--builder' => true])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models;',
        'class Foo extends Model',
        'use App\Models\Builders\FooBuilder;',
        '@return FooBuilder<Foo>',
        'public function newEloquentBuilder($query): FooBuilder',
        'return new FooBuilder($query);',
    ], 'app/Models/Foo.php');

    $this->assertFileContains([
        'namespace App\Models\Builders;',
        'use Illuminate\Database\Eloquent\Builder;',
        'class FooBuilder extends Builder',
        '@template TModel of \\App\\Models\\Foo',
        '@extends Builder<\\App\\Models\\Foo>',
    ], 'app/Models/Builders/FooBuilder.php');
});

it('can generate model with collection', function (): void {
    $this->artisan('make:model', ['name' => 'Foo', '--collection' => true])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models;',
        'class Foo extends Model',
        'use App\Models\Collections\FooCollection;',
        '@return FooCollection<Foo>',
        'public function newCollection(array $models = []): FooCollection',
        'return new FooCollection($models);',
    ], 'app/Models/Foo.php');

    $this->assertFileContains([
        'namespace App\Models\Collections;',
        'use Illuminate\Database\Eloquent\Collection;',
        'class FooCollection extends Collection',
        '@template TModel of \\App\\Models\\Foo',
        '@extends Collection<\\App\\Models\\Foo>',
    ], 'app/Models/Collections/FooCollection.php');
});
