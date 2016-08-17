<?php
namespace Motorway\SearchEngine\DB;

use \Motorway\SearchEngine\DB\Connection;

class Schema
{
	/**
	 * Инстанс подключения к бд
	 * 
	 */
	protected $manager;

	protected $schemaClass = '\Doctrine\DBAL\Schema\Schema';

	/**
	 * Конструктор класса
	 * 
	 * @param \Motorway\SearchEngine\DB\Connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Производит синхронизацию схем таблиц в соответствии с переданными параметрами
	 * 
	 * @param  array
	 * @return результат выполнения последнего запроса
	 */
	public function process($parms)
	{
		$lastResult = null;
		foreach($parms as $tableName => $tableParms) {
			$lastResult = $this->processTable($tableName, $tableParms);
		}

		return $lastResult;
	}

	/**
	 * Производит синхронизацию схемы таблицы в соответствии с переданными параметрами
	 * 
	 * @param  string название таблицы
	 * @param  array  описание таблицы
	 * @return результат выполнения последнего запроса
	 */
	public function processTable($tableName, $tableParms)
	{
		$table = $this->getSchemaManager()->listTableDetails($tableName);

		$schema = $this->createSchema($table);
		$newSchema = $this->createMigrateSchema($tableName, $tableParms);
		
		$columns = $table->getColumns();
		$tableExists = !empty($columns);

		if ($tableExists) {
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
	 * Возвращает менеджер схемы
	 * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	public function getSchemaManager()
	{
		return $this->connection->getSchemaManager();
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
	protected function createMigrateSchema($tableName, $tableParms)
	{
		$schema = $this->createSchema();
		$table = $schema->createTable($tableName);

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