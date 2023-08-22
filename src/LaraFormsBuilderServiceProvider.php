<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WisamAlhennawi\LaraFormsBuilder\Commands\LaraFormsBuilderCommand;

class LaraFormsBuilderServiceProvider extends PackageServiceProvider
{
    public function boot(): void
    {
        /*
         * Bootstrap any package services.
         */
        $this->publishes([
            __DIR__ . '/../resources/css/lara-forms-builder.css' => resource_path('css/lara-forms-builder.css'),
        ], 'lara-forms-builder-css');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lara-forms-builder');
    }

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
            ->hasCommand(LaraFormsBuilderCommand::class);
    }
}
