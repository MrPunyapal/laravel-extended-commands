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
    protected $description = 'Create a new facade and service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Facade';

    /**
     * The service class name.
     *
     * @var string
     */
    protected $serviceClassName;

    /**
     * Execute the console command.
     */
    #[Override]
    public function handle()
    {
        // Get the service class name
        $this->serviceClassName = $this->argument('serviceClass');

        if (empty($this->serviceClassName)) {
            $this->serviceClassName = $this->ask('Enter FacadeServiceClass (ex. CommonFileUpload)');
        }

        if (empty($this->serviceClassName)) {
            $this->error('Service class name is required!');

            return false;
        }

        // Generate facade
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        // Generate service class
        $this->createServiceClass($this->serviceClassName);

        $this->displayRegistrationInstructions($this->serviceClassName);

        return null;
    }

    /**
     * Create the service class for the facade.
     */
    protected function createServiceClass(string $name): void
    {
        $path = $this->getServicePath($name);

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->error("Service class [{$path}] already exists.");

            return;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildServiceClass($name));

        $this->components->info(sprintf('Service class [%s] created successfully.', $path));
    }

    /**
     * Get the destination path for the service class.
     */
    protected function getServicePath(string $name): string
    {
        $name = str_replace('\\', '/', $name);

        return $this->laravel->basePath('app/Facades/Services/'.$name.'.php');
    }

    /**
     * Build the service class with the given name.
     */
    protected function buildServiceClass(string $name): string
    {
        $stub = $this->files->get($this->getServiceStub());

        return $this->replaceServiceNamespace($stub, $name)->replaceServiceClass($stub, $name);
    }

    /**
     * Replace the namespace for the service class.
     */
    protected function replaceServiceNamespace(string &$stub, string $name): static
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}'],
        ];

        $namespace = 'App\\Facades\\Services';

        $normalizedName = str_replace('\\', '/', $name);
        if (str_contains($normalizedName, '/')) {
            $namespace .= '\\'.str_replace('/', '\\', dirname($normalizedName));
        }

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$namespace, $this->rootNamespace(), $this->userProviderModel()],
                $stub
            );
        }

        return $this;
    }

    /**
     * Replace the class name for the service class.
     */
    protected function replaceServiceClass(string &$stub, string $name): string
    {
        $class = str_replace('/', '\\', $name);
        $class = class_basename($class);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get the stub file for the service class.
     */
    protected function getServiceStub(): string
    {
        return $this->resolveStubPath('/../../stubs/facades-services.stub');
    }

    /**
     * Build the class with the given name.
     */
    #[Override]
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceServiceAccessor($stub, $this->serviceClassName);
    }

    /**
     * Replace the service accessor in the stub.
     */
    protected function replaceServiceAccessor(string $stub, string $serviceClassName): string
    {
        $accessor = Str::lower(class_basename(str_replace('/', '\\', $serviceClassName)));

        return str_replace(['{{ serviceAccessor }}', '{{serviceAccessor}}'], $accessor, $stub);
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
     * Display registration instructions for the service provider.
     */
    protected function displayRegistrationInstructions(string $serviceClassName): void
    {
        $accessor = Str::lower(class_basename(str_replace('/', '\\', $serviceClassName)));
        $serviceClass = str_replace('/', '\\', $serviceClassName);

        $this->components->info('Register the facade in your AppServiceProvider:');
        $this->line('');
        $this->line('$this->app->singleton("'.$accessor.'", function ($app) {');
        $this->line('    return new \\App\\Facades\\Services\\'.$serviceClass.'();');
        $this->line('});');
    }

    /**
     * Get the console command arguments.
     */
    #[Override]
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the facade'],
            ['serviceClass', InputArgument::OPTIONAL, 'The name of the service class'],
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
