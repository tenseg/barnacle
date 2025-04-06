<?php

namespace Tenseg\Barnacle\Middleware;

use Closure;
use Statamic\Auth\User;
use Statamic\Facades\Permission;
use Statamic\Facades\Preference;

class BarnaclePermissions
{
    public function handle($request, Closure $next)
    {
        Permission::extend(function () {
            Permission::group('barnacle', 'Barnacle', function () {
                Permission::register('use barnacle cookie')
                    ->label(__('Can save Barnacle cookies'));
                foreach (config('barnacle.components') as $key => $label) {
                    Permission::register("use barnacle component $key")
                        ->label(__("Can use $label"));
                }
            });
        });

        $fields = [];
        $user = User::current();

        if ($user) {

            if ($user->can('use barnacle cookie')) {
                $fields['barnacle_disabled'] = [
                    'type' => 'toggle',
                    'display' => __('Save a Cookie to enable Barnacle'),
                    'width' => '50',
                    'default' => true,
                ];
            }
            $options = [];
            foreach (config('barnacle.components') as $key => $label) {
                if ($user->can("use barnacle component $key")) {
                    $options[$key] = "Hide $label";
                }
            }
            if (! empty($options)) {
                $fields['barnacle_hidden_components'] = [
                    'type' => 'checkboxes',
                    'display' => __('Hide Components'),
                    'options' => $options,
                    'width' => '50',
                ];
            }

            if (! empty($fields)) {

                $fields = array_merge(['barnacle' => [
                    'type' => 'section',
                    'display' => __('Barnacle'),
                ]], $fields);

                Preference::extend(fn ($preference) => [
                    'general' => [
                        'display' => __('Barnacle'),
                        'fields' => $fields,
                    ],
                ]);
            }
        }

        return $next($request);
    }
}
