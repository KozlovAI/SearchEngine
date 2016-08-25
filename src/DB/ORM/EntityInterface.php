<?php
namespace Motorway\SearchEngine\DB\ORM;

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

	/**
	 * Вызывается перед добавлением записи
	 * 
	 * @return bool
	 */
	public function beforeInsert();

	/**
	 * Вызывается перед изменением записи
	 * 
	 * @return bool
	 */
	public function beforeUpdate();

	/**
	 * Вызывается перед сохранением (добавление/изменение) записи
	 * 
	 * @return bool
	 */
	public function beforeSave();

	/**
	 * Вызывается перед удалением записи
	 * 
	 * @return bool
	 */
	public function beforeDelete();

	/**
	 * Вызывается после добавления записи
	 * 
	 * @return bool
	 */
	public function afterInsert();

	/**
	 * Вызывается после изменения записи
	 * 
	 * @return bool
	 */
	public function afterUpdate();

	/**
	 * Вызывается после сохранения (добавление/изменение) записи
	 * 
	 * @return bool
	 */
	public function afterSave();

	/**
	 * Вызывается после записи
	 * 
	 * @return bool
	 */
	public function afterDelete();
}