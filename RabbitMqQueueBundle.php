<?php

namespace Saq\RabbitMqQueueBundle;

use Saq\RabbitMqQueueBundle\DependencyInjection\RabbitMqQueueExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * class:  RabbitMqQueueBundle
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle
 * -----------------------------------------------------
 * 05.09.2017
 */
class RabbitMqQueueBundle extends Bundle
{
	private static $oContainer;


	/**
	 * @param ContainerInterface|null $container
	 */
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

	public function getContainerExtension()
	{
		if (null === $this->extension) {
			$this->extension = new RabbitMqQueueExtension();
		}

		return $this->extension;
	}

}
