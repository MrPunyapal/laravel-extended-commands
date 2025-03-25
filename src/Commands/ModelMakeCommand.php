<?php

namespace MrPunyapal\LaravelExtendedCommands\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand as BaseCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('builder')) {
            $this->createBuilder();
        }
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
     * @inheritDoc
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['builder', 'b', InputOption::VALUE_NONE, 'Create a new builder for the model'],
        ]);
    }
}
