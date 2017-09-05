<?php

namespace Saq\StaticHelperBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;


/**
 * class: SaqStaticHelperBundle
 * -----------------------------------------------------
 * @date 19.07.2017
 * @author Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package Saq\StaticHelperBundle
 */
class SaqStaticHelperBundle extends Bundle
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
