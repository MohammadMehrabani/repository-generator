<?php

namespace MohammadMehrabani\RepositoryGenerator;

use Illuminate\Support\ServiceProvider;

class RepositoryGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/repository-generator.php', 'repository-generator');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/repository-generator.php' => config_path('repository-generator.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\Generate::class,
            ]);
        }
    }
}
