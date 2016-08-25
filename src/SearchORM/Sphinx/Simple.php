<?php
namespace Motorway\SearchEngine\SearchORM\Sphinx;

use \Motorway\SearchEngine\Config\ConfigInterface;
use \Motorway\SearchEngine\DB\Connection;
use \Motorway\SearchEngine\DB\ORM\Mapper as ReaderMapper;

use \Motorway\SearchEngine\SearchORM\MapperInterface;
use \Motorway\SearchEngine\SearchORM\EntityInterface;
use \Motorway\SearchEngine\SearchORM\Entity;

class Simple implements MapperInterface
{
	protected $config;

	protected $reader;

	public function __construct(ConfigInterface $config)
	{
		$this->config = $config;
	}

	protected function reader()
	{
		if ($this->reader) {
			return $this->reader;
		}

		$listen = $this->config->get('listen');
		if (!$listen || !isset($listen['mysql'])) {
			throw new \LogicException('not mysql listen');
		}

		$dsn = 'mysql://'. $listen['mysql'];
		
		$this->reader = new ReaderMapper(new Connection($dsn));
		$this->reader
				->tableName($this->config->getName());
				->key('id')
				->entityName('ss');


		return $this->reader;
	}
}