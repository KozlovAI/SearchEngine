<?php
namespace Motorway\SearchEngine\SearchORM;

use \Motorway\SearchEngine\Config\ConfigInterface;

class Factory
{
	protected static $class_map = [
		'sphinx_simple' => '\\Motorway\\SearchEngine\\SearchORM\\Sphinx\\Simple',
		'sphinx_delta'  => '\\Motorway\\SearchEngine\\SearchORM\\Sphinx\\Delta',
		'sphinx_rt'     => '\\Motorway\\SearchEngine\\SearchORM\\Sphinx\\RT',
	];

	public static function create($orm, ConfigInterface $config)
	{
		if (is_object($orm)) {
			if ($orm instanceof MapperInterface) {
				return $orm;
			}
			
			throw new \LogicException('ORM must be instanceof \\Motorway\\SearchEngine\\SearchORM\\MapperInterface');	
		}

		if (class_exists($orm) && $orm instanceof ConfigInterface) {
			return new $orm($config);
		}

		if (isset(self::$class_map[$orm])) {
			return new self::$class_map[$orm]($config);
		}

		throw \LogicException("Undefined orm type");
	}

	public static function createFromConfig(ConfigInterface $config)
	{
		$ormName = $config->get('orm', $config->defaultOrm());
		return self::create($ormName, $config);
	}
}