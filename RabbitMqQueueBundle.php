<?php

namespace Saq\RabbitMqQueueBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * class:  RabbitMqQueueBundle
 * -----------------------------------------------------
 * @author  Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle
 * -----------------------------------------------------
 * 05.09.2017
 */
class RabbitMqQueueBundle extends Bundle
{
	private static $oContainer;

	public function setContainer(ContainerInterface $container = null)
	{
		parent::setContainer($container);
		self::$oContainer = $container;
	}

	/**
	 * @return ContainerInterface
	 */
	public static function getContainer()
	{
		return self::$oContainer;
	}

}
