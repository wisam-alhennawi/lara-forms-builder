<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WisamAlhennawi\LaraFormsBuilder\Commands\LaraFormsBuilderCommand;
use WisamAlhennawi\LaraFormsBuilder\Commands\LaraFormsBuilderSetupCommand;
use WisamAlhennawi\LaraFormsBuilder\Livewire\Modals\Confirmation;

class LaraFormsBuilderServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        parent::boot();
        Livewire::component('modals.confirmation', Confirmation::class);
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
            ->hasAssets()
            ->hasCommand(LaraFormsBuilderSetupCommand::class)
            ->hasCommand(LaraFormsBuilderCommand::class);
    }
}
