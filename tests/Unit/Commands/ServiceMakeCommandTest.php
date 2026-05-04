<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

pest()->use(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Services/FooService.php',
        'app/Services/Foo/BarService.php',
    ];
});

it('can generate service file', function (): void {
    $this->artisan('make:service', ['name' => 'FooService'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Services;',
        'class FooService',
    ], 'app/Services/FooService.php');

    $this->assertFilenameNotExists('app/Services/Foo/BarService.php');
});

it('can generate service file with namespace', function (): void {
    $this->artisan('make:service', ['name' => 'Foo\\BarService'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Services\Foo;',
        'class BarService',
    ], 'app/Services/Foo/BarService.php');

    $this->assertFilenameNotExists('app/Services/FooService.php');
});

it('can force overwrite existing service', function (): void {
    $this->artisan('make:service', ['name' => 'FooService'])
        ->assertExitCode(0);

    file_put_contents(base_path('app/Services/FooService.php'), <<<'PHP'
<?php

namespace App\Services;

class FooService
{
    public function handle(): string
    {
        return 'customized';
    }
}
PHP);

    $this->artisan('make:service', ['name' => 'FooService', '--force' => true])
        ->assertExitCode(0);

    $fileContents = file_get_contents(base_path('app/Services/FooService.php'));

    $this->assertIsString($fileContents);
    $this->assertStringContainsString('namespace App\\Services;', $fileContents);
    $this->assertStringContainsString('class FooService', $fileContents);
    $this->assertStringNotContainsString('customized', $fileContents);
});

it('fails when service already exists without force', function (): void {
    $this->artisan('make:service', ['name' => 'FooService'])
        ->assertExitCode(0);

    file_put_contents(base_path('app/Services/FooService.php'), <<<'PHP'
<?php

namespace App\Services;

class FooService
{
    public function handle(): string
    {
        return 'customized';
    }
}
PHP);

    $this->artisan('make:service', ['name' => 'FooService'])
        ->expectsOutputToContain('Service already exists.')
        ->assertExitCode(0);

    $fileContents = file_get_contents(base_path('app/Services/FooService.php'));

    $this->assertIsString($fileContents);
    $this->assertStringContainsString('customized', $fileContents);
});
