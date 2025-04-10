<?php

namespace Tenseg\Barnacle;

use Statamic\Providers\AddonServiceProvider;
use Tenseg\Barnacle\Middleware\BarnacleInject;
use Tenseg\Barnacle\Middleware\BarnaclePermissions;

class ServiceProvider extends AddonServiceProvider
{
    protected $middlewareGroups = [
        'statamic.web' => [BarnaclePermissions::class, BarnacleInject::class],
        'statamic.cp' => [BarnaclePermissions::class],
    ];

    public function bootAddon()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/barnacle.php', 'barnacle');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/barnacle.php' => config_path('barnacle.php'),
            ], 'barnacle-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/barnacle'),
            ], 'barnacle-templates');

        }
    }
}
