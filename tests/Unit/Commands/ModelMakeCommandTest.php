<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function () {
    $this->files = [
        'app/Models/Foo.php',
        'app/Models/Builders/FooBuilder.php',
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
