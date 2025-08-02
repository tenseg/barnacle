# Barnacle

Barnacle is a Statamic addon that creates and extensible toolbar on the front end of your site. Barnacle builds a toolbar using regular Statamic templates/views, which is super flexible. Barnacle is primarily intended as a developer tool, though it can be helpful for site editors and authors as well.

## Features

- Create new components with regular templates
- Customize which components are exposed to users by role
- Allow users to customize which components they see

## Installation

Barnacle can be installed via Composer:

```sh
composer require tenseg/barnacle
```

Then add the `{{ barnacle }}` tag to your Antlers layout just before you closing `</body>` tag.

Barnacle can direct you to the source code for entries and templates if you add an entry to your `.env` file letting it know what file scheme to use to open files from your web browser. For example, add the following to `.env` to facilitate editing source files with VS Code on macOS:

```sh
BARNACLE_FILE_SCHEME=vscode://file
```

## Use

Barnacle attaches a toolbar menu to the upper right corner of your website. You will only see this menu if one of the following is true:

1. Your site is in debug mode (you have set `APP_DEBUG=true` in your `.env` file, for example);
2. You are logged in to your site;
3. You have logged in to your site in the past week on the same browser.

That third option is facilitated by a cookie Barnacle leaves in your browser when you log in. This option can be disabled in role permissions or by setting the `BARNACLE_COOKIE=` to nothing in your `.env` file. If you want to customize the name of the cookie Barnacle uses for this purpose, just put a value into the `.env` file such as `BARNACLE_COOKIE=mysillycookiename`. 

You can also choose to have Barnacle always appear by setting `BARNACLE_ALWAYS=true` in your `.env` file.

Each user can set their own preferences in the Statamic control panel to hide Barnacle components they don't wish to see. Users can also click on the "pin" icon to hide Barnacle when it is distracting. Hovering over the space where Barnacle usually appears will make it visible even when hidden.

## Customization

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

You can also publish Barnacle's configuration if you wish:

```sh
php artisan vendor:publish --tag=barnacle-config
```
Changes made to the `config/barnacle.php` file will be propagated by git to other instances of the site, while those in the `.env` file typically only apply to that single instance.

## Credits

This addon was inspired by both the [Statamic Toolbar](https://statamic.com/addons/heidkaemper/toolbar) addon and the [Admin Bar](https://statamic.com/addons/el-schneider/admin-bar) addon. Both of those are wonderful and worthy of you consideration before you decide to adopt Barnacle. They each can do things that Barnacle does not do.

The icons are from [Iconoir at Iconify](https://icon-sets.iconify.design/iconoir/) by Luca Burgio and made available with an MIT license.

Barnacle is made available under an open source [MIT License](LICENSE.txt) by [Tenseg LLC](https://www.tenseg.net).
