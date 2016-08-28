<?php
namespace Motorway\SearchEngine\Config;

class Factory
{
	protected static $class_map = [
		'sphinx_simple' => '\\Motorway\\SearchEngine\\Config\\Sphinx\\Simple',
		'sphinx_delta'  => '\\Motorway\\SearchEngine\\Config\\Sphinx\\Delta',
		'sphinx_rt'     => '\\Motorway\\SearchEngine\\Config\\Sphinx\\RT',
	];

	public static function create($config)
	{
		if (is_object($config)) {
			if ($config instanceof ConfigInterface) {
				return $config;
			}
			
			throw new \LogicException('Config must be instanceof \\Motorway\\SearchEngine\\Config\\ConfigInterface');	
		}

		if (!file_exists($config)) {
			throw new \LogicException("Config path '{$config}' not found");
		}

		$name = basename($config, '.php');
		$options = include($config);
		if (empty($options)) {
			throw new \LogicException("Config '{$config}' is empty");
			
		}

		$type = $options['type'];
		if (class_exists($type) && $type instanceof ConfigInterface) {
			return new $type($options, $name);
		}

		if (isset(self::$class_map[$type])) {
			return new self::$class_map[$type]($options, $name);
		}

		throw \LogicException("Undefined config type");
	}
}