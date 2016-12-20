CM SMS
======

*By [endroid](http://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/cm-sms.svg)](https://packagist.org/packages/endroid/cm-sms)
[![Build Status](http://img.shields.io/travis/endroid/CmSms.svg)](http://travis-ci.org/endroid/CmSms)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/cm-sms.svg)](https://packagist.org/packages/endroid/cm-sms)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/cm-sms.svg)](https://packagist.org/packages/endroid/cm-sms)
[![License](http://img.shields.io/packagist/l/endroid/cm-sms.svg)](https://packagist.org/packages/endroid/cm-sms)

This library enables sending SMS messages using the [CM Telecom SMS service](https://docs.cmtelecom.com/).

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require endroid/cm-sms
```

Then, require the vendor/autoload.php file to enable the autoloading mechanism
provided by Composer. Otherwise, your application won't be able to find the
classes of this library.

Of course you can also download the library and build your own autoloader.

## Usage

```php
use Endroid\CmSms\Client;

$client = new Client();

```

## Symfony

You can use [`EndroidCmSmsBundle`](https://github.com/endroid/EndroidCmSmsBundle) to integrate this service in your Symfony application.

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
