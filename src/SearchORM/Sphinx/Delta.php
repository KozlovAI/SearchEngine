<?php
namespace Motorway\SearchEngine\SearchORM\Sphinx;

use \Motorway\SearchEngine\SearchORM\EntityInterface;

class Delta extends Simple 
{
	protected $writer;

	public function save(EntityInterface $entity)
	{
		$isExist = $this->writer()->createQueryBuilder()
			->select('1 as exist')
			->from('se_sphinx_updates')
			->where('index_name = ? and rel_id = ?')
			->setParameter(0, $this->config->getName())
			->setParameter(1, $entity->id())
			->execute()
			->fetch()
		;

		if ($isExist) {
			return $this->update($entity);
		}

		return $this->insert($entity);
	}

	/**
	 * Возвращает инстанс для записи индекса
	 * 
	 * @return \Motorway\SearchEngine\DB\Connection
	 */
	protected function writer()
	{
		if ($this->writer) {
			return $this->writer;
		}

		$dsn = $this->config->get('source');

		return $this->writer = new Utils\DB\Connection($dsn);
	}

	protected function setup()
	{
		$schema = new Utils\DB\Schema($this->writer(), 'se_sphinx_updates');
		$schema->processCached($this->getSchemaChanges());

		$schema = new Utils\DB\Schema($this->writer(), 'se_sphinx_counters');
		$schema->processCached($this->getSchemaCounters());
	}

	/**
	 * Возвращает схему таблицы для записи изменений
	 * 
	 * @return []
	 */
	protected function getSchemaChanges()
	{
		return [
			'columns' => [
				'index_name'  => ['type' => 'string'],
				'rel_id'      => ['type' => 'integer'],
				'update_time' => ['type' => 'datetime'],
				'deleted'     => ['type' => 'integer', 'default' => 0],
			],

			'indexes' => [
				['type' => 'primary', 'columns' => ['index_name', 'rel_id']]
			],
		];
	}

	/**
	 * Возвращает схему таблицы для фиксации кол-ва индексаций
	 * 
	 * @return []
	 */
	protected function getSchemaCounters()
	{
		return [
			'columns' => [
				'index'       => ['type' => 'string'],
				'update_time' => ['type' => 'datetime'],
			],

			'indexes' => [
				['type' => 'primary', 'columns' => ['index']]
			],
		];
	}

	/**
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function doInsert(EntityInterface $entity)
	{
		return (bool) $this->writer()->insert(
			'se_sphinx_updates', 
			$this->convertEntityToDB($entity)
		);
	}

	/**
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function doUpdate(EntityInterface $entity)
	{
		return (bool) $this->writer()->update(
			'se_sphinx_updates', 
			$this->convertEntityToDB($entity), 
			['index_name' => $this->config->getName(), 'rel_id' => $entity->id()]
		);
	}

	/**
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function doDelete(EntityInterface $entity)
	{
		return (bool) $this->writer()->update(
			'se_sphinx_updates', 
			$this->convertEntityToDB($entity, true), 
			['index_name' => $this->config->getName(), 'rel_id' => $entity->id()]
		);
	}

	/**
	 * Возвращает массив полей для записи в индекс
	 * 
	 * @param  EntityInterface $entity
	 * @param  boolean         $deleted флаг означающий, что запись удаляется
	 * @return []
	 */
	protected function convertEntityToDB(EntityInterface $entity, $deleted = false)
	{
		return [
			'index_name'  => $this->config->getName(),
			'rel_id'      => $entity->id(),
			'update_time' => (new \DateTime())->format('Y-m-d H:i:s'),
			'deleted'     => $deleted ? 1 : 0,
		];
	}
}