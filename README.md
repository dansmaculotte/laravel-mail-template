# A laravel mail template driver to send emails with

[![Latest Version](https://img.shields.io/packagist/v/DansMaCulotte/laravel-mail-template.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/laravel-mail-template)
[![Total Downloads](https://img.shields.io/packagist/dt/DansMaCulotte/laravel-mail-template.svg?style=flat-square)](https://packagist.org/packages/dansmaculotte/laravel-mail-template)
[![Build Status](https://img.shields.io/travis/DansMaCulotte/laravel-mail-template/master.svg?style=flat-square)](https://travis-ci.org/dansmaculotte/laravel-mail-template)
[![Quality Score](https://img.shields.io/scrutinizer/g/DansMaCulotte/laravel-mail-template.svg?style=flat-square)](https://scrutinizer-ci.com/g/dansmaculotte/laravel-mail-template)
[![Code Coverage](https://img.shields.io/coveralls/github/DansMaCulotte/laravel-mail-template.svg?style=flat-square)](https://coveralls.io/github/dansmaculotte/laravel-mail-template)

> This package allows you to send emails via mail service providers template's engine.

There are 5 drivers available:

  - [Mandrill](https://mandrillapp.com/api/docs/)
  - [Mailjet](https://dev.mailjet.com/guides/#about-the-mailjet-api)
  - [Sendgrid](https://sendgrid.com/docs/api-reference/)
  - [Mailgun](https://documentation.mailgun.com/en/latest/api_reference.html) 
  - [SendinBlue](https://developers.sendinblue.com/docs)
  
There is also and `log` and `null` driver for testing and debug purpose.

## Installation

### Requirements

- PHP 7.2

You can install the package via composer:

```bash
composer require dansmaculotte/laravel-mail-template
```

The package will automatically register itself.

To publish the config file to config/mail-template.php run:

```php
php artisan vendor:publish --provider="DansMaCulotte\MailTemplate\MailTemplateServiceProvider"
```

Finally, install the email service package needed:

- Mailjet

```bash
composer require mailjet/mailjet-apiv3-php
```

- Mandrill

```bash
composer require mandrill/mandrill
```

- SendGrid

```bash
composer require sendgrid/sendgrid
```

- Mailgun

```bash
composer require mailgun/mailgun-php
```

- SendinBlue

```bash
composer require sendinblue/api-v3-sdk
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
$mailTemplate = MailTemplate::make()
    ->setSubject('Welcome aboard')
    ->setFrom(config('mail.name'), config('mail.email'))
    ->setRecipient('Recipient Name', 'recipient@email.com')
    ->setLanguage('en')
    ->setTemplate('welcome-aboard')
    ->setVariables([
        'first_name' => 'Recipient',
    ]);
    
$response = $mailTemplate->send();
```

If an error occurs in the send method it will throw a `SendError::responseError` exception.

### Via Notification

Create a new notification via php artisan:

```bash
php artisan make:notification WelcomeNotification
```

Set `via` to `MailTemplateChannel`:

```php
/**
 * Get the notification's delivery channels.
 *
 * @param  mixed  $notifiable
 * @return array
 */
public function via($notifiable)
{
    return [MailTemplateChannel::class];
}
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

### Mailjet Specifics

Mailjet API allows to set an email to debug templates. When a template error is
encountered on email sending, Mailjet sends an error report to this mailbox. To
do so, set the email in `config/mail-template.php`, in key
`mailjet.debug_email`.

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
