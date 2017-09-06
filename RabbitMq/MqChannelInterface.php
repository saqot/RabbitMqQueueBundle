<?php

namespace Saq\RabbitMqQueueBundle\RabbitMq;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Interface:  MqChannelInterface
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\RabbitMq\Channel
 * -----------------------------------------------------
 * 05.09.2017
 */
interface MqChannelInterface
{
	/**
	 * MqChannelInterface constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container);

	/**
	 * обработка заданий из очереди
	 * @param MqJob $job
	 * @return mixed
	 */
	public function processJob(MqJob $job);
}