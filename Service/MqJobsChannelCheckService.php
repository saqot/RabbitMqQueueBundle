<?php

namespace Saq\RabbitMqQueueBundle\Service;

use Saq\RabbitMqQueueBundle\RabbitMq\MqChannel;
use Saq\RabbitMqQueueBundle\RabbitMq\MqJob;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Канал для проведения проверок работы с очередью
 * -----------------------------------------------------
 * class:  MqJobsChannelCheckService
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Service
 * -----------------------------------------------------
 * 10.09.2017
 */
class MqJobsChannelCheckService extends MqChannel
{

	public $queue = 'checkQueue';

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
	}

	public function processJob(MqJob $job)
	{
		$val = $job->getData();


		if ($val == 'jobdelete') {
			echo "\n [OK] Задание успешно удалено";
			$job->delete();

		} elseif ($val == 'jobskip') {
			echo "\n [OK] Задание успешно пропущено";
			$job->skip();

		} elseif ($val == 'jobcancel') {
			echo "\n [OK] Оброботка успешно остановлена";
			$job->cancel();
		}
	}


}

