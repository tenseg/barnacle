# Barnacle

Barnacle is a Statamic addon that creates and extensible toolbar on the front end of your site.

## TO DO

[X] Move collection generation into the 'new' component
[X] Permissions and Preferences
[X] Move main Barnacle to a template
    [X] Perhaps move components into partials?
[X] Make configuration and templates publishable for customization
[ ] Harden components against missing variables
[ ] Add the MIT License
[ ] Instructions for installing from Git

## Features

- Create new components with regular templates
- Customize which components are exposed to user roles
- Allow users to customize which components they see

## How to Install

You can install this addon via Composer:

``` bash
composer require tenseg/barnacle
```

Publish Barnacle's configuration if you want to make changes:

``` bash
php artisan vendor:publish --tag=barnacle-config
```

Publish Barnacle's templates if you want to customize the components:

``` bash
php artisan vendor:publish --tag=barnacle-templates
```

## How to Use

Publish the configuration and templates. Play on!

## Credits

This addon was inspired by both the [Statamic Toolbar](https://statamic.com/addons/heidkaemper/toolbar) addon and the [Admin Bar](https://statamic.com/addons/el-schneider/admin-bar) addon. Both of those are wonderful and worthy of you consideration before you decide to adopt Barnacle.

The icons are from [Iconoir at Iconify](https://icon-sets.iconify.design/iconoir/) by Luca Burgio and made available with an MIT license.
