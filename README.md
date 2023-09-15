<h1 align="center">lara-forms-builder package</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wisam-alhennawi/lara-forms-builder.svg?style=flat-square)](https://packagist.org/packages/wisam-alhennawi/lara-forms-builder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/wisam-alhennawi/lara-forms-builder/run-tests?label=tests)](https://github.com/wisam-alhennawi/lara-forms-builder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/wisam-alhennawi/lara-forms-builder/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/wisam-alhennawi/lara-forms-builder/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wisam-alhennawi/lara-forms-builder.svg?style=flat-square)](https://packagist.org/packages/wisam-alhennawi/lara-forms-builder)

## About
The main functionality of this package is:
- Generate Livewire forms (Show, Create, Update) by using one command and one Livewire component.

## Requirements
The following points are required to install this package:
- PHP 8.0+
- Laravel [10.x, 9.x]
- Laravel Livewire 2.x
- Alpine.js 3.x
- Tailwindcss 3.x
> Note that the following npm packages is required only if you have a date field within your form.
- pikaday: 1.x
- moment: 2.x
## Installation

For now while the package is in development phase and not released yet, in order to use it you must add these line to your `composer.json` file:

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/wisam-alhennawi/lara-forms-builder.git"
        }
    ]
```
> Note that each one of the following commands has two option for execution first with laravel sail & second with default laravel.

After that run:

```bash
sail composer require wisam-alhennawi/lara-forms-builder
composer require wisam-alhennawi/lara-forms-builder
```

## Configuration

### Publishing Assets

1) #### Config **(Mandatory)**
    You Must publish the config file and add it to the `tailwind.config.js` in order to apply the styles:
    ```bash
    sail artisan vendor:publish --tag="lara-forms-builder-config"
    php artisan vendor:publish --tag="lara-forms-builder-config"
    ```
    This is the contents of the published config file:
    ```php
    return [
        'default_group_wrapper_class' => 'grid grid-cols-2 gap-6',
        'default_field_wrapper_class' => 'col-span-1 sm:col-span-1',
        'card_field_error_wrapper_classes' => 'shadow mb-4 overflow-hidden rounded-md col-span-2 sm:col-span-2',
        'primary_button_classes' => 'btn btn-primary disabled:bg-c_gray-300',
        'secondary_button_classes' => 'btn btn-secondary',
    ];
    ```

   Update `tailwind.config.js`:
   ```js
   export default {
        content: [
        './config/lara-forms-builder.php',
       ],
        theme: {
            extend: {
                colors: {
                    'primary': '', // #7c8e63
                    'secondary': '', // #aebf85
                    'danger': '' // #DC3545
                },
            },
        },
   };
   ```

2) #### CSS **(Mandatory)**
   Publishing css file is **Mandatory** to apply styles.

    ```bash
    sail artisan vendor:publish --tag="lara-forms-builder-assets"
    php artisan vendor:publish --tag="lara-forms-builder-assets"
    ```
   That will make a new css file `lara-forms-builder.css` in the `public/vendor/lara-forms-builder/css/` directory.
   After that you must import this file with your `resources/css/app.css` by adding:
    ```bash
    @import "../../public/vendor/lara-forms-builder/css/lara-forms-builder.css";
    ```
   And feel free to edit the default style in `lara-forms-builder.css` to fulfil your form requirements style.

3) #### Translation (optional)
    ```bash
    sail artisan vendor:publish --tag="lara-forms-builder-translations"
    php artisan vendor:publish --tag="lara-forms-builder-translations"
    ```

4) #### Views (optional)
    ```bash
    sail artisan vendor:publish --tag="lara-forms-builder-views"
    php artisan vendor:publish --tag="lara-forms-builder-views"
    ```

### Alpine.js Cloak **(Mandatory)**
You must also make sure you have this Alpine style available globally:
```
<style>
    [x-cloak] { display: none !important; }
</style>
```

### Using date in form (optional)
Like it mention in the Requirements section if your form has a date field you must install required dependencies by following these steps:
- Installing pikaday:
    ```bash
    sail npm install pikaday
    npm install pikaday
    ```
  add these to your `resources/js/app.js`
    ```js
    import Pikaday from 'pikaday';
    window.Pikaday = Pikaday;
    ```
  add these to your `resources/css/app.css`
    ```css
    @import 'pikaday/css/pikaday.css';
    ```

- Installing moment:
    ```bash
    sail npm install moment
    npm install moment
    ```

## Usage

### Create new Livewire from

By using this command you can create a new Livewire form depending on a model,
additionally you can add [--langModelFileName=] tag to specify a lang file for model fields labels:
```bash
sail artisan make:lara-forms-builder [Component Name] [Model] [--langModelFileName=]
php artisan make:lara-forms-builder [Component Name] [Model] [--langModelFileName=]
```

Examples:
```bash
sail artisan make:lara-forms-builder UserForm User --langModelFileName=users
sail artisan make:lara-forms-builder Users.UserForm User --langModelFileName=users       //this will make a UserForm component inside Users directory.
```

### Use confirmation modal

In order to use the confirmation modal within your project you must include it globally in the default layout of your blade view where you want to use it.
So you can add `@livewire('modals.confirmation')` to your `views/layouts/app.blade.php` inside the html `<body>` tag.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Wisam Alhennawi](https://github.com/wisam-alhennawi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
