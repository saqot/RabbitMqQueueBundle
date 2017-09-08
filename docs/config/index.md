[RabbitMqQueueBundle](https://saqot.github.io/RabbitMqQueueBundle/) > Настройка

# Настройка бандла: RabbitMqQueueBundle

###  Настраиваем parameters.yml
* заполняем app/config/parameters.yml.dist
	```yaml
	parameters:
	# ...
		rabbit_host: 'localhost'
		rabbit_port: 5672
		rabbit_user: 'guest'
		rabbit_pwd:  'guest'
	# ...
	```

* Запускаем обновление через composer, для формирования `app/config/parameters.yml`
	```bash
	php composer update
	```
	
###  Настраиваем config.yml
* заполняем app/config/config.yml
	```yaml
	# ...
	queue_rabbit_mq:
		connection:
			host:     '%rabbit_host%'
			port:     '%rabbit_port%'
			user:     '%rabbit_user%'
			password: '%rabbit_pwd%'
	# ...
	```
* чистим кеш symfony
	```bash
	php bin/console cache:clear
	```
	
###  Примечания
* Не рекомендуется использовать юзера ***guest:guest*** на продакшене, создайте своего и его данные впишите в конфиг.
* host и port указывать не обязательно, если только у вас они отличаются от дефолтных
