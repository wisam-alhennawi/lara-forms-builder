<?php

namespace WisamAlhennawi\LaraFormsBuilder\Commands;

use Composer\InstalledVersions;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LaraFormsBuilderSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:lara-forms-builder-setup {--stv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    protected bool $isJetstreamInstalled = false;

    protected bool $isLaraFormsBuilderConfigFilePublished = false;

    protected bool $isConfirmationModalIncluded = false;

    protected bool $isLaraFormsBuilderCSSFilePublished = false;

    protected bool $isPikadayInstalled = false;

    protected bool $isMomentInstalled = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->alertForFreshProject();
        $this->checkEnvironment();
        $this->runInstallations();
    }

    protected function alertForFreshProject(): void
    {
        $this->components->warn('Auto setup for this package is recommended to run only into a fresh Laravel project.');
        $this->line('If you install this package into an existing Laravel application in this case we recommended to configure this package manually as it explained in the documentation [https://github.com/wisam-alhennawi/lara-forms-builder].');
        if (! $this->components->confirm('Do You Want To Continue And Start The Setup ?', true)) {
            exit;
        }
    }

    protected function checkEnvironment(): void
    {
        Log::info('LFB Jetstream = '.InstalledVersions::isInstalled('laravel/jetstream'));
        //check if jetstream version 3 installed
        if (InstalledVersions::isInstalled('laravel/jetstream')) {
            $jetstreamVersion = InstalledVersions::getVersion('laravel/jetstream');
            $jetstreamVersionAsArray = collect(explode('.', $jetstreamVersion))->slice(0, 1)->toArray();
            if ($jetstreamVersionAsArray[0] == 3) {
                $this->isJetstreamInstalled = true;
            } else {
                $this->warn('This Package Only Supports laravel/jetstream V3 And Your Version Is '.$jetstreamVersionAsArray[0]);
                exit;
            }
        }

        // check if lara-forms-builder config file is published
        $this->isLaraFormsBuilderConfigFilePublished = File::exists(config_path('lara-forms-builder.php'));

        // check if lara-forms-builder CSS file is published
        $this->isLaraFormsBuilderCSSFilePublished = File::exists(public_path('/vendor/lara-forms-builder/css/lara-forms-builder.css'));

        // check if is Confirmation Modal is Included in the layouts/app.blade.php
        if (File::exists(resource_path('views/layouts/app.blade.php'))) {
            $this->isConfirmationModalIncluded = Str::contains(
                file_get_contents(resource_path('views/layouts/app.blade.php')),
                "@livewire('modals.confirmation')"
            );
        }

        //check if pikaday & moment npm packages installed
        if (File::exists(base_path('package.json'))) {
            $data = json_decode(file_get_contents(base_path('package.json')), true);
            // check dependencies{} object
            if (isset($data['dependencies'])) {
                if (array_key_exists('pikaday', $data['dependencies'])) {
                    $this->isPikadayInstalled = true;
                }
                if (array_key_exists('moment', $data['dependencies'])) {
                    $this->isMomentInstalled = true;
                }
            }
            // check devDependencies{} object
            if (isset($data['devDependencies'])) {
                if (array_key_exists('pikaday', $data['devDependencies'])) {
                    $this->isPikadayInstalled = true;
                }
                if (array_key_exists('moment', $data['devDependencies'])) {
                    $this->isMomentInstalled = true;
                }
            }
        }
    }

    protected function runInstallations(): void
    {
        $this->installJetstream();
        $this->publishLaraFormsBuilderConfigFile();
        $this->publishLaraFormsBuilderCSSFile();
        $this->includeConfirmationModal();
        $this->installPikadayAndMoment();
        $this->npmActions();
    }

    protected function installJetstream(): void
    {
        if (! $this->option('stv') && ! $this->isJetstreamInstalled) {
            if ($this->components->confirm('This Package Requires (laravel/jetstream:^3.0 with livewire/livewire:^2.0) Do You Want To Install them ?', true)) {
                try {
                    exec('composer require laravel/jetstream:^3.0');
                    exec('php artisan jetstream:install livewire');
                    exec('php artisan migrate');

                    $this->line('');
                    $this->components->info('<options=bold,reverse;fg=green> Jetstream with livewire installed successfully & DB migrated </>');
                } catch (\Throwable $e) {
                    $this->components->error('Jetstream with livewire installation ERROR');
                    exit;
                }
            } else {
                $this->components->warn('Installation Aborted Because Required Packages Not Installed');
                exit;
            }
        }
    }

    protected function publishLaraFormsBuilderConfigFile(): void
    {
        if (! $this->isLaraFormsBuilderConfigFilePublished) {
            $this->call('vendor:publish', ['--tag' => 'lara-forms-builder-config', '--force' => true]);

            // add lara-forms-builder-config to the content[] in tailwind.config.js
            (new Filesystem)
                ->replaceInFile(
                    "'./resources/views/**/*.blade.php',",
                    "'./resources/views/**/*.blade.php',\n        './config/lara-forms-builder.php',",
                    base_path('tailwind.config.js')
                );

            $this->components->info('<options=bold,reverse;fg=green> (lara-forms-builder-config) published successfully & added to tailwind.config.js </>');
        }
    }

    protected function publishLaraFormsBuilderCSSFile(): void
    {
        if (! $this->isLaraFormsBuilderCSSFilePublished) {
            $this->call('vendor:publish', ['--tag' => 'lara-forms-builder-assets', '--force' => true]);

            // import lara-forms-builder-assets in app.css
            (new Filesystem)
                ->prepend(
                    resource_path('/css/app.css'),
                    '@import "../../public/vendor/lara-forms-builder/css/lara-forms-builder.css";'."\n"
                );

            // update the colors{} object in tailwind.config.js
            (new Filesystem)
                ->replaceInFile(
                    "            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },",
                    '            fontFamily: {'."\n".
                           "                 sans: ['Figtree', ...defaultTheme.fontFamily.sans],"."\n".
                           '            },'."\n".
                           '            colors: {'."\n".
                           "                'primary': '', // #7c8e63"."\n".
                           "                'secondary': '', // #aebf85"."\n".
                           "                'danger': '' // #DC3545"."\n".
                           '            },',

                    base_path('tailwind.config.js')
                );

            $this->components->info('<options=bold,reverse;fg=green> (lara-forms-builder-assets) published successfully & imported in /resource/css/app.css & colors added in tailwind.config.js </>');
        }
    }

    protected function includeConfirmationModal(): void
    {
        if (! $this->isConfirmationModalIncluded) {
            if ($this->components->confirm('Do You Want To Include Modal in app.blade layout ?', true)) {
                (new Filesystem)
                    ->replaceInFile(
                        "@livewire('navigation-menu')",
                        "@livewire('navigation-menu') \n            @livewire('modals.confirmation')",
                        resource_path('views/layouts/app.blade.php')
                    );
                $this->components->info('<options=bold,reverse;fg=green> Confirmation modal included successfully </>');
            }
        }
    }

    protected function installPikadayAndMoment(): void
    {
        if (! $this->isPikadayInstalled || ! $this->isMomentInstalled) {
            if ($this->components->confirm('Do You Want To Install and config (pikaday & moment) npm packages, These packages are required to use date fields in lara-forms-builder ?', true)) {
                $data = json_decode(file_get_contents(base_path('package.json')), true);
                if (! $this->isPikadayInstalled) {
                    $data['devDependencies']['pikaday'] = '^1.8.2';
                }
                if (! $this->isMomentInstalled) {
                    $data['devDependencies']['moment'] = '^2.29.4';
                }

                file_put_contents(base_path('package.json'), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                // config pikaday in app.js
                (new Filesystem)
                    ->append(
                        resource_path('/js/app.js'),
                        "import Pikaday from 'pikaday';"."\n".'window.Pikaday = Pikaday;'."\n"
                    );

                // import pikaday in app.css
                (new Filesystem)
                    ->prepend(
                        resource_path('/css/app.css'),
                        '@import "pikaday/css/pikaday.css";'."\n"
                    );

                $this->components->info('<options=bold,reverse;fg=green> (pikaday & moment) packages added to package.json successfully & imported in (app.js & app.css) </>');
            }
        }
    }

    protected function npmActions(): void
    {
        try {
            $this->components->info('<options=bold,reverse;fg=green> Running npm install & npm run build </>');
            $this->info(exec('npm install'));
            $this->info(exec('npm run build'));
        } catch (\Throwable $e) {
            $this->components->error('(npm install & npm run build) ERROR');
            exit;
        }
    }
}
