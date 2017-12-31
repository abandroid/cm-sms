Endroid CM SMS
==============

*By [endroid](https://endroid.nl/)*

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

### Symfony integration

You can easily integrate the library in Symfony with the [EndroidCmSmsBundle](https://github.com/endroid/EndroidCmSmsBundle).

## Usage

```php
use Endroid\CmSms\Client;
use Endroid\CmSms\Exception\RequestException;

$client = new Client();

$options = [
    'sender' => 'Endroid',
    'unicode' => 'auto',
    'minimum_number_of_message_parts' => 1,
    'maximum_number_of_message_parts' => 3,
];

$message = new Message();
$message->addTo('0600000000');
$message->setBody('SMS Messaging is the future!');

// Send single message (to one or more recipients)
try {
    $client->sendMessage($message, $options);
} catch (RequestException $exception) {
    // handle exception
}

// Or bulk send multiple messages (to one or more recipients)
try {
    $client->sendMessages([$message, ...], $options);
} catch (RequestException $exception) {
    // handle exception
}
```

## Options

The following sending options are available.

* sender: default sender
* unicode: unicode handling (auto, force or never)
* minimum_number_of_message_parts: min when splitting up long messages
* maximum_number_of_message_parts: max when splitting up long messages

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This source code is subject to the MIT license bundled in the file LICENSE.
