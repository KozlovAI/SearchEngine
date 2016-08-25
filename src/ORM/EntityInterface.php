<?php
namespace Motorway\SearchEngine\ORM;

interface EntityInterface
{
	/**
	 * Конструктор класса
	 * 
	 * @param Mapper $mapper
	 */
	public function __construct(MapperInterface $mapper);

	/**
	 * Заполняет поля сущности из массива
	 * 
	 * @param  array  $parms
	 * @return self
	 */
	public function assign(array $parms);

	/**
	 * Добавление записи
	 * 
	 * @return bool
	 */
	public function insert();

	/**
	 * Изменение записи
	 * 
	 * @return bool
	 */
	public function update();

	/**
	 * Добавление или изменение записи
	 * 
	 * @return bool
	 */
	public function save();

	/**
	 * Удаление записи
	 * 
	 * @return bool
	 */
	public function delete();

	public function beforeInsert();

	public function beforeUpdate();

	public function beforeSave();

	public function afterInsert();

	public function afterUpdate();

	public function afterSave();

	public function afterDelete();
}