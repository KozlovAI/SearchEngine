<?php
namespace Motorway\SearchEngine\ORM\Sphinx;

use \Motorway\SearchEngine\Config\ConfigInterface;
use \Motorway\SearchEngine\ORM\MapperInterface;
use \Motorway\SearchEngine\ORM\EntityInterface;

class Simple implements MapperInterface
{
	protected $columns;

	protected $reader;

	/**
	 * Конструктор класса
	 * 
	 * @param ConfigInterface $config
	 */
	public function __construct(ConfigInterface $config)
	{
		$this->columns = $config->get('columns');
		$this->reader  = $this->makeReader($config);
	}

	/**
	 * Возвращает имя первичного ключа
	 * 
	 * @return string
	 */
	public function key()
	{
		return 'id';
	}

	/**
	 * Возвращает имена полей сущности
	 * 
	 * @return array
	 */
	public function columns()
	{
		return array_keys($this->columns);
	}

	/**
	 * Создает инстанс сущности мапера
	 * 
	 * @return EntityInterface
	 */
	public function entity()
	{
		return new \Motorway\SearchEngine\ORM\Entity($this);
	}

	/**
	 * Создает инстанс сущности и сохраняет ее
	 * 
	 * @return EntityInterface
	 */
	public function create(array $parms = array())
	{
		$entity = $this->entity();
		$entity->assign($parms);
		$entity->save();

		return $entity;
	}

	/**
	 * Добавление записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity)
	{
		return true;
	}

	/**
	 * Изменение записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity)
	{
		return true;
	}

	/**
	 * Добавление или изменение записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function save(EntityInterface $entity)
	{
		return true;
	}

	/**
	 * Удаление записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity)
	{
		return true;
	}
}