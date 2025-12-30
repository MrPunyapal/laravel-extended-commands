<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;

class FacadeMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:facade {facadeName?} {facadeServiceClass?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to create a new facade.';

    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $argument_name_facade = 'facadeName';
        $facadeName = $this->getArgumentValueAndValidation($argument_name_facade, 'Enter FacadeName (ex. FileUpload)');

        $argument_name_facade_service_class = 'facadeServiceClass';
        $facadeServiceClass = $this->getArgumentValueAndValidation($argument_name_facade_service_class, 'Enter FacadeServiceClass (ex. CommonFileUpload)'); 

        $facadepath = $this->getValidatePath('Facades/', $facadeName);
        $facadeServicePath = $this->getValidatePath('Facades/Services/', $facadeServiceClass);

        if ($this->files->exists($facadepath)) {
            $this->warn("Facade File : {$facadepath} Already Exist");
            exit();
        }

        if ($this->files->exists($facadeServicePath)) {
            $this->warn("Facade Service File : {$facadeServicePath} Already Exist");
            exit();
        }

        // Created Directory Facades & its services
        $this->makeDirectory(dirname($facadepath));
        $this->makeDirectory(dirname($facadeServicePath));

        /**
         * Generate Both the Files
         */
        $this->generateFacadeClass($facadepath, $facadeServicePath, $facadeName, $facadeServiceClass);
        $this->generateFacadeServiceClass($facadepath, $facadeServicePath, $facadeName, $facadeServiceClass);
        $this->info("Facades Created");

        $this->info('AppServiceProvider register the code and start');
        $this->info('$this->app->singleton("'.Str::lower($facadeServiceClass).'", function ($app) {
            return app('.$facadeServiceClass.'::class);
        });');
    }

    
    public function getValidatePath($path, $filename)
    {
        return app_path($path . $filename . '.php');
    }

    protected function getArgumentValueAndValidation($argument_name_facade, $message): string
    {
        $name = $this->argument($argument_name_facade);
        
        while (empty($name)) {
            $name = $this->ask($message);
            
            if (empty($name)) {
                $this->error("This is required Field!");
            }
        }
        
        return $name;
    }

    protected function generateFacadeClass($facadepath, $facadeServicePath, $facadeName, $facadeServiceClass)
    {
        $stubsVariable = [
            'NAMESPACE' => 'App\\Facades',
            'CLASS_NAME' => ucwords(Pluralizer::singular($facadeName)),
            'SERVICE_CLASS_NAME' => Str::lower($facadeServiceClass)
        ];

        

        $facadeStubsPath = __DIR__.'/../../stubs/facades.stub';

        $contents = $this->getStubContents($facadeStubsPath, $stubsVariable);

        $this->files->put($facadepath, $contents);
    }

    protected function generateFacadeServiceClass($facadepath, $facadeServicePath, $facadeName, $facadeServiceClass)
    {
        $stubsVariable = [
            'NAMESPACE' => 'App\\Facades\\Services',
            'CLASS_NAME' => ucwords(Pluralizer::singular($facadeServiceClass)),
        ];

        $facadeStubsPath = __DIR__.'/../../stubs/facades-services.stub';

        $contents = $this->getStubContents($facadeStubsPath, $stubsVariable);

        $this->files->put($facadeServicePath, $contents);
    }


    public function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }

        return $contents;
    }

    protected function makeDirectory($path): string
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }
}
