<?php
namespace Motorway\SearchEngine\ORM;

interface EntityInterface
{
	/**
	 * Конструктор класса
	 * 
	 * @param Mapper $mapper
	 */
	public function __construct(Mapper $mapper);

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
	 * Вызывается перед добавлением записи, в этом методе можно прервать
	 * процедуру добавления
	 * 
	 * @return true
	 */
	protected function beforeInsert();

	/**
	 * Вызывается перед изменением записи, в этом методе можно прервать
	 * процедуру изменения
	 * 
	 * @return true
	 */
	protected function beforeUpdate();
	
	/**
	 * Вызывается перед сохранением (добавление и изменение) записи, 
	 * в этом методе можно прервать процедуру сохранения
	 * 
	 * @return bool
	 */
	protected function beforeSave();
	
	/**
	 * Вызывается перед удалением записи, в этом методе можно прервать
	 * процедуру удаления
	 * 
	 * @return bool
	 */
	protected function beforeDelete();

	/**
	 * Вызывается после добавления записи
	 * 
	 * @return bool
	 */
	protected function afterInsert();

	/**
	 * Вызывается после добавления записи
	 * 
	 * @return bool
	 */
	protected function afterUpdate();
	
	/**
	 * Вызывается после изменения записи
	 * 
	 * @return bool
	 */
	protected function afterSave();
	
	/**
	 * Вызывается после удаления записи
	 * 
	 * @return bool
	 */
	protected function afterDelete();
}