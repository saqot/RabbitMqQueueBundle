<?php

namespace Saq\RabbitMqQueueBundle\RabbitMq;


use PhpAmqpLib\Connection\AMQPLazyConnection;

/**
 * class:  MqConnectionFactory
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\RabbitMq
 * -----------------------------------------------------
 * 05.09.2017
 */
class MqConnectionFactory
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
		'connection_timeout' => 3,
		'read_write_timeout' => 3,
		'context'            => null,
		'keepalive'          => false,
		'heartbeat'          => 0,
	];
	/** @var AMQPLazyConnection */
	private $connection;

	/**
	 * Получаем праметры из конфигуратора
	 * @param array $parameters
	 */
	public function setParameters(array $parameters)
	{
		$this->parameters = array_merge($this->parameters, $parameters);
	}

	public function getConnection()
	{
		if (!$this->connection) {
			$this->connection = new AMQPLazyConnection(
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
				$this->parameters['context'],
				$this->parameters['keepalive'],
				$this->parameters['heartbeat']
			);
		}

		return $this->connection;
	}
}

