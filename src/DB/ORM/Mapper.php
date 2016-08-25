<?php
namespace Motorway\SearchEngine\DB\ORM;

use \Motorway\SearchEngine\DB\Connection;
use \Motorway\SearchEngine\DB\Schema;

class Mapper implements MapperInterface
{
	protected $connection;

	protected $tableName;

	protected $entityName;

	protected $schema;

	protected $columns;

	protected $keyName;

	/**
	 * @param \Motorway\SearchEngine\DB\Connection $connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
		$this->setup();
	}

	public function setup()
	{
		return $this;
	}

	/**
	 * @param  string $tableName
	 * @return mixed
	 */
	public function tableName($tableName = false)
	{
		if ($tableName) {
			$this->tableName = $tableName;
			$this->schema = null;

			return $this;
		}

		return $this->tableName;
	}

	/**
	 * @param  string $entityName
	 * @return mixed
	 */
	public function entityName($entityName = false)
	{
		if ($entityName) {
			$this->entityName = $entityName;

			return $this;
		}

		return $this->entityName ?: '\Motorway\SearchEngine\DB\ORM\Entity';
	}

	/**
	 * @param  array  $schema
	 * @return mixed
	 */
	public function schema(array $schema = [])
	{
		if (is_null($this->schema)) {
			$this->schema = new Schema($this->connection, $this->tableName);
		}

		if ($schema) {
			$this->schema->processCached($schema);

			return $this;
		}

		return $this->schema;
	}

	/**
	 * @param  array  $columns
	 * @return mixed
	 */
	public function columns(array $columns = [])
	{
		if ($columns) {
			$this->columns = $columns;

			return $this;
		}

		if ($this->columns) {
			return $this->columns;
		}

		$columns = array();
		if ($this->schema()->isExist()) {
			$columns = array_keys($this->schema()->getColumns());
		}

		return $this->columns = $columns;
	}

	/**
	 * @param  string $keyName
	 * @return mixed
	 */
	public function key($keyName = false)
	{
		if ($keyName) {
			$this->keyName = $keyName;

			return $this;
		}

		if ($this->keyName) {
			return $this->keyName;
		}

		$keyName = false;
		if ($this->schema()->isExist()) {
			$indexes = $this->schema()->getIndexes();
			foreach ($indexes as $index) {
				if ($index->isPrimary()) {
					$keyName = $index->getColumns();
					break;
				}
			}
		}

		return $this->keyName = $keyName ?: 'id';
	}

	/**
	 * Создает инстанс сущности
	 * 
	 * @return \Motorway\SearchEngine\DB\Entity
	 */
	public function entity()
	{
		$className = $this->entityName();

		return new $className;
	}

	/**
	 * Создает инстанс сущности и сохраняет ее в БД
	 * 
	 * @param  array $parms значение полей сущности
	 * @return \Motorway\SearchEngine\DB\Entity
	 */
	public function create(array $parms = [])
	{
		$entity = $this->entity();
		$entity->assign($parms);
		$entity->save();

		return $entity;
	}

	/**
	 * Добавляет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity)
	{
		return 1 == 1
			&& $this->beforeInsert($entity)
			&& $this->createQueryBuilder()
				->insert($this->tableName())
				->values(
					$this->convertEntityToDB($entity)
				)
				->execute()
			&& $this->afterInsert($entity);
	}

	/**
	 * Обновляет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity)
	{
		return 1 == 1
			&& $this->beforeUpdate($entity)
			&& $this->createQueryBuilder()
				->update($this->tableName())
				->set(
					$this->convertEntityToDB($entity)
				)
				->where($this->key() .' = ?')
				->execute()
			&& $this->afterUpdate($entity);
	}

	/**
	 * Сохраняет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
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
	 * Удаляет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity)
	{
		return 1 == 1
			&& $this->beforeDelete($entity)
			&& $this->createQueryBuilder()
				->delete($this->tableName())
				->where($this->key() .' = ?')
				->execute()
			&& $this->afterDelete($entity);
	}

	public function queryBuilder()
	{
		return $this->connection->createQueryBuilder();
	}

	protected function beforeInsert(EntityInterface $entity)
	{
		return $entity->beforeInsert() && $entity->beforeSave();
	}

	protected function beforeUpdate(EntityInterface $entity)
	{
		return $entity->beforeUpdate() && $entity->beforeSave();
	}

	protected function beforeDelete(EntityInterface $entity)
	{
		return $entity->beforeDelete();
	}

	protected function afterInsert(EntityInterface $entity)
	{
		return $entity->afterInsert() && $entity->afterSave();
	}

	protected function afterUpdate(EntityInterface $entity)
	{
		return $entity->afterUpdate() && $entity->afterSave();
	}

	protected function afterDelete(EntityInterface $entity)
	{
		return $entity->afterDelete();
	}
}