<?php
namespace Motorway\SearchEngine\ORM\Sphinx\DB;

use Motorway\SearchEngine\DB\ORM\Mapper as DBMapper;

class Changes extends DBMapper
{
	public function setup()
	{
		// вспомогательная таблица
		new Counters($this->connection);

		return parent::setup()
			->tableName('se_sphinx_updates')
			->schema([
				'columns' => [
					'index'       => ['type' => 'string'],
					'rel_id'      => ['type' => 'integer'],
					'update_time' => ['type' => 'datetime'],
					'deleted'     => ['type' => 'integer', 'default' => 0],
				],

				'indexes' => [
					['type' => 'primary', 'columns' => ['index', 'rel_id']]
				],
			]);
	}
}