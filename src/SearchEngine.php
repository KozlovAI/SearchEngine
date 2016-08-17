<?php
namespace Motorway;

class SearchEngine
{
	protected $instances = array();

	public static function getInstance($name)
	{
		if (isset(self::$instances[$name])) {
			return self::$instances[$name];
		}

		return self::$instances[$name] = new self();
	}
}