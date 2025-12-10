<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function () {
    $this->files = [
        'app/Models/Collections/FooCollection.php',
        'app/Models/Collections/Foo/BarCollection.php',
    ];
});

it('can generate collection file', function () {
    $this->artisan('make:collection', ['name' => 'FooCollection'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models\Collections;',
        'use Illuminate\Database\Eloquent\Collection;',
        'class FooCollection extends Collection',
    ], 'app/Models/Collections/FooCollection.php');

    $this->assertFilenameNotExists('app/Models/Collections/Foo/BarCollection.php');
});

it('can generate collection file with namespace', function () {
    $this->artisan('make:collection', ['name' => 'Foo\\BarCollection'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models\Collections\Foo;',
        'use Illuminate\Database\Eloquent\Collection;',
        'class BarCollection extends Collection',
    ], 'app/Models/Collections/Foo/BarCollection.php');

    $this->assertFilenameNotExists('app/Models/Collections/FooCollection.php');
});

it('can generate collection file with model option', function (): void {
    $this->artisan('make:collection', ['name' => 'FooCollection', '--model' => 'Foo'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Models\Collections;',
        'class FooCollection extends Collection',
        '@template TModel of Foo',
        '@extends Collection<Foo>',
    ], 'app/Models/Collections/FooCollection.php');
});
