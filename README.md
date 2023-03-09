# This is my package lara-forms-builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wisam-alhennawi/lara-forms-builder.svg?style=flat-square)](https://packagist.org/packages/wisam-alhennawi/lara-forms-builder)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/wisam-alhennawi/lara-forms-builder/run-tests?label=tests)](https://github.com/wisam-alhennawi/lara-forms-builder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/wisam-alhennawi/lara-forms-builder/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/wisam-alhennawi/lara-forms-builder/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wisam-alhennawi/lara-forms-builder.svg?style=flat-square)](https://packagist.org/packages/wisam-alhennawi/lara-forms-builder)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

## Installation

You can install the package via composer:

```bash
composer require wisam-alhennawi/lara-forms-builder
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="lara-forms-builder-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="lara-forms-builder-views"
```

## Usage

```php
$laraFormsBuilder = new WisamAlhennawi\LaraFormsBuilder();
echo $laraFormsBuilder->echoPhrase('Hello, WisamAlhennawi!');
```

## Testing

```bash
composer test
```

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
