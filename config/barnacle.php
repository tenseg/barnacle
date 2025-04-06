<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Barnacle Enabled
    |--------------------------------------------------------------------------
    |
    | Barnacle is enabled by default, when app.debug is set to true.
    | You can override this by setting true or false instead of null.
    |
    */

    'enabled' => env('BARNACLE_ENABLED', null),

    /*
    |--------------------------------------------------------------------------
    | Barnacle Cookie
    |--------------------------------------------------------------------------
    |
    | If you provide a cookie name like "barnacle_cookie", then each time
    | a user logs in, Barnacle will leave a cookie, making it possilbe
    | to see Barnacle even when it is disabled and app.debug is set to false.
    |
    */

    'cookie' => env('BARNACLE_COOKIE', null),

    /*
    |--------------------------------------------------------------------------
    | Barnacle Components
    |--------------------------------------------------------------------------
    |
    | You can add your own components here, or remove the ones you don't want.
    | Create a template with the same name as the key to display the component.
    |
    */

    'components' => [
        'edit' => 'Edit entry in control panel',
        'md' => 'Open entry source',
        'blueprint' => 'Edit blueprint',
        'template' => 'Open template source',
        'collection' => 'Go to collection',
        'new' => 'Create new entry',
        'git' => 'Git information',
        'prefs' => 'Edit user preferences',
    ],

    /*
    |--------------------------------------------------------------------------
    | Barnacle Options
    |--------------------------------------------------------------------------
    |
    | These options will be passed along to all components.
    |
    */

    'options' => [

        /*
        |--------------------------------------------------------------------------
        | File Scheme Option
        |--------------------------------------------------------------------------
        |
        | This file sheme will be used to build a links to source files.
        | Note, omit the final slash since the path will start with a slash.
        |
        */

        'file_scheme' => 'vscode://file',
    ],

];
