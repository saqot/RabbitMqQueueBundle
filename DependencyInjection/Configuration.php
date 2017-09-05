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
			->booleanNode('foo')->defaultValue(false)->end()
			->end();

		return $tree;
	}
}