# Установка RabbitMq на windows.

**ВАЖНО!**
> Имя компьютера в системе должно быть на латинице и в нижнем регистре. Иначе Erlang работать не будет.

**Приступим к установке**

1. Скачать и установить последнюю версию Erlang <http://www.erlang.org/download.html>
	> желательно использовать файл с именем "Windows 64-bit Binary File"
2. Скачать и установить последнюю версию RabbitMQ <https://www.rabbitmq.com/install-windows.html>
3. Идем в раздел с установленным RabbitMQ, пути могут быть такими
	```sh
	C:\Program Files (x86)\RabbitMQ Server\rabbitmq_server-3.3.5\
	```
	или
	```sh
	C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\
	```
	Где **rabbitmq_server-3.3.5** - это вресия сервера и у вас она может отличатся.
4. В разделе с установленным RabbitMQ создаем две папки **conf** и **base**? должно получиться вот так
	```sh
	C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\conf\
	C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\base\
    ```
5. В папку `C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\conf\` положить конфиг файл 
	взять из `C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\etc\` и переименовать его в `rabbitmq.config`
	
6. Установить переменные окружения для windows (Свойства системы -> Перменные среды)
	* конфиг файла 
		* имя `RABBITMQ_CONFIG_FILE`
		* путь `C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\conf\rabbitmq`
	* логи и базы данных
		* имя `RABBITMQ_BASE`
		* путь `C:\Program Files\RabbitMQ Server\rabbitmq_server-3.3.5\base`
	* путь по Erlang
		* имя `ERLANG_HOME`
        * путь `C:\Program Files\erl9.0\bin`
6. Запускаем консольное прихожение windows.
	* В поиске набираем **CMD** и из списка можно выбрать два варианта, консоль от RabbitMQ или консоль от windows
		> Запускаем консоль от Администратора
	* Если выбрали консоль от windows то выполняем код 
		```sh
		cd	C:\Program Files\RabbitMQ Server\rabbitmq_server-3.6.11\sbin
		```
	* запускаем установку сервера
		```sh
		rabbitmq-service install
		```
	* проверяем статус сервера, если ошибки, то гуглим и исправляем их
		```sh
		rabbitmq-service status
		```sh
	* устанавливаем менеджмент плагин
		```sh	
		rabbitmq-plugins enable rabbitmq_management
		```
	* Рестартуем сервис RabbitMQ
		```sh	
		rabbitmq-service stop
		rabbitmq-service start
		```
7. Готово. Заходим в админку RabbitMQ <http://localhost:15672>
	* логин: `guest`
	* пароль: `guest`