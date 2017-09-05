<?php

namespace Saq\RabbitMqQueueBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * class:  RabbitMqQueueExtension
 * -----------------------------------------------------
 * @author  Saqot (Mihail Shirnin) <saqott@gmail.com>
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

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$this->container = $container;

		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');

		$configuration = new Configuration($this->name);
		$this->config = $this->processConfiguration($configuration, $configs);

		$container->setParameter('foo', $this->config['foo']);

		//$this->loadConnections();
	}

//	protected function loadConnections()
//	{
//		foreach ($this->config['connections'] as $key => $connection) {
//			$classParam =
//				$connection['lazy']
//					? '%old_sound_rabbit_mq.lazy.connection.class%'
//					: '%old_sound_rabbit_mq.connection.class%';
//
//			$definition = new Definition('%old_sound_rabbit_mq.connection_factory.class%', array(
//				$classParam, $connection,
//			));
//			$definition->setPublic(false);
//			$factoryName = sprintf('old_sound_rabbit_mq.connection_factory.%s', $key);
//			$this->container->setDefinition($factoryName, $definition);
//
//			$definition = new Definition($classParam);
//			if (method_exists($definition, 'setFactory')) {
//				// to be inlined in services.xml when dependency on Symfony DependencyInjection is bumped to 2.6
//				$definition->setFactory(array(new Reference($factoryName), 'createConnection'));
//			} else {
//				// to be removed when dependency on Symfony DependencyInjection is bumped to 2.6
//				$definition->setFactoryService($factoryName);
//				$definition->setFactoryMethod('createConnection');
//			}
//
//			$this->container->setDefinition(sprintf('old_sound_rabbit_mq.connection.%s', $key), $definition);
//		}
//	}
}

