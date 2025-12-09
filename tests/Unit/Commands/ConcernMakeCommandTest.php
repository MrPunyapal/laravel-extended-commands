<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function () {
    $this->files = [
        'app/Concerns/FooConcern.php',
        'app/Concerns/Foo/BarConcern.php',
    ];
});

it('can generate concern file', function () {
    $this->artisan('make:concern', ['name' => 'FooConcern'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Concerns;',
        'trait FooConcern',
    ], 'app/Concerns/FooConcern.php');

    $this->assertFilenameNotExists('app/Concerns/Foo/BarConcern.php');
});

it('can generate concern file with namespace', function () {
    $this->artisan('make:concern', ['name' => 'Foo\\BarConcern'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Concerns\Foo;',
        'trait BarConcern',
    ], 'app/Concerns/Foo/BarConcern.php');

    $this->assertFilenameNotExists('app/Concerns/FooConcern.php');
});
