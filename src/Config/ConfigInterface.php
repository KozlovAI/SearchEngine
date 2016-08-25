<?php
namespace Motorway\SearchEngine\Config;

interface ConfigInterface
{
	/**
	 * Коснтруктор класса
	 * 
	 * @param string|array $options Либо данные, либо путь к файлу
	 * @param string       $name    имя конфига
	 */
	public function __construct($options, $name = false);

	/**
	 * Загружает конфиг из файла
	 * 
	 * @return string путь к файлу
	 */
	public function load($path);

	/**
	 * Возвразает имя конфига
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * Возвращает значение параметра из конфига
	 * 
	 * @param  string $name    название параметра
	 * @param  mixed  $default значение по умолчанию
	 * @return mixed
	 */
	public function get($name, $default = null);
}