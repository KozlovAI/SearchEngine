<?php
namespace Motorway\SearchEngine\SearchORM\Sphinx;

use \Motorway\SearchEngine\SearchORM\AbstractMapper;
use \Motorway\SearchEngine\SearchORM\EntityInterface;
use Utils\DB\Connection;

class Simple extends AbstractMapper
{
	protected $reader;

	/**
	 * @return string
	 */
	public function key()
	{
		return 'id';
	}

	/**
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function doInsert(EntityInterface $entity)
	{
		return true;
	}

	/**
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function doUpdate(EntityInterface $entity)
	{
		return true;
	}

	/**
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	protected function doDelete(EntityInterface $entity)
	{
		return true;
	}

	/**
	 * Возвращает инстанс для чтения индекса
	 * 
	 * @return \Motorway\SearchEngine\DB\Connection
	 */
	protected function reader()
	{
		if ($this->reader) {
			return $this->reader;
		}

		$listen = $this->config->get('listen');
		if (!$listen || !isset($listen['mysql'])) {
			throw new \LogicException('not sphinx mysql listen');
		}

		$dsn = 'mysql://'. $listen['mysql'];

		return $this->reader = new Connection($dsn);
	}
}