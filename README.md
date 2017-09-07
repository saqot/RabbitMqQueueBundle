Saq RabbitMqQueueBundle
=================
[![Build Status](https://travis-ci.org/saqot/RabbitMqQueueBundle.svg?branch=master)](http://travis-ci.org/saqot/RabbitMqQueueBundle)
[![Latest Stable Version](https://poser.pugx.org/saq/rabbitmq-queue-bundle/v/stable)](https://packagist.org/packages/saq/rabbitmq-queue-bundle)
[![Total Downloads](https://poser.pugx.org/saq/rabbitmq-queue-bundle/downloads)](https://packagist.org/packages/saq/rabbitmq-queue-bundle)

Бундл управления очередями c помощью RabbitMq для Symfony >3.2

Установка
------------

### Composer
```bash
$ php composer require saq/rabbitmq-queue-bundle
```
или можно добавить в конфиг composer.json строку вида
```json
{
    "require" : {
        "saq/rabbitmq-queue-bundle": "~1.0"
    }
}
```
Добаляем в файл app/AppKernel.php строку вида

```php
// app/AppKernel.php

public function registerBundles()
{
    // ...
    $bundles[] = new Saq\RabbitMqQueueBundle\RabbitMqQueueBundle();
    // ...
}
```

### RabbitMq
* RU
	- Установка [RabbitMq на windows](doc/setup/rabbitmq-windows-ru.md)
	- Установка [RabbitMq на centos 7](doc/setup/rabbitmq-centos7-ru.md) 

