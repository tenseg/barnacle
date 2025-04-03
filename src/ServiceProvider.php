<?php

namespace Tenseg\Barnacle;

use Statamic\Facades\Permission;
use Statamic\Facades\Preference;
use Statamic\Providers\AddonServiceProvider;
use Tenseg\Barnacle\Middleware\InjectBarnacle;

class ServiceProvider extends AddonServiceProvider
{
    protected $middlewareGroups = [
        'statamic.web' => [InjectBarnacle::class],
    ];

    public function bootAddon()
    {

        Permission::extend(function () {
            Permission::group('barnacle', 'Barnacle', function () {
                Permission::register('use barnacle cookie')
                    ->label(__('Allows Barnacle to have a cookie'));
            });
        });

        Preference::extend(fn ($preference) => [
            'general' => [
                'display' => __('Barnacle'),
                'fields' => [
                    'barnacle' => [
                        'type' => 'section',
                        'display' => __('Barnacle'),
                    ],
                    'barnacle_disabled' => [
                        'type' => 'toggle',
                        'display' => __('Save a Cookie to enable Barnacle'),
                        'width' => '100',
                        'default' => true,
                    ],
                ],
            ],
        ]);
    }
}
