<?php

use Illuminate\Support\Facades\Artisan;

it('creates facade and service files', function () {

    $facade = app_path('Facades/FileUpload.php');
    $service = app_path('Facades/Services/CommonFileUpload.php');

    if (file_exists($facade)) unlink($facade);
    if (file_exists($service)) unlink($service);

    Artisan::call('make:facade', [
        'facadeName' => 'FileUpload',
        'facadeServiceClass' => 'CommonFileUpload',
    ]);

    expect($facade)->toBeFile();
    expect($service)->toBeFile();

    unlink($facade);
    unlink($service);
});
