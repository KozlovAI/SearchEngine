<?php
namespace Motorway\SearchEngine\SearchORM\Sphinx;

use \Motorway\SearchEngine\Config\ConfigInterface;
use \Motorway\SearchEngine\DB\Connection;
use \Motorway\SearchEngine\SearchORM\EntityInterface;

class Delta extends Simple 
{
	protected $writer;

	public function __construct(ConfigInterface $config)
	{
		parent::__construct($config);

		$this->writer = $this->makeWriter($config);
	}

	/**
	 * Добавляет запись
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity)
	{
		$saveEntity = $this->writer->entity();
		$saveEntity->index       = $this->name;
		$saveEntity->rel_id      = $entity->id();
		$saveEntity->update_time = new \DateTime();

		return 1 == 1
			&& $this->beforeInsert($entity) 
			&& $saveEntity->insert()
			&& $this->afterInsert($entity); 
	}

	/**
	 * Изменяет запись
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity)
	{
		$saveEntity = $this->writer->entity();
		$saveEntity->index       = $this->name;
		$saveEntity->rel_id      = $entity->id();
		$saveEntity->update_time = new \DateTime();

		return 1 == 1
			&& $this->beforeUpdate($entity)
			&& $entity->update()
			&& $this->afterUpdate($entity);
	}

	/**
	 * Сохраняет запись
	 * 
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity)
	{
		$saveEntity = $this->writer->entity();
		$saveEntity->index       = $this->name;
		$saveEntity->rel_id      = $entity->id();
		$saveEntity->update_time = new \DateTime();
		$saveEntity->deleted     = true;

		return 1 == 1
			&& $this->beforeDelete($entity)
			&& $entity->delete()
			&& $this->afterDelete($entity);

	}

	/**
	 * Возвращает DB маппер для таблицы в которую
	 * записываются изменения
	 * 
	 * @param  \Motorway\SearchEngine\Config\ConfigInterface $entity
	 * @return \Motorway\SearchEngine\DB\ORM\MapperInterface
	 */
	protected function makeWriter(ConfigInterface $config)
	{
		$source = $config->get('source');

		if (empty($source)) {
			throw new \LogicException("source is empty", 1);
		}

		$conn = Connection::getConnection($source);

		return new DB\Changes($conn);
	}

	/**
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	protected function beforeInsert(EntityInterface $entity)
	{
		return $entity->beforeInsert() && $entity->beforeSave();
	}

	/**
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	protected function beforeUpdate(EntityInterface $entity)
	{
		return $entity->beforeUpdate() && $entity->beforeSave();
	}

	/**
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	protected function beforeDelete(EntityInterface $entity)
	{
		return $entity->beforeDelete();
	}

	/**
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	protected function afterInsert(EntityInterface $entity)
	{
		return $entity->afterInsert() && $entity->afterSave();
	}

	/**
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	protected function afterUpdate(EntityInterface $entity)
	{
		return $entity->afterUpdate() && $entity->afterSave();
	}

	/**
	 * @param  \Motorway\SearchEngine\SearchORM\EntityInterface $entity
	 * @return bool
	 */
	protected function afterDelete(EntityInterface $entity)
	{
		return $entity->afterDelete();
	}
}