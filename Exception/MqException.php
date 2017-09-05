<?php

namespace Saq\RabbitMqQueueBundle\Exception;

use Throwable;

/**
 * class:  MqException
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Exception
 * -----------------------------------------------------
 * 05.09.2017
 */
class MqException extends \Exception implements MqExceptionInterface
{

	/**
	 * MqException constructor.
	 * @param string         $message
	 * @param int            $code
	 * @param Throwable|null $previous
	 */
	public function __construct($message = "", $code = 500, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

