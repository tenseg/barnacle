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
                    'display' => __('Allow Barnacle to show a login link'),
                    'instructions' => __('This will allow Barnacle save a cookie in your browser so that on future visits it will show a login link when you are not already logged in. You will still need to know your email adress and password to login.'),
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
