<?php

namespace Smartisan\Settings\Test;

use Smartisan\Settings\Repositories\DatabaseRepository;

class DatabaseRepositoryTest extends BaseRepositoryTest
{
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/migration'),
        ]);

        $this->settings = new DatabaseRepository($this->app['db'], 'settings');
    }

    protected function getPackageProviders($app)
    {
        return [
            'Orchestra\Database\ConsoleServiceProvider',
            'Smartisan\Settings\SettingsServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
