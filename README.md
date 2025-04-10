# Barnacle

Barnacle is a Statamic addon that creates and extensible toolbar on the front end of your site.

## Features

- Create new components with regular templates
- Customize which components are exposed to users by role
- Allow users to customize which components they see

## How to Install

For now, Barnacle is hosted in a private Github repository without a listing in [packagist.org](packagist.org). Please contact us at [dev@tenseg.net](mailto:dev@tenseg.net?subject=Barnacle%20repo%20request) for access. Once you have access to the repository, you can add the following to your `composer.json` file to let composer know about it.

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://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@github.com/tenseg/barnacle.git"
        }
    ]
```
Note that you will probably have to weave these entries into `composer.json` around other existing entries, so modify as required. Also, this URL uses a read-only token that will expire in April 2026.

After that is in place, you can install Barnacle via Composer as usual:

```sh
composer require tenseg/barnacle
```

## How to Use

Barnacle attaches a toolbar menu to the upper right corner of your website. You will only see this menu if:

1. Your site is in debug mode (you have set `APP_DEBUG=true` in your `.env` file, for example);
2. You are logged in to your site;
3. You have logged in to your site in the past week on the same browser.

That third option is facilitated by a cookie Barnacle leaves in your browser when you log in. This option can be disabled in role permissions or by setting the `BARNACLE_COOKIE=` to nothing in your `.env` file. You can also choose to have Barnacle always appear by setting `BARNACLE_ALWAYS=true` in your `.env` file.

Each user can set their own preferences to hide Barnacle components they don't wish to see. Users can also click on the "pin" icon to hide Barnacle when it is distracting. Hovering over the space where Barnacle usually appears will make it visible even when hidden.

## Customizing Barnacle

If you wish to create your own custom components, then first publish Barnacle's templates:

```sh
php artisan vendor:publish --tag=barnacle-templates
```

This will put the default templates into the `resources/views/vendor/barnacle` directory of your Statamic project.

Custom components are simply template files, like other Statamic views. Given the power of Antlers, which can even include PHP code, the potential for custom components is really endless. See the [example.antlers.html](resources/views/components/example.antlers.html) component for an extremly minimal example, and the [new.antlers.html](resources/views/components/new.antlers.html) component for a much more involved example.

Once you have defined a new component, you can tell Barnacle to include it by adding a JSON array of custom components to your `.env` file. For example, you would include a component in the template `example.antlers.html` with the following in `.env`:

```sh
BARNACLE_COMPONENTS='{"example":"Example Component"}'
```

The key matches the template, and the value will be used to describe the component in Statamic permissions and preferences.

You can also publish Barnacle's configuration if you wish, but this should probably not be necessary:

```sh
php artisan vendor:publish --tag=barnacle-config
```

## Credits

This addon was inspired by both the [Statamic Toolbar](https://statamic.com/addons/heidkaemper/toolbar) addon and the [Admin Bar](https://statamic.com/addons/el-schneider/admin-bar) addon. Both of those are wonderful and worthy of you consideration before you decide to adopt Barnacle.

The icons are from [Iconoir at Iconify](https://icon-sets.iconify.design/iconoir/) by Luca Burgio and made available with an MIT license.

Barnacle is made available under an open source [MIT License](LICENSE.txt) by [Tenseg LLC](https://www.tenseg.net).
