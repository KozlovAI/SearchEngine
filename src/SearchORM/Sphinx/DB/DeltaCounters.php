<?php
namespace Motorway\SearchEngine\ORM\Sphinx\DB;

use Motorway\SearchEngine\DB\ORM\Mapper as DBMapper;

class Counters extends DBMapper
{
	public function setup()
	{
		return parent::setup()
			->tableName('se_sphinx_counters')
			->schema([
				'columns' => [
					'index'       => ['type' => 'string'],
					'update_time' => ['type' => 'datetime'],
				],

				'indexes' => [
					['type' => 'primary', 'columns' => ['index']]
				],
			]);
	}

	public function key()
	{
		return 'index';
	}
}