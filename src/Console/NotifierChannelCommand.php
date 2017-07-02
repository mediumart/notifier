<?php

namespace Mediumart\Notifier\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class NotifierChannelCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'notifier:channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification channel class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Notification Channel';
        
    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        if ($this->option('aliases')) {
            return $this->replaceAliases($stub, $this->option('aliases'));
        }

        return str_replace('DummyHooks', '//', $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/channel.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Notifications\\Channels';
    }

    /**
     * Replace Hooks Aliases for the given stub.
     * 
     * @param  string $stub
     * @param  string $aliases
     * @return string
     */
    protected function replaceAliases($stub, $aliases)
    {
        $aliases = str_replace(',', "', '", str_replace(' ', '', $aliases));

        return str_replace('DummyHooks', 'return in_array($driver, [\''.$aliases.'\']);', $stub);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['aliases', 'a', InputOption::VALUE_OPTIONAL, 'Aliases/hooks names supported by the channel.'],
        ];
    }
}