<?php
namespace Motorway\SearchEngine\DB;

use \Motorway\SearchEngine\DB\Connection;

class Schema
{
	/**
	 * Инстанс подключения к бд
	 */
	protected $connection;

	/**
	 * Имя таблицы для работы
	 * @var [type]
	 */
	protected $tableName;

	protected $schemaClass = '\Doctrine\DBAL\Schema\Schema';

	/**
	 * Конструктор класса
	 * 
	 * @param \Motorway\SearchEngine\DB\Connection
	 */
	public function __construct(Connection $connection, $tableName)
	{
		$this->connection = $connection;
		$this->tableName = $tableName;
	}

	/**
	 * Возвращает менеджер схемы
	 * 
	 * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	public function getSchemaManager()
	{
		return $this->connection->getSchemaManager();
	}

	public function getColumns()
	{
		return $this->getSchemaManager()->listTableColumns($this->tableName);
	}

	public function getIndexes()
	{
		return $this->getSchemaManager()->listTableIndexes($this->tableName);
	}

	public function isExist()
	{
		return $this->getSchemaManager()->tablesExist(array($this->tableName));
	}

	/**
	 * Производит синхронизацию схемы таблицы в соответствии с переданными параметрами
	 * 
	 * @param  array  описание таблицы
	 * @return результат выполнения последнего запроса
	 */
	public function process($tableParms)
	{
		$table = $this->getSchemaManager()->listTableDetails($this->tableName);

		$schema = $this->createSchema($table);
		$newSchema = $this->createMigrateSchema($tableParms);

		if ($this->isExist()) {
			$queries = $schema->getMigrateToSql($newSchema, $this->connection->getDatabasePlatform());
		} else {
			$queries = $newSchema->toSql($this->connection->getDatabasePlatform());
		}

		$lastResult = null;
		foreach($queries as $query) {
			$lastResult = $this->connection->exec($query);
		}

		return $lastResult;
	}

	/**
	 * Производит синхронизацию схемы таблицы в соответствии с переданными параметрами
	 * и сохранением в кеше
	 * 
	 * @param  array $tableParms описание таблицы
	 * @return bool
	 */
	public function processCached($tableParms)
	{
		return $this->process($tableParms);
	}

	/**
	 * Возвращает инстанс схемы для таблицы
	 * 
	 * @param  string|null Имя таблицы
	 * @return \Doctrine\DBAL\Schema\Schema
	 */
	protected function createSchema($table = null)
	{
		if (is_null($table)) {
			return new \Doctrine\DBAL\Schema\Schema(); 
		}

		return new \Doctrine\DBAL\Schema\Schema([$table]);
	}

	/**
	 * Возвращает инстанс схемы для таблицы миграции
	 * 
	 * @param  string Имя таблицы
	 * @param  array  Описание схеы таблицы
	 * @return \Doctrine\DBAL\Schema\Schema
	 */
	protected function createMigrateSchema($tableParms)
	{
		$schema = $this->createSchema();
		$table = $schema->createTable($this->tableName);

		foreach($tableParms['columns'] as $columnName => $columnParms) {
			$columnType = $columnParms['type'];
			unset($columnParms['type']);
			$table->addColumn($columnName, $columnType, $columnParms);
		}

		if (isset($tableParms['indexes'])) {
			$primaryKeys = array();
			foreach($tableParms['indexes'] as $indexName => $indexParms) {
				if ($indexParms['type'] == 'primary') {
					$column = isset($indexParms['columns']) ? $indexParms['columns'] : [$indexName];
					$primaryKeys = array_merge($primaryKeys, $column);
					continue;
				}

				$indexName = isset($indexParms['name']) ? $indexParms['name'] : $indexName;
				if (is_numeric($indexName)) {
					$indexName = $tableName . implode('_', $indexParms['columns']) .'_idx';
				}

				if ($indexParms['type'] == 'unique') {
					$table->addUniqueIndex($indexParms['columns'], $indexName);
				} else {
					$table->addIndex($indexParms['columns'], $indexName);
				}
			}

			if ($primaryKeys) {
				$table->setPrimaryKey($primaryKeys);
			}
		}

		return $schema;
	}
}