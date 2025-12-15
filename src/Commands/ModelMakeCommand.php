<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand as BaseCommand;
use Illuminate\Support\Str;
use Override;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function handle(): void
    {
        parent::handle();

        if ($this->option('builder')) {
            $this->createBuilder();
        }

        if ($this->option('collection')) {
            $this->createCollection();
        }
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    protected function getStub()
    {
        return $this->resolveStubPath('/../../stubs/model.stub');
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * {@inheritdoc}
     */
    #[Override]
    protected function buildClass($name)
    {
        $replace = array_merge(
            $this->buildBuilderReplacements(),
            $this->buildCollectionReplacements()
        );

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
     * Create a collection file for the model.
     *
     * @return void
     */
    protected function createCollection()
    {
        $collection = Str::studly($this->argument('name'));

        $this->call('make:collection', [
            'name' => "{$collection}Collection",
            '--model' => '\\'.$this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Build the replacements for a builder.
     *
     * @return array<string, string>
     */
    protected function buildBuilderReplacements(): array
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
     * Build the replacements for a collection.
     *
     * @return array<string, string>
     */
    protected function buildCollectionReplacements(): array
    {
        $replacements = [];

        if ($this->option('collection') || $this->option('all')) {
            $modelPath = Str::of($this->argument('name'))->studly()->replace('/', '\\')->toString();

            $collectionNamespace = 'App\\Models\\Collections\\'.$modelPath.'Collection';
            $collectionClass = Str::of($collectionNamespace)->afterLast('\\')->toString();

            $collectionCode = <<<EOT
            /**
                 * Create a new Eloquent collection for the model.
                 *
                 * @param  array \$models
                 * @return $collectionClass<$modelPath>
                 */
                public function newCollection(array \$models = []): $collectionClass
                {
                    return new $collectionClass(\$models);
                }
            EOT;

            $replacements['{{ newCollectionFunction }}'] = $collectionCode;
            $replacements['{{ collectionImport }}'] = "use $collectionNamespace;";

            if (! $this->option('factory')) {
                $replacements["//\n"] = '';
                $replacements["//\r\n"] = '';
            }
        } else {
            $replacements["{{ newCollectionFunction }}\n"] = '';
            $replacements["{{ newCollectionFunction }}\r\n"] = '';
            $replacements["{{ collectionImport }}\n"] = '';
            $replacements["{{ collectionImport }}\r\n"] = '';
        }

        return $replacements;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['builder', 'b', InputOption::VALUE_NONE, 'Create a new builder for the model'],
            ['collection', null, InputOption::VALUE_NONE, 'Create a new collection for the model'],
        ]);
    }
}
