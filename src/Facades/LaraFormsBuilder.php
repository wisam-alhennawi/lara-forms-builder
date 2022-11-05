<?php

namespace WisamAlhennawi\LaraFormsBuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \WisamAlhennawi\LaraFormsBuilder\LaraFormsBuilder
 */
class LaraFormsBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \WisamAlhennawi\LaraFormsBuilder\LaraFormsBuilder::class;
    }
}
