# A laravel mail template driver to send emails with

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dansmaculotte/laravel-mail-template.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/laravel-mail-template)
[![Total Downloads](https://img.shields.io/packagist/dt/dansmaculotte/laravel-mail-template.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/laravel-mail-template)


This package allows you to send emails via mail service providers template's engine.

## Installation

You can install the package via composer:

```bash
composer require dansmaculotte/laravel-mail-template
```

## Usage

``` php
$mailTemplate = new DansMaCulotte\MailTemplate();
echo $mailTemplate->send('Hello, Spatie!');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
