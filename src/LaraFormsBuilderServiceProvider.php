<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WisamAlhennawi\LaraFormsBuilder\Commands\LaraFormsBuilderCommand;

class LaraFormsBuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lara-forms-builder')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasAssets()
            ->hasCommand(LaraFormsBuilderCommand::class);
    }
}
