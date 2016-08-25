<?php
namespace Motorway\SearchEngine\Config;

class Config implements ConfigInterface
{
	protected $name;

	protected $options;

	/**
	 * Коснтруктор класса
	 * 
	 * @param string|array $options Либо данные, либо путь к файлу
	 * @param string       $name    имя конфига
	 */
	public function __construct($options, $name = false)
	{
		$this->name = $name;

		if (is_array($options)) {
			$this->options = $options;
		} else {
			$this->load($options);
		}
	}

	/**
	 * Загружает конфиг из файла
	 * 
	 * @return string путь к файлу
	 */
	public function load($path)
	{
		if (!file_exists($path)) {
			throw new \LogicException("Config '{$path}' not found");
		}

		$this->options = include($path);
		$this->name = strtolower(basename($path, '.php'));
	}

	/**
	 * Возвразает имя конфига
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name ?: get_class($this);
	}

	/**
	 * Возвращает значение параметра из конфига
	 * 
	 * @param  string $name    название параметра
	 * @param  mixed  $default значение по умолчанию
	 * @return mixed
	 */
	public function get($name, $default = null)
	{
		return isset($this->options[$name]) ? $this->options[$name] : $default;
	}
}