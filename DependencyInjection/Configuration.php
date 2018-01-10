<?php

namespace Saq\RabbitMqQueueBundle\DependencyInjection;

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

	protected $configDefault;


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
		$builder = new TreeBuilder();
		$root = $builder->root($this->name);
		$root
			->children()
				->arrayNode('connection')
					->children()
						->scalarNode('host')->defaultValue('localhost')->end()
						->scalarNode('port')->defaultValue(5672)->end()
						->scalarNode('user')->defaultValue('guest')->end()
						->scalarNode('password')->defaultValue('guest')->end()
					->end()
				->end()
				->arrayNode('channel')
					->prototype('array')
						->children()
							->scalarNode('service')->isRequired()->end()
							->scalarNode('max_running')->isRequired()->end()
						->end()
					->end()
				->end()
				->scalarNode('url')->end()

			->end()
		;


		return $builder;
	}
}