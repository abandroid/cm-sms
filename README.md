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

$options = [
    'sender' => 'Endroid',
    'unicode' => 'auto',
    'minimumNumberOfMessageParts' => 1,
    'maximumNumberOfMessageParts' => 3,
];

$message = new Message();
$message->addTo('0600000000');
$message->setBody('SMS Messaging is the future!');

// Send single message (to one or more recipients)
$client->sendMessage($message, $options);

// Or bulk send multiple messages (to one or more recipients)
$client->sendMessages([$message, ...], $options);
```

## Options

The following sending options are available.

* sender: default sender
* unicode: unicode handling (auto, force or never)
* minimumNumberOfMessageParts: min when splitting up long messages
* maximumNumberOfMessageParts: max when splitting up long messages

## Symfony integration

Register the Symfony bundle in the kernel.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Endroid\CmSms\Bundle\EndroidCmSmsBundle(),
    ];
}
```

The default parameters can be overridden via the configuration.

```yaml
endroid_cm_sms:
    product_token: '00000000-0000-0000-0000-000000000000'
    defaults:
        sender: 'Endroid'
        unicode: 'auto'
        minimumNumberOfMessageParts: 1
        maximumNumberOfMessageParts: 3
```

Now you can retrieve the client as follows.

```php
$client = $this->get('endroid.cm_sms.client');
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
