<?php
namespace Motorway\SearchEngine;

class Index
{
	protected static $instances = array();

	/**
	 * Возвращает инстанс класс по сохраненному имени
	 * 
	 * @param  string  $name
	 * @param  mixed   $config [description]
	 * @return self
	 */
	public static function getInstance($name, $config = false)
	{
		if (isset(self::$instances[$name])) {
			return self::$instances[$name];
		}

		return self::$instances[$name] = new self($config);
	}

	protected $config;
	
	protected $orm;

	/**
	 * Конструктор класса
	 * @param mixed $config
	 */
	public function __construct($config)
	{
		$this->config = Config\Factory::create($config);
		$this->orm    = SearchORM\Factory::createFromConfig($this->config);
	}

	/**
	 * @return \Motorway\SearchEngine\Config\ConfigInterface
	 */
	public function config()
	{
		return $this->config;
	}

	/**
	 * @return \Motorway\SearchEngine\SearchORM\MapperInterface
	 */
	public function orm()
	{
		return $this->orm; 
	}
}