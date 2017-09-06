<?php

namespace Saq\RabbitMqQueueBundle\RabbitMq;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * class:  MqJob
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\RabbitMq
 * -----------------------------------------------------
 * 06.09.2017
 */
class MqJob
{

	/**
	 * @var AMQPMessage
	 */
	private $msg;

	public function __construct(AMQPMessage $msg)
	{
		$this->msg = $msg;
	}

	/**
	 * Получить обработанный результат. В случае. если в строке JSON , то на выходе массив
	 * @return mixed|string|array
	 */
	public function getData()
	{
		$data = $this->getValue();
		if ($this->isValidJSON($data)) {
			$data = json_decode($data, JSON_UNESCAPED_UNICODE);
		}

		return $data;
	}

	/**
	 * Получить не обработанный результат
	 * @return string
	 */
	public function getValue()
	{
		return $this->msg->body;
	}

	/**
	 * Пропустить, оставить задание в очереди
	 * @return $this
	 */
	public function skip()
	{
		$this->msg->delivery_info['channel']->basic_nack($this->msg->delivery_info['delivery_tag']);

		return $this;
	}

	/**
	 * Остановить очередь на текущем задании. Само задание остается в очереди
	 * @return $this
	 */
	public function cancel()
	{
		$this->msg->delivery_info['channel']->basic_cancel($this->msg->delivery_info['consumer_tag']);

		return $this;
	}

	/**
	 * Удалить задание из очереди
	 * @return $this
	 */
	public function delete()
	{
		$this->msg->delivery_info['channel']->basic_ack($this->msg->delivery_info['delivery_tag']);

		return $this;
	}

	/**
	 * Проверяем значение на наличие JSON
	 * @param $str
	 * @return bool
	 */
	private function isValidJSON($str)
	{
		json_decode($str, JSON_UNESCAPED_UNICODE);

		return (json_last_error() == JSON_ERROR_NONE);
	}


}

