<?php

namespace Saq\RabbitMqQueueBundle\Service;

use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

/**
 * class:  MqConsole
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Service
 * -----------------------------------------------------
 * 09.01.2018
 */
class MqConsole
{

	private $error;

	private $result;

	/**
	 * MqConsole run
	 * @param $command
	 * @return $this
	 */
	public function run($command)
	{
		$process = new Process($command);
		$process->setTimeout(60 * 15);
		try {
			$process->run();
			if (!$process->isSuccessful()) {
				$this->error = $process->getErrorOutput();
			}
			$this->result = $process->getOutput();
			$process->stop();

		} catch (ProcessTimedOutException $e) {
			$this->error = $e;
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}


}

