<?php

namespace WisamAlhennawi\LaraFormsBuilder\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\ComponentParser;
use Livewire\Features\SupportConsoleCommands\Commands\MakeCommand as LivewireMakeCommand;

class LaraFormsBuilderCommand extends Command
{
    protected ComponentParser $parser;

    protected string $model;

    protected ?string $modelPath;

    protected ?string $langModelFileName;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // TODO fields as optional argument
    protected $signature = 'make:lara-forms-builder
        {name : The name of your Livewire class}
        {model : The name of the model you want to use in this form}
        {modelpath? : The name of the model you want to use in this form}
        {--langModelFileName= : The name of the lang file of the model}
        {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Laravel Livewire Form class by WisamAlhennawi\LaraFormsBuilder.';

    /**
     * Generate the Datatable component
     */
    public function handle(): void
    {
        $this->parser = new ComponentParser(
            config('livewire.class_namespace'),
            config('livewire.view_path'),
            $this->argument('name')
        );

        $livewireMakeCommand = new LivewireMakeCommand();

        if ($livewireMakeCommand->isReservedClassName($name = $this->parser->className())) {
            $this->line("<fg=red;options=bold>Class is reserved:</> {$name}");

            return;
        }

        $this->model = Str::studly($this->argument('model'));
        $this->modelPath = $this->argument('modelpath') ?? null;
        $this->langModelFileName = $this->option('langModelFileName') ?? null;

        $force = $this->option('force');

        $this->createClass($force);

        $this->info('Livewire Form Created: '.$this->parser->className());
    }

    /**
     * @throws Exception
     */
    protected function createClass(bool $force = false): bool
    {
        $classPath = $this->parser->classPath();

        if (! $force && File::exists($classPath)) {
            $this->line("<fg=red;options=bold>Class already exists:</> {$this->parser->relativeClassPath()}");

            return false;
        }

        $this->ensureDirectoryExists($classPath);

        File::put($classPath, $this->classContents());

        return $classPath;
    }

    /**
     * @param  mixed  $path
     */
    protected function ensureDirectoryExists($path): void
    {
        if (! File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * @throws Exception
     */
    public function classContents(): string
    {
        return str_replace(
            ['[namespace]', '[class]', '[model]', '[object]', '[model_import]', '[fields]'],
            [
                $this->parser->classNamespace(),
                $this->parser->className(),
                $this->model,
                lcfirst($this->model),
                $this->getModelImport(),
                $this->generateFields($this->getModelImport()),
            ],
            file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'lara-forms-builder.stub')
        );
    }

    public function getModelImport(): string
    {
        if (File::exists(app_path('Models/'.$this->model.'.php'))) {
            return 'App\Models\\'.$this->model;
        }

        if (File::exists(app_path($this->model.'.php'))) {
            return 'App\\'.$this->model;
        }

        if (isset($this->modelPath)) {
            if (File::exists(rtrim($this->modelPath, '/').'/'.$this->model.'.php')) {

                return Str::studly(str_replace('/', '\\', $this->modelPath)).$this->model;
            }
        }

        $this->error('Could not find path to model.');

        return 'App\Models\\'.$this->model;
    }

    /**
     * @throws Exception
     */
    private function generateFields(string $modelName): string
    {
        $model = new $modelName();

        if ($model instanceof Model === false) {
            throw new Exception('Invalid model given.');
        }

        $getFillable = array_merge(
            $model->getFillable(),
            ['created_at', 'updated_at']
        );

        $fields = "[\n".'                '."'fields' => [\n";

        // TODO check if fields argument is passed and use it to generate fields otherwise use fillable

        // get casted fields to generate input types
        $castedFields = $model->getCasts();
        foreach ($getFillable as $field) {
            if (in_array($field, $model->getHidden())) {
                continue;
            }

            $fieldType = '';
            $inputType = '';
            $label = $this->langModelFileName
                ? '__('."'".'models/'.$this->langModelFileName.'.fields.'.$field."'".')'
                : '__('."'".$field."'".')';

            // TODO add select, radio, textarea, etc. and improve input types
            if (array_key_exists($field, $castedFields)) {
                $type = $castedFields[$field];
                switch ($type) {
                    case 'integer':
                        $fieldType = 'input';
                        $inputType = 'number';
                        break;
                    case 'string':
                        $fieldType = 'input';
                        break;
                    case 'boolean':
                        $fieldType = 'checkbox';
                        break;
                    case 'date':
                    case 'datetime':
                        $fieldType = 'date-picker';
                        break;
                    default:
                        // code...
                        break;
                }
            }
            $fields .= '                    '."'".$field."'".' => ['."\n".
                                                    "                        'type' => '".$fieldType."',"."\n";
            // add input type if exists
            if ($fieldType === 'input' && $inputType !== '') {
                $fields .= "                        'inputType' => '".$inputType."',"."\n";
            }
            $fields .= "                        'label' => ".$label.','."\n".

                                                    '                    ],'."\n";
        }
        $fields .= '                ]'."\n".'            ]';

        return $fields;
    }
}
