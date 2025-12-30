<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

uses(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Facades/FileUpload.php',
        'app/Facades/TestFacade.php',
        'app/Facades/Foo/BarFacade.php',
        'app/Facades/PromptedFacade.php',
        'app/Facades/Payment.php',
        'app/Facades/Payment/Stripe.php',
    ];
});

it('can generate facade file', function (): void {
    $this->artisan('make:facade', [
        'name' => 'FileUpload',
    ])->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades;',
        'use Illuminate\Support\Facades\Facade;',
        'class FileUpload extends Facade',
        'protected static function getFacadeAccessor(): mixed',
        'return "file_upload";',
    ], 'app/Facades/FileUpload.php');

    $this->assertFilenameNotExists('app/Facades/TestFacade.php');
});

it('can generate facade with namespace', function (): void {
    $this->artisan('make:facade', [
        'name' => 'Foo\\BarFacade',
    ])->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades\Foo;',
        'use Illuminate\Support\Facades\Facade;',
        'class BarFacade extends Facade',
        'return "bar_facade";',
    ], 'app/Facades/Foo/BarFacade.php');

    $this->assertFilenameNotExists('app/Facades/FileUpload.php');
});

it('prompts for facade name when not provided', function (): void {
    $this->artisan('make:facade')
        ->expectsQuestion('Enter FacadeName (ex. FileUpload)', 'PromptedFacade')
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades;',
        'class PromptedFacade extends Facade',
        'return "prompted_facade";',
    ], 'app/Facades/PromptedFacade.php');
});

it('generates correct class name with nested namespace', function (): void {
    $this->artisan('make:facade', [
        'name' => 'Payment\Stripe',
    ])->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Facades\Payment;',
        'use Illuminate\Support\Facades\Facade;',
        'class Stripe extends Facade',
        'protected static function getFacadeAccessor(): mixed',
        'return "stripe";',
    ], 'app/Facades/Payment/Stripe.php');
});
