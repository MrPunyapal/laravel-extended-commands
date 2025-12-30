<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Facades/FileUpload.php',
        'app/Facades/Services/CommonFileUpload.php',
        'app/Facades/TestFacade.php',
        'app/Facades/Services/TestService.php',
        'app/Facades/Foo/BarFacade.php',
        'app/Facades/Services/Foo/BarService.php',
    ];
});

it('can generate facade and service files', function (): void {
    $this->artisan('make:facade', [
        'name' => 'FileUpload',
        'serviceClass' => 'CommonFileUpload',
    ])->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades;',
        'use Illuminate\Support\Facades\Facade;',
        'class FileUpload extends Facade',
        'protected static function getFacadeAccessor(): mixed',
        'return "commonfileupload";',
    ], 'app/Facades/FileUpload.php');

    $this->assertFileContains([
        'namespace App\Facades\Services;',
        'class CommonFileUpload',
        'public static function startToMakingFunction()',
    ], 'app/Facades/Services/CommonFileUpload.php');

    $this->assertFilenameNotExists('app/Facades/TestFacade.php');
    $this->assertFilenameNotExists('app/Facades/Services/TestService.php');
});

it('can generate facade with namespace', function (): void {
    $this->artisan('make:facade', [
        'name' => 'Foo\\BarFacade',
        'serviceClass' => 'Foo\\BarService',
    ])->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades\Foo;',
        'use Illuminate\Support\Facades\Facade;',
        'class BarFacade extends Facade',
    ], 'app/Facades/Foo/BarFacade.php');

    $this->assertFileContains([
        'namespace App\Facades\Services\Foo;',
        'class BarService',
    ], 'app/Facades/Services/Foo/BarService.php');

    $this->assertFilenameNotExists('app/Facades/FileUpload.php');
    $this->assertFilenameNotExists('app/Facades/Services/CommonFileUpload.php');
});

it('prompts for service class when not provided', function (): void {
    $this->artisan('make:facade', [
        'name' => 'TestFacade',
    ])
        ->expectsQuestion('Enter FacadeServiceClass (ex. CommonFileUpload)', 'TestService')
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades;',
        'class TestFacade extends Facade',
    ], 'app/Facades/TestFacade.php');

    $this->assertFileContains([
        'namespace App\Facades\Services;',
        'class TestService',
    ], 'app/Facades/Services/TestService.php');
});
