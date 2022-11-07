<?php

namespace WisamAlhennawi\LaraFormsBuilder\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WisamAlhennawi\LaraFormsBuilder\LaraFormsBuilderServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaraFormsBuilderServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
