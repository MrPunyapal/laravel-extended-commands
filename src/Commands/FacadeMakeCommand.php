<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:facade')]
class FacadeMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:facade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new facade class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Facade';

    /**
     * Build the class with the given name.
     */
    #[Override]
    protected function buildClass($name)
    {
        $class = class_basename($name);
        $accessor = Str::snake($class);

        return str_replace('{{ accessor }}', $accessor, parent::buildClass($name));
    }

    /**
     * Get the stub file for the generator.
     */
    #[Override]
    protected function getStub()
    {
        return $this->resolveStubPath('/../../stubs/facades.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     */
    #[Override]
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Facades';
    }

    /**
     * Get the console command arguments.
     */
    #[Override]
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the facade'],
        ];
    }

    /**
     * Get the console command options.
     */
    #[Override]
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the facade already exists'],
        ];
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     */
    #[Override]
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => 'Enter FacadeName (ex. FileUpload)',
        ];
    }
}
