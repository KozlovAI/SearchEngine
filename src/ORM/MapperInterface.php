<?php
namespace Motorway\SearchEngine\ORM;

use \Motorway\SearchEngine\Config\ConfigInterface;

interface MapperInterface
{
	/**
	 * Конструктор класса 
	 * 
	 * @param ConfigInterface $config
	 */
	public function __construct(ConfigInterface $config);

	/**
	 * Возвращает имя первичного ключа
	 * 
	 * @return string
	 */
	public function key();

	/**
	 * Возвращает имена полей сущности
	 * 
	 * @return array
	 */
	public function columns();

	/**
	 * Добавление записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity);

	/**
	 * Изменение записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity);

	/**
	 * Добавление или изменение записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function save(EntityInterface $entity);

	/**
	 * Удаление записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity);
}