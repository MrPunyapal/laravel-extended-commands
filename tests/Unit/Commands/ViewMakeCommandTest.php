<?php

use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Clean up created files before each test
    File::deleteDirectory(resource_path('views/layouts'));
    File::delete(resource_path('views/admin/page.blade.php'));
    File::delete(resource_path('views/dashboard.blade.php'));
    File::delete(resource_path('views/test/simple.blade.php'));
});

// Test basic view creation with default layout
it('generates a view file with default layout', function (): void {
    $this->artisan('make:view', ['name' => 'dashboard'])
        ->assertExitCode(0);

    expect(File::exists(resource_path('views/dashboard.blade.php')))->toBeTrue()->and(File::exists(resource_path('views/layouts/app.blade.php')))->toBeTrue();

    $content = File::get(resource_path('views/dashboard.blade.php'));
    expect($content)->toContain("@extends('layouts.app')");
});

// Test view creation with a specific layout
it('generates a view file and specified layout', function (): void {
    $this->artisan('make:view', ['name' => 'admin/page', '--layout' => 'admin/main'])
        ->assertExitCode(0);

    expect(File::exists(resource_path('views/admin/page.blade.php')))->toBeTrue()->and(File::exists(resource_path('views/layouts/admin/main.blade.php')))->toBeTrue();

    $content = File::get(resource_path('views/admin/page.blade.php'));
    expect($content)->toContain("@extends('layouts.admin/main')");
});

// Test view creation in simple mode (no layout)
it('generates a simple view file without layout', function (): void {
    $this->artisan('make:view', ['name' => 'test/simple', '--simple' => true])
        ->assertExitCode(0);

    expect(File::exists(resource_path('views/test/simple.blade.php')))->toBeTrue();
    // Should not create any layout for simple views
    expect(File::exists(resource_path('views/layouts/app.blade.php')))->toBeFalse();
});
