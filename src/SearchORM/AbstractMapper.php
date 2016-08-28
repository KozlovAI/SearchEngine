<?php
namespace Motorway\SearchEngine\SearchORM;

use \Motorway\SearchEngine\Config\ConfigInterface;

abstract class AbstractMapper implements MapperInterface
{
	protected $columns;

	/**
	 * Конструктор класса 
	 * 
	 * @param \Motorway\SearchEngine\Config\ConfigInterface $config
	 */
	public function __construct(ConfigInterface $config)
	{
		$this->config = $config;
		$this->setup();
	}

	/**
	 * Возвращает имена полей сущности
	 * 
	 * @return string[]
	 */
	public function columns()
	{
		if ($this->columns) {
			return $this->columns;
		}

		return $this->columns = array_unique(array_merge(
			(array) $this->key(), 
			(array) $this->config->get('columns')
		));
	}

	/**
	 * Создает инстанс сущности мапера
	 * 
	 * @return \Motorway\SearchEngine\SearchORM\EntityInterface
	 */
	public function entity()
	{
		return new Entity($this);
	}

	/**
	 * Создает инстанс сущности и сохраняет ее
	 * 
	 * @return \Motorway\SearchEngine\SearchORM\EntityInterface
	 */
	public function create(array $parms = array())
	{
		$entity = $this->entity();
		$entity->assign($parms);
		$entity->save();
	}

	/**
	 * Добавление записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity)
	{
		return 1 == 1
			&& $this->beforeInsert($entity) !== false
			&& $this->doInsert($entity)     !== false
			&& $this->afterInsert($entity)  !== false;
	}

	/**
	 * Изменение записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity)
	{
		return 1 == 1
			&& $this->beforeInsert($entity) !== false
			&& $this->doUpdate($entity)     !== false
			&& $this->afterInsert($entity)  !== false;
	}

	/**
	 * Добавление или изменение записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function save(EntityInterface $entity)
	{
		if ($entity->id()) {
			return $this->update($entity);
		}

		return $this->insert($entity);
	}

	/**
	 * Удаление записи
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity)
	{
		return 1 == 1
			&& $this->beforeDelete($entity) !== false
			&& $this->doDelete($entity)     !== false
			&& $this->afterDelete($entity)  !== false;
	}

	/**
	 * Вызывается после создания экземпляра класса
	 * 
	 * @return self
	 */
	protected function setup()
	{
		return $this;
	}

	/**
	 * Вызывается перед добавлением записи
	 * вернув false можно прервать процесс добавления
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function beforeInsert(EntityInterface $entity)
	{
		return $entity->beforeInsert() && $entity->beforeSave();
	}

	/**
	 * Вызывается перед изменением записи
	 * вернув false можно прервать процесс
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function beforeUpdate(EntityInterface $entity)
	{
		return $entity->beforeUpdate() && $entity->beforeSave();
	}

	/**
	 * Вызывается перед удалением записи
	 * вернув false можно прервать процесс
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function beforeDelete(EntityInterface $entity)
	{
		return $entity->beforeDelete();
	}

	/**
	 * Вызывается после добавлением записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function afterInsert(EntityInterface $entity)
	{
		return $entity->afterInsert() && $entity->afterSave();
	}

	/**
	 * Вызывается после изменения записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function afterUpdate(EntityInterface $entity)
	{
		return $entity->afterUpdate() && $entity->afterSave();
	}

	/**
	 * Вызывается после удаления записи
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function afterDelete(EntityInterface $entity)
	{
		return $entity->afterDelete();
	}

	/**
	 * Производит непосредственное добавление записи в индекс
	 * Возвращает результат добавления
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	abstract protected function doInsert(EntityInterface $entity);
	
	/**
	 * Производит непосредственное обновление записи в индексе
	 * Возвращает результат изменения
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	abstract protected function doUpdate(EntityInterface $entity);

	/**
	 * Производит непосредственное удаление записи в индексе
	 * Возвращает результат удаления
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	abstract protected function doDelete(EntityInterface $entity);
}