<?php

namespace Saq\RabbitMqQueueBundle\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * class:  MqChannel
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\RabbitMq\Channel
 * -----------------------------------------------------
 * 05.09.2017
 */
class MqChannel implements MqChannelInterface
{

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var AMQPStreamConnection|AbstractConnection
	 */
	private $connection;

	/**
	 * @var AMQPChannel
	 */
	private $channel;

	/**
	 * Имя очереди может содержать до 255 байт UTF-8 символов
	 * @var string
	 */
	public $queue = 'hello';

	/**
	 * Проверка, инициирован ли обмен, без того, чтобы изменять состояние сервера
	 * @var bool
	 */
	protected $passive = false;

	/**
	 * Флаг сохранения данных при падении очередь или перезагрузке брокера
	 * @var bool
	 */
	protected $durable = true;

	/**
	 * Использование только одного соединением, и очередь будет удалена при его закрытии
	 * @var bool
	 */
	protected $exclusive = false;

	/**
	 * Очередь удаляется, когда отписывается последний подписчик
	 * @var bool
	 */
	protected $auto_delete = false;

	/**
	 * @var bool
	 */
	protected $nowait = false;

	/**
	 * @var null
	 */
	protected $arguments = null;

	/**
	 * @var null
	 */
	protected $ticket = null;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->connection = $this->container->get('saq.rabbitmq.connection')->getConnection();
		$this->channel = $this->connection->channel();
		$this->channel->queue_declare($this->queue, $this->passive, $this->durable, $this->exclusive, $this->auto_delete);

	}

	/**
	 * закрываем соединения
	 */
	function __destruct()
	{
		if ($this->channel) {
			$this->channel->close();
		}
		if ($this->connection) {
			$this->connection->close();
		}
	}

	/**
	 * @return ContainerInterface
	 */
	protected function getContainer()
	{
		return $this->container;
	}

	/**
	 * @return AMQPChannel
	 */
	protected function getChannel()
	{
		return $this->channel;
	}

	/**
	 * Удаление канала со всеми заданиями в нем
	 * @return bool
	 */
	public function delete()
	{
		$this->channel->queue_delete($this->queue);

		return true;
	}

	/**
	 * @return AbstractConnection|AMQPStreamConnection
	 */
	protected function getConnection()
	{
		return $this->connection;
	}

	public function addJob($data, $properties = [])
	{
		$data = is_array($data) ? json_encode($data) : $data;
		$msg = new AMQPMessage($data, $properties);

		$this->getChannel()->basic_publish($msg, '', $this->queue);

		return $this;
	}

	/**
	 * Регистрация и вызов слушателя, полученные данные с канала отправляются на метод processJob
	 * Метод вызываем через консоль, иначе при пустой очереди браузер будет в зависе.
	 */
	public function listen()
	{
		$this->channel->basic_consume(
			$this->queue,
			'',
			false,
			false,
			false,
			false,
			function (AMQPMessage $msg) {
				return $this->processJob(new MqJob($msg));
			}
		);

		while (count($this->channel->callbacks)) {
			echo "\n wait . . .";
			$this->channel->wait();
		}
	}

	/**
	 * обработка заданий из очереди
	 * @param MqJob $job
	 * @return mixed|void
	 */
	public function processJob(MqJob $job)
	{
		// тут логика обработки заданий из очереди
	}

}