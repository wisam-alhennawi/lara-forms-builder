<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Livewire\Component;
use Livewire\WithFileUploads;

abstract class LaraFormComponent extends Component
{
    use LaraFormsBuilder, WithFileUploads;

    abstract protected function fields(): array;

    abstract protected function responseCallBack();

    abstract protected function cancel();
}
