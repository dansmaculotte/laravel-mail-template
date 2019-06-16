# A mail template driver to send emails with

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dansmaculotte/laravel-mail-template.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/laravel-mail-template)
[![Build Status](https://img.shields.io/travis/dansmaculotte/laravel-mail-template/master.svg?style=flat-square)](https://travis-ci.org/dansmaculotte/laravel-mail-template)
[![Quality Score](https://img.shields.io/scrutinizer/g/dansmaculotte/laravel-mail-template.svg?style=flat-square)](https://scrutinizer-ci.com/g/dansmaculotte/laravel-mail-template)
[![Total Downloads](https://img.shields.io/packagist/dt/dansmaculotte/laravel-mail-template.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/laravel-mail-template)

This package allows you to send emails via mail service providers template's engine.

## Installation

You can install the package via composer:

```bash
composer require dansmaculotte/laravel-mail-template
```

The package will automatically register itself.

To publish the config file to config/mail-template.php run:

```php
php artisan vendor:publish --provider="DansMaCulotte\MailTemplate\MailTemplateServiceProvider"
```

## Usage

Configure your mail template driver and credentials in `config/mail-template.php`.

### Basic

After you've installed the package and filled in the values in the config-file working with this package will be a breeze.
All the following examples use the facade. Don't forget to import it at the top of your file.

```php
use MailTemplate;
```

```php
$mailTemplate = MailTemplate::setSubject('Welcome aboard')
    ->setFrom(config('mail.name'), config('mail.email'))
    ->setRecipient('Recipient Name', 'recipient@email.com')
    ->setLanguage('en')
    ->setTemplate('welcome-aboard')
    ->setVariables([
        'first_name' => 'Recipient',
    ]);
    
$response = $mailTemplate->send();
```

### Via Notification

Create a new notification via php artisan:

```bash
php artisan make:notification WelcomeNotification
```

Change `extends` to `MailTemplateNotificaiton`:

```php
use DansMaCulotte\MailTemplate\MailTemplateNotification;

class WelcomeNotification extends MailTemplateNotification
```

Implement `toMailTemplate` method and prepare your template:

```php
public function toMailTemplate($notifiable)
{
    return MailTemplate::prepare(
        'Welcome aboard',
        [
            'name' => config('mail.from.name'),
            'email' => config('mail.from.email'),
        ],
        [
            'name' => $notifiable->full_name,
            'email' => $notifiable->email,
        ],
        $notifiable->preferredLocale(),
        'welcome-aboard',
        [
            'first_name' => $notifiable->first_name
        ]
    );
}
```

And that's it.
When `MailTemplateChannel` will receive the notification it will automatically call `send` method from `MailTemplate` facade.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
