<?php
namespace Motorway\SearchEngine;

define('SRC_PATH', __DIR__);
define('ROOT_PATH', realpath(SRC_PATH .'/../'));

class Index
{
	protected static $instances = array();

	protected static $configLoadPath = false;

	protected static $configSavePath = false;

	/**
	 * Возвращает инстанс класс по сохраненному имени
	 * 
	 * @param  string  $name
	 * @param  mixed   $config
	 * @return self
	 */
	public static function getInstance($name, $config = false)
	{
		if (isset(self::$instances[$name])) {
			return self::$instances[$name];
		}

		return self::$instances[$name] = new self($config ?: $name);
	}

	/**
	 * Возвращает или устанавливает директорию по умолчанию для загрузки конфигов
	 * 
	 * @param  string $path
	 * @return string
	 */
	public static function configLoadPath($path = false)
	{
		if ($path) {
			return self::$configLoadPath = $path;
		}

		if (self::$configLoadPath) {
			return self::$configLoadPath;
		}

		return ROOT_PATH . '/config/';
	}

	/**
	 * Возвращает или устанавливает директорию по умолчанию для выгрузки конфигов
	 * 
	 * @param  string $path
	 * @return string
	 */
	public static function configSavePath($path = false)
	{
		if ($path) {
			return self::$configSavePath;
		}

		if (self::$configSavePath) {
			return self::$configSavePath;
		}

		return ROOT_PATH .'/output/';
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