<?php

namespace Smartisan\Settings;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Smartisan\Settings\Console\MakeTableCommand;
use Smartisan\Settings\Generators\BladeSettingsGenerator;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSettings();

        $this->registerCommands();
    }

    /**
     * Boot registered package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        
        $this->registerBladeDirectives();
    }

    /**
     * Register settings.
     *
     * @return void
     */
    protected function registerSettings()
    {
        $this->app->singleton('settings.manager', function ($app) {
            return new SettingsManager($app);
        });

        $this->app->singleton('settings', function ($app) {
            $repository = $this->app['settings.manager']->driver();

            if ($app['config']->get('settings.cache.enable')) {
                $repository->setCacher($app['cache']);
            }

            return $repository;
        });
    }

    /**
     * Register package console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands(MakeTableCommand::class);
    }

    /**
     * Register package configuration.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $path = realpath(__DIR__.'/../config/settings.php');

        $this->mergeConfigFrom($path, 'settings');

        $this->publishes([$path => config_path('settings.php')], 'config');
    }

    /**
     * Register package custom blade directives.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('settings', function () {
            return "<?php echo app('".BladeSettingsGenerator::class."')->generate(); ?>";
        });
    }
}
