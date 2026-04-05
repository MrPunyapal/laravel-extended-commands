<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Console\GeneratorCommand;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:view')]
class ViewMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $name = 'make:view';

    /**
     * The console command description.
     */
    protected $description = 'Create a new blade view file';

    /**
     * The type of class being generated.
     */
    protected $type = 'View';

    /**
     * Execute the console command.
     * If not in simple mode, attempt to create the layout file if it does not exist.
     */
    #[Override]
    public function handle(): ?bool
    {
        // Automatically create the layout blade file if needed
        if (! $this->option('simple')) {
            $this->createLayoutIfNeeded();
        }

        // Call the parent handler to proceed with view file generation
        return parent::handle();
    }

    /**
     * Creates the layout blade file if it does not already exist.
     * Supports specifying nested layouts (e.g., admin/main).
     */
    protected function createLayoutIfNeeded()
    {
        // Use specified layout or default to 'app'
        $layoutName = $this->option('layout') ?? 'app';
        // Support dot or slash notation for subfolders
        $layoutRelativePath = str_replace(['.', '\\'], '/', $layoutName);
        $layoutPath = resource_path('views/layouts/'.$layoutRelativePath.'.blade.php');

        if (! $this->files->exists($layoutPath)) {
            // Minimal HTML structure with @yield('content')
            $content = <<<'BLADE'
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Laravel View</title>
                </head>
                <body>
                    @yield('content')
                </body>
                </html>
                BLADE;

            // Create parent directories for the layout if they do not exist
            if (! $this->files->exists(dirname($layoutPath))) {
                $this->files->makeDirectory(dirname($layoutPath), 0755, true);
            }

            // Write the base content to the layout file
            $this->files->put($layoutPath, $content);
        }
    }

    /**
     * Return the path to the stub file depending on options.
     */
    protected function getStub()
    {
        if ($this->option('simple')) {
            return $this->resolveStubPath('/../../stubs/view.simple.stub');
        }

        return $this->resolveStubPath('/../../stubs/view.stub');
    }

    /**
     * Resolve the absolute path to the stub file.
     */
    protected function resolveStubPath(string $stub): string
    {
        return __DIR__.$stub;
    }

    /**
     * Define the console command options.
     *
     * @return array<int, array<int, mixed>|InputOption>
     */
    #[Override]
    protected function getOptions(): array
    {
        return [
            ['layout', '', InputOption::VALUE_REQUIRED, 'Specify the layout to use.'],
            ['simple', '', InputOption::VALUE_NONE, 'Generate a simple view without layout.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite the view if it already exists.'],
        ];
    }

    /**
     * Get the target path for the generated view file.
     * Ensures the directory exists before creating the file.
     */
    #[Override]
    protected function getPath($name)
    {
        // Support dot syntax for nested folders
        $name = str_replace('.', '/', $name);

        $path = resource_path('views/'.$name.'.blade.php');

        // Create the directory for the view if it does not exist
        if (! $this->files->exists(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }

        return $path;
    }

    /**
     * Replace variables (such as layout) in the stub content.
     */
    #[Override]
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        // Insert the layout name where needed
        $layout = $this->option('layout') ?? 'app';

        return str_replace('{{ layout }}', $layout, $stub);
    }
}
