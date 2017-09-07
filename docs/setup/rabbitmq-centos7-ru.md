[RabbitMqQueueBundle](https://saqot.github.io/RabbitMqQueueBundle/) >
[Установка](index-ru.md)

# Установка RabbitMq на centos 7.

Для начала стоит обновить систему, выполняем код
```sh
yum -y update
```
### Установить Erlang
Для установки Erlang используйте команды:
* Если не установлены репозитории epel или remi, то ставим их
	```sh
	wget https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
	sudo rpm -Uvh epel-release-latest-7*.rpm
    ```
    ```sh
	wget http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
	sudo rpm -Uvh remi-release-7*.rpm
	```
* устанавливаем сам Erlang
	```sh
	yum install -y erlang
	```
	
### Установить RabbitMQ
```sh
cd /tmp
```
* Скачиваем актуальный репозиторий, подсмотреть можно здесь <https://www.rabbitmq.com/install-rpm.html> 
	```sh
	wget http://www.rabbitmq.com/releases/rabbitmq-server/v3.6.11/rabbitmq-server-3.6.11-1.el7.noarch.rpm
	```
* получаем кей и устанавливаем сервер
	```sh
	rpm --import https://www.rabbitmq.com/rabbitmq-release-signing-key.asc
	yum -y install rabbitmq-server-3.6.11-1.el7.noarch.rpm
	```

* Чтобы получить доступ к консоли удаленного управления RabbitMQ, вам необходимо разрешить входящий TCP-трафик на портах 4369, 5671, 5672, 25672, 15672, 15675, 61613, 61614, 1883, 15674.

	* при наличии firewall
		```sh
		firewall-cmd --zone=public --permanent --add-port=4369/tcp --add-port=5671-5672/tcp --add-port=25672/tcp --add-port=15672-15675/tcp  --add-port=61613-61614/tcp --add-port=1883/tcp --add-port=15674/tcp
		firewall-cmd --reload
		```
	* при наличии iptables
		```sh
		iptables -I INPUT 1 -p tcp --dport 4369 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 5671 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 5672 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 15675 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 25672 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 15672 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 61613 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 61614 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 1883 -j ACCEPT
		iptables -I INPUT 1 -p tcp --dport 15674 -j ACCEPT
		service iptables save
		service iptables restart
		```
* меняем ограничение на количество открытых файлов. 
	* Открываем файл `/etc/systemd/system/rabbitmq-server.service.d/limits.conf` и выставлем содержимое, что бы получилось вот так
		```bash
		[Service]
		LimitNOFILE=infinity
		```
		Если указанного файла нет, то создаем его.
	* выполняем команду
		```sh
		systemctl --system daemon-reload
		```
* помечаем rabbitmq для автоматического запуска при перезагрузках
	```sh
	chkconfig rabbitmq-server on
	```
* запускаем сервер
	```sh
	service rabbitmq-server start
	```
На этом установка закончена	

### Управление
* Управление службой:
	```sh
	# Старт
	service rabbitmq-server start
	# Стоп:
	service rabbitmq-server stop
	# Перезагрузка:
	service rabbitmq-server restart
	# Статус:
	service rabbitmq-server status
	```
* Управление сервером:
	```sh
	# Старт
	rabbitmqctl start
	# Стоп:
	rabbitmqctl stop
	```
	
* Управление юзерами
	```sh
	# Добавить юзера
	rabbitmqctl add_user cinder CINDER_PASS
	# Изменить пароль юзеру
	rabbitmqctl change_password cinder NEW_PASS
	# Выставить привелегии юзеру
	rabbitmqctl set_permissions cinder ".*" ".*" ".*"
	# Просмотреть список привелегий
	rabbitmqctl list_permissions
	```