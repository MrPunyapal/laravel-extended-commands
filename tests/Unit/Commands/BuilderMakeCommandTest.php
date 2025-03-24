<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function () {
    $this->files = [
        'app/Models/Builders/FooBuilder.php',
        'app/Models/Builders/Foo/BarBuilder.php',
    ];
});

it('can generate builder file', function () {
    $this->artisan('make:builder', ['name' => 'FooBuilder'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models\Builders;',
        'use Illuminate\Database\Eloquent\Builder;',
        'class FooBuilder extends Builder',
    ], 'app/Models/Builders/FooBuilder.php');

    $this->assertFilenameNotExists('app/Models/Builders/Foo/BarBuilder.php');
});

it('can generate builder file with namespace', function () {
    $this->artisan('make:builder', ['name' => 'Foo\BarBuilder'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models\Builders\Foo;',
        'use Illuminate\Database\Eloquent\Builder;',
        'class BarBuilder extends Builder',
    ], 'app/Models/Builders/Foo/BarBuilder.php');

    $this->assertFilenameNotExists('app/Models/Builders/FooBuilder.php');
});

it('can generate builder file with model option', function (): void {
    $this->artisan('make:builder', ['name' => 'FooBuilder', '--model' => 'Foo'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models\Builders;',
        'class FooBuilder extends Builder',
        '@template TModel of Foo',
        '@extends Builder<Foo>',
    ], 'app/Models/Builders/FooBuilder.php');
});
