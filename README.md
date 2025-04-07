# Barnacle

Barnacle is a Statamic addon that creates and extensible toolbar on the front end of your site.

## TO DO

- [X] Move collection generation into the 'new' component
- [X] Permissions and Preferences
- [X] Move main Barnacle to a template and components to partials
- [X] Make configuration and templates publishable for customization
- [ ] Harden components against missing variables
- [X] Add the MIT License
- [X] Instructions for installing from Git

## Features

- Create new components with regular templates
- Customize which components are exposed to user roles
- Allow users to customize which components they see

## How to Install

For now, Barnacle is hosted in a private Github repository without a listing in [packagist.org](packagist.org). Please contact us at [dev@tenseg.net](mailto:dev@tenseg.net?subject=Barnacle%20repo%20request) for access. Once you have access to the repository, you can add the following to your `composer.json` file to let composer know about it.

```json
    "require": {
        "tenseg/barnacle": "dev-main"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:tenseg/barnacle.git"
        }
    ]
```
Note that you will probably have to weave these entries into `composer.json` around other existing entries, so modify as required.

After that is in place, you can install Barnacle via Composer as usual:

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

Barnacle is made available under an open source [MIT License](LICENSE.txt) by [Tenseg LLC](https://www.tenseg.net).
