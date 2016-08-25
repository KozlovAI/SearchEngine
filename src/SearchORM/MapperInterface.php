<?php
namespace Motorway\SearchEngine\SearchORM;

use \Motorway\SearchEngine\Config\ConfigInterface;
use \Motorway\SearchEngine\DB\ORM\MapperInterface as DB_ORM_MapperInterface;

interface MapperInterface
{
	/**
	 * Конструктор класса 
	 * 
	 * @param \Motorway\SearchEngine\Config\ConfigInterface $config
	 */
	public function __construct(ConfigInterface $config);

	/**
	 * Возвращает имя первичного ключа
	 * В случае если ключ составной - возвращается массив
	 * 
	 * @return string|string[]
	 */
	public function key();

	/**
	 * Возвращает имена полей сущности
	 * 
	 * @return string[]
	 */
	public function columns();

	/**
	 * Создает инстанс сущности мапера
	 * 
	 * @return \Motorway\SearchEngine\SearchORM\EntityInterface
	 */
	public function entity();

	/**
	 * Создает инстанс сущности и сохраняет ее
	 * 
	 * @return \Motorway\SearchEngine\SearchORM\EntityInterface
	 */
	public function create(array $parms = array());

	/**
	 * Добавление записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity);

	/**
	 * Изменение записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity);

	/**
	 * Добавление или изменение записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function save(EntityInterface $entity);

	/**
	 * Удаление записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity);
}