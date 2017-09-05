<?php

namespace Saq\RabbitMqQueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * class:  Configuration
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\DependencyInjection
 * -----------------------------------------------------
 * 05.09.2017
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * Configuration constructor.
	 * @param   string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getConfigTreeBuilder()
	{
		$tree = new TreeBuilder();
		$rootNode = $tree->root($this->name);
		$rootNode
			->children()
			->booleanNode('debug')->defaultValue('%kernel.debug%')->end()
			->booleanNode('foo')->cannotBeEmpty()->end()
			->end();

		//$this->addConnections($rootNode);

		return $tree;
	}

//	protected function addConnections(ArrayNodeDefinition $node)
//	{
//		$node
//			->fixXmlConfig('connection')
//			->children()
//				->arrayNode('connections')
//					->useAttributeAsKey('key')
//					->canBeUnset()
//					->prototype('array')
//						->children()
//							->scalarNode('url')->defaultValue('')->end()
//							->scalarNode('host')->defaultValue('localhost')->end()
//							->scalarNode('port')->defaultValue(5672)->end()
//							->scalarNode('user')->defaultValue('guest')->end()
//							->scalarNode('password')->defaultValue('guest')->end()
//							->scalarNode('vhost')->defaultValue('/')->end()
//							->booleanNode('lazy')->defaultFalse()->end()
//							->scalarNode('connection_timeout')->defaultValue(3)->end()
//							->scalarNode('read_write_timeout')->defaultValue(3)->end()
//							->booleanNode('use_socket')->defaultValue(false)->end()
//							->arrayNode('ssl_context')
//								->useAttributeAsKey('key')
//								->canBeUnset()
//								->prototype('variable')->end()
//							->end()
//							->booleanNode('keepalive')->defaultFalse()->info('requires php-amqplib v2.4.1+ and PHP5.4+')->end()
//							->scalarNode('heartbeat')->defaultValue(0)->info('requires php-amqplib v2.4.1+')->end()
//							->scalarNode('connection_parameters_provider')->end()
//							->end()
//						->end()
//					->end()
//				->end()
//		;
//	}
}