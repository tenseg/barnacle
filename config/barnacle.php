<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Barnacle Enabled
    |--------------------------------------------------------------------------
    |
    | Barnacle is enabled by default when app.debug is set to true or users
    | are logged in. You can force Barnacle to always appear by setting
    | this option to true.
    |
    */

    'always' => env('BARNACLE_ALWAYS', null),

    /*
    |--------------------------------------------------------------------------
    | Barnacle Cookie
    |--------------------------------------------------------------------------
    |
    | Provide an alternate cookie name.
    |
    | By default, a cookie will be created each time
    | a user logs in, Barnacle will leave a cookie in
    | the browser, making it possilbe to see Barnacle
    | even when nobody is logged in on that browser.
    |
    */

    'cookie' => env('BARNACLE_COOKIE', 'barnacle_cookie'),

    /*
    |--------------------------------------------------------------------------
    | Barnacle Components
    |--------------------------------------------------------------------------
    |
    | Note that by default, components you define in the .env file using JSON
    | encoding will be added before the final 'prefs' component. So you don't
    | have to edit this configuration at all. For example:
    |
    | BARNACLE_COMPONENTS='{"example":"Example Component"}'
    |
    | However, you can also add or remove components here too.
    |
    | Create a template with the same name as the key to display the component.
    |
    */

    'components' => array_merge(
        [
            'edit' => 'Edit entry in control panel',
            'md' => 'Open entry source',
            'blueprint' => 'Edit blueprint',
            'template' => 'Open template source',
            'collection' => 'Go to collection',
            'new' => 'Create new entry',
            'git' => 'Git information',
        ],
        json_decode(env('BARNACLE_COMPONENTS', null), true) ?: [],
        [
            'prefs' => 'Edit user preferences',
        ]
    ),

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
        | Leaving this blank will tell Barnacle to not display source links.
        |
        | For example, put this in your .env file:
        |
        | BARNACLE_FILE_SCHEME=vscode://file
        |
        */

        'file_scheme' => env('BARNACLE_FILE_SCHEME', ''),
    ],

];
