<?php

namespace Saq\RabbitMqQueueBundle\RabbitMq;

use PhpAmqpLib\Connection\AMQPLazyConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Saq\RabbitMqQueueBundle\Exception\MqException;

/**
 * class:  MqConnection
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\RabbitMq
 * -----------------------------------------------------
 * 05.09.2017
 */
class MqConnection
{
	/** @var array */
	private $parameters = [
		'host'               => 'localhost',
		'port'               => 5672,
		'user'               => 'guest',
		'password'           => 'guest',
		'vhost'              => '/',
		'insist'             => false,
		'login_method'       => 'AMQPLAIN',
		'login_response'     => null,
		'locale'             => 'en_US',
		'connection_timeout' => 3.0,
		'read_write_timeout' => 3.0,
		'context'            => null,
		'keepalive'          => false,
		'heartbeat'          => 0,
		'channels'          => [],
	];
	/** @var array */
	private $channels = [];

	/** @var AMQPLazyConnection */
	private $connection;

	/**
	 * Получаем праметры из конфигуратора
	 * @param array $parameters
	 * @return $this
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = array_merge($this->parameters, $parameters);

		return $this;
	}

	/**
	 *
	 * @param array $channels
	 * @return $this
	 */
	public function setChannels(array $channels = [])
	{
		$this->channels = $channels;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getRegistredChannels(): array
	{
		return $this->channels;
	}


    /**
     * @return AMQPLazyConnection|AMQPStreamConnection
     * @throws MqException
     */
    public function getConnection()
	{
		if (!$this->connection) {
			$this->connection = new AMQPStreamConnection(
				$this->parameters['host'],
				$this->parameters['port'],
				$this->parameters['user'],
				$this->parameters['password'],
				$this->parameters['vhost'],
				$this->parameters['insist'],
				$this->parameters['login_method'],
				$this->parameters['login_response'],
				$this->parameters['locale'],
				$this->parameters['connection_timeout'],
				$this->parameters['read_write_timeout'],
				$this->parameters['context'],
				$this->parameters['keepalive'],
				$this->parameters['heartbeat']
			);
		}


		if (!$this->connection or !$this->connection->isConnected()) {
			throw new MqException('Ошибка подключения к RabbitMq');
		}

		return $this->connection;
	}
}

