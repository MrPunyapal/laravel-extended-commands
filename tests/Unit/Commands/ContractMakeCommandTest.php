<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

pest()->use(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Contracts/FooContract.php',
        'app/Contracts/Foo/BarContract.php',
    ];
});

it('can generate contract file', function (): void {
    $this->artisan('make:contract', ['name' => 'FooContract'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Contracts;',
        'interface FooContract',
    ], 'app/Contracts/FooContract.php');

    $this->assertFilenameNotExists('app/Contracts/Foo/BarContract.php');
});

it('can generate contract file with namespace', function (): void {
    $this->artisan('make:contract', ['name' => 'Foo\\BarContract'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Contracts\Foo;',
        'interface BarContract',
    ], 'app/Contracts/Foo/BarContract.php');

    $this->assertFilenameNotExists('app/Contracts/FooContract.php');
});
