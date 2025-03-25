<?php

namespace MrPunyapal\LaravelExtendedCommands;

use MrPunyapal\LaravelExtendedCommands\Commands\BuilderMakeCommand;
use MrPunyapal\LaravelExtendedCommands\Commands\ModelMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelExtendedCommandsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-extended-commands')
            ->hasConfigFile()
            ->hasCommands([
                BuilderMakeCommand::class,
                ModelMakeCommand::class,
            ]);

        $this->app->extend('command.model.make', function ($app) {
            return new ModelMakeCommand($app['files']);
        });
    }
}
