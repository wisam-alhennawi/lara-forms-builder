<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Livewire\Component;
use Livewire\WithFileUploads;
use WisamAlhennawi\LaraFormsBuilder\Traits\FieldIndicator;

abstract class LaraFormComponent extends Component
{
    use FieldIndicator, LaraFormsBuilder, WithFileUploads;

    abstract protected function fields(): array;

    abstract protected function responseCallBack();

    abstract protected function cancel();
}
