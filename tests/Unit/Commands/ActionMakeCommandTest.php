<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

pest()->use(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Actions/FooAction.php',
        'app/Actions/Foo/BarAction.php',
    ];
});

it('can generate action file', function (): void {
    $this->artisan('make:action', ['name' => 'FooAction'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Actions;',
        'class FooAction',
        'public function handle()',
    ], 'app/Actions/FooAction.php');

    $this->assertFilenameNotExists('app/Actions/Foo/BarAction.php');
});

it('can generate action file with namespace', function (): void {
    $this->artisan('make:action', ['name' => 'Foo\\BarAction'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Actions\Foo;',
        'class BarAction',
    ], 'app/Actions/Foo/BarAction.php');

    $this->assertFilenameNotExists('app/Actions/FooAction.php');
});

it('can generate invokable action when option provided', function (): void {
    $this->artisan('make:action', ['name' => 'FooAction', '--invokable' => true])
        ->assertExitCode(0);

    $this->assertFileContains([
        'public function __invoke()',
    ], 'app/Actions/FooAction.php');
});
