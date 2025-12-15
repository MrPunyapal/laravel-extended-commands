<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:collection')]
class CollectionMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new collection class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Collection';

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    #[Override]
    protected function alreadyExists($rawName)
    {
        return class_exists($rawName) ||
               $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/../../stubs/collection.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @return string
     */
    protected function resolveStubPath(string $stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    #[Override]
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models\Collections';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    #[Override]
    protected function buildClass($name)
    {
        $replace = $this->buildModelReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a model.
     *
     * @return array<string, string>
     */
    protected function buildModelReplacements(): array
    {
        $replacements = [];

        if ($this->option('model')) {
            $modelNamespace = $this->option('model');

            $replacements['{{ modelPhpdoc }}'] = <<<EOT
            /**
             * @template TModel of $modelNamespace
             *
             * @extends Collection<$modelNamespace>
             */
            EOT;
        } else {
            $replacements["{{ modelPhpdoc }}\n"] = '';
            $replacements["{{ modelPhpdoc }}\r\n"] = '';
        }

        return $replacements;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The model that the collection contains'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the collection already exists'],
        ];
    }
}
