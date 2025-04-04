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

    public function bootAddon() {}
}
