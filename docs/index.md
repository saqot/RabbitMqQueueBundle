## Инструкции.
* Установка
	* Установка бандла через [Composer](setup/composer.md)
	* Установка RabbitMq
		* [на windows](setup/rabbitmq-windows.md)
		* [на centos 7](setup/rabbitmq-centos7.md) 

* Настройка
	* [app/config/config.yml](config/index.md)

* Проверка
	- Для проверки того, что все установлено и настроено верно, необходимо выполнить в консоли комманду
		```bash
		php bin/console mq.jobs:check
		```

* Примеры работы
	* [app/config/config.yml](example/index.md)