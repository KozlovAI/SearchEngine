<?php
namespace Motorway\SearchEngine\DB;

interface MapperInterface
{
	/**
	 * Конструктор класса
	 * 
	 * @param Connection $connection ссылка на подключение к БД
	 */
	public function __construct(Connection $connection);

	/**
	 * Вызывается после инициализации экземпляра
	 * 
	 * @return self
	 */
	public function setup();

	/**
	 * Устанавливает или возвращает имя таблицы
	 *
	 * @param  string $tableName
	 * @return mixed  если передан параметр, возвращается ссылка на самого себя, иначе название таблицы
	 */
	public function tableName($tableName = false);

	/**
	 * Устанавливает или возвращает название класса для сущностей
	 * 
	 * @param  string $entityName
	 * @return mixed  если передан параметр, возвращается ссылка на самого себя, иначе название класса
	 */
	public function entityName($entityName = false);

	/**
	 * Производит синхронизацию схемы таблицы с данными БД
	 * или возвращает ссылку на схему
	 * 
	 * @param  array  $schema описание таблицы
	 * @return mixed  если передан параметр, возвращается ссылка на самого себя, иначе ссылка на инстанс схемы
	 */
	public function schema(array $schema = array());

	/**
	 * Устанавливает или возвращает имена полей таблицы
	 * 
	 * @param  array  $columns набор полей
	 * @return mixed  если передан параметр, возвращается ссылка на самого себя, иначе массив имен полей
	 */
	public function columns(array $columns = array());

	/**
	 * Устанавливает или возвращает имя ключевого поля 
	 * 
	 * @param  string $keyName
	 * @return mixed  если передан параметр, возвращается ссылка на самого себя, иначе имя ключевого поля
	 */
	public function key($keyName = false);

	/**
	 * Создает инстанс сущности
	 * 
	 * @return \Motorway\SearchEngine\DB\EntityInterface
	 */
	public function entity()

	/**
	 * Создает инстанс сущности и сохраняет ее в БД
	 * 
	 * @param  array $parms значение полей сущности
	 * @return \Motorway\SearchEngine\DB\EntityInterface
	 */
	public function create(array $parms = array());

	/**
	 * Добавляет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function insert(EntityInterface $entity);

	/**
	 * Обновляет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function update(EntityInterface $entity);

	/**
	 * Сохраняет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function save(EntityInterface $entity);

	/**
	 * Удаляет данные сущности в БД
	 * 
	 * @param  EntityInterface $entity
	 * @return bool
	 */
	public function delete(EntityInterface $entity);
}