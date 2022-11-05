<?php

namespace WisamAlhennawi\LaraFormsBuilder\Commands;

use Illuminate\Console\Command;

class LaraFormsBuilderCommand extends Command
{
    public $signature = 'lara-forms-builder';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
