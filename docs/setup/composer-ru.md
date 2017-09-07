[RabbitMqQueueBundle](https://saqot.github.io/RabbitMqQueueBundle/) >
[Установка](https://saqot.github.io/RabbitMqQueueBundle/setup/)
* ***Установка бандла RabbitMqQueueBundle*** <--
* Установка [RabbitMq на windows](https://saqot.github.io/RabbitMqQueueBundle/setup/rabbitmq-windows-ru.html)
* Установка [RabbitMq на centos 7](https://saqot.github.io/RabbitMqQueueBundle/setup/rabbitmq-centos7-ru.html) 



# Установка бандла RabbitMqQueueBundle

### Composer
Выполняем в консоли код
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
и затем выполнить код 
```bash
$ php composer update
```

### Регистрация бандла
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

