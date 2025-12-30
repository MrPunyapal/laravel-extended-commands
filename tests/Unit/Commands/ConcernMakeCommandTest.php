<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

pest()->use(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Concerns/FooConcern.php',
        'app/Concerns/Foo/BarConcern.php',
    ];
});

it('can generate concern file', function (): void {
    $this->artisan('make:concern', ['name' => 'FooConcern'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Concerns;',
        'trait FooConcern',
    ], 'app/Concerns/FooConcern.php');

    $this->assertFilenameNotExists('app/Concerns/Foo/BarConcern.php');
});

it('can generate concern file with namespace', function (): void {
    $this->artisan('make:concern', ['name' => 'Foo\\BarConcern'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Concerns\Foo;',
        'trait BarConcern',
    ], 'app/Concerns/Foo/BarConcern.php');

    $this->assertFilenameNotExists('app/Concerns/FooConcern.php');
});
