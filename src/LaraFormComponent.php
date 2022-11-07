<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Livewire\Component;

abstract class LaraFormComponent extends Component
{
    use LaraFormsBuilder;

    abstract protected function fields(): array;

    abstract protected function responseCallBack();

    abstract protected function cancel();
}
