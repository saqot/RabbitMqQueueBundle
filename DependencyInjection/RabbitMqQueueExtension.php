<?php

namespace Saq\RabbitMqQueueBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * class:  RabbitMqQueueExtension
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\DependencyInjection
 * -----------------------------------------------------
 * 05.09.2017
 */
class RabbitMqQueueExtension extends Extension
{
	/**
	 * имя конфига
	 */
	private $name = 'queue_rabbit_mq';

	/**
	 * @var ContainerBuilder
	 */
	private $container;

	/**
	 * @var array
	 */
	private $config = [];

	public function getAlias()
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$this->container = $container;

		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');

		$configuration = new Configuration($this->name);
		$this->config = $this->processConfiguration($configuration, $configs);


		if (!empty($this->config['connection'])) {
			$connDefintion = $container->getDefinition('saq.rabbitmq.connection');
			$connDefintion->addMethodCall('setParameters', [$this->config['connection']]);
		}

	}


}

