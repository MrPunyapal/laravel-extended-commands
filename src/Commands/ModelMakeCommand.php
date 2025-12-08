<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand as BaseCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('builder')) {
            $this->createBuilder();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/../../stubs/model.stub');
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildClass($name)
    {
        $replace = $this->buildBuilderReplacements();

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Create a builder file for the model.
     *
     * @return void
     */
    protected function createBuilder()
    {
        $builder = Str::studly($this->argument('name'));

        $this->call('make:builder', [
            'name' => "{$builder}Builder",
            '--model' => '\\'.$this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Build the replacements for a builder.
     *
     * @return array<string, string>
     */
    protected function buildBuilderReplacements()
    {
        $replacements = [];

        if ($this->option('builder') || $this->option('all')) {
            $modelPath = Str::of($this->argument('name'))->studly()->replace('/', '\\')->toString();

            $builderNamespace = 'App\\Models\\Builders\\'.$modelPath.'Builder';
            $builderClass = Str::of($builderNamespace)->afterLast('\\')->toString();

            $builderCode = <<<EOT
            /**
                 * Create a new Eloquent query builder for the model.
                 *
                 * @param  \Illuminate\Database\Query\Builder \$query
                 * @return $builderClass<$modelPath>
                 */
                public function newEloquentBuilder(\$query): $builderClass
                {
                    return new $builderClass(\$query);
                }
            EOT;

            $replacements['{{ newBuilderFunction }}'] = $builderCode;
            $replacements['{{ builderImport }}'] = "use $builderNamespace;";

            if (! $this->option('factory')) {
                $replacements["//\n"] = '';
                $replacements["//\r\n"] = '';
            }
        } else {
            $replacements["{{ newBuilderFunction }}\n"] = '';
            $replacements["{{ newBuilderFunction }}\r\n"] = '';
            $replacements["{{ builderImport }}\n"] = '';
            $replacements["{{ builderImport }}\r\n"] = '';
        }

        return $replacements;
    }

    /**
     * {@inheritDoc}
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['builder', 'b', InputOption::VALUE_NONE, 'Create a new builder for the model'],
        ]);
    }
}
