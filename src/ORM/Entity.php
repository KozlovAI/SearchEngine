<?php
namespace Motorway\SearchEngine\ORM;

class Entity implements EntityInterface
{
	protected $mapper;

	protected $attrs;

	/**
	 * Конструктор класса
	 * 
	 * @param MapperInterface $mapper ссылка на мапер
	 */
	public function __construct(MapperInterface $mapper)
	{
		$this->mapper = $mapper;
		$this->setup();
	}

	/**
	 * Вызывется после создания инстанса
	 * можно задать значение полей по умолчанию
	 * 
	 * @return self
	 */
	protected function setup()
	{
		return $this;
	}

	/**
	 * Возвращает значение первичного ключа
	 * 
	 * @return mixed
	 */
	public function id()
	{
		return $this->{$this->mapper->key()};
	}

	/**
	 * Устанавливает значение полей из массива
	 * 
	 * @param  array $parms
	 * @return self
	 */
	public function assign(array $parms)
	{
		$this->attrs = $parms;

		return $this;
	}

	/**
	 * Проверяет есть ли такое св-во
	 * 
	 * @param  string  $prop имя св-ва
	 * @return bool
	 */
	public function __isset($prop)
	{
		return in_array($prop, $this->mapper->columns());
	}

	/**
	 * Возвращает значение св-ва
	 *
	 * Можно перехватить возврат значения создав метод с именем 
	 * вида ```get_PROP_NAME```, где ```PROP_NAME``` имя св-ва.
	 * 
	 * @param  string $prop
	 * @return mixed
	 */
	public function __get($prop)
	{
		if (!$this->__isset($prop)) {
			throw new \LogicException("Property {$prop} is not exist");
		}

		$method = 'get_'. $prop;
		if (method_exists($this, $method)) {
			return $this->$method();
		}

		return $this->attrs[$prop];
	}

	/**
	 * Устанавливает значение св-ва
	 *
	 * Можно перехватить задание значения создав метод с именем 
	 * вида ```get_PROP_NAME```, где ```PROP_NAME``` имя св-ва.
	 * 
	 * @param string $prop
	 * @param mixed $value
	 */
	public function __set($prop, $value)
	{
		if (!$this->__isset($prop)) {
			throw new \LogicException("Property {$prop} is not exist");
		}

		$method = 'set_'. $prop;
		if (method_exists($this, $method)) {
			return $this->$method($value);
		}

		return $this->attrs[$prop];
	}

	public function __unset($prop)
	{
		if (!$this->__isset($prop)) {
			throw new \LogicException("Property {$prop} is not exist");
		}

		throw new \LogicException("Property {$prop} can not be removed");
	}

	/**
	 * Добавляет запись
	 * 
	 * @return bool
	 */
	public function insert()
	{
		return $this->mapper->insert($this);
	}

	/**
	 * Изменяет запись
	 * 
	 * @return bool
	 */
	public function update()
	{
		return $this->mapper->update($this);
	}

	/**
	 * Сохраняет (добавляет или изменяет) запись
	 * 
	 * @return bool
	 */
	public function save()
	{
		return $this->mapper->save($this);
	}

	/**
	 * Удаляет запись
	 * 
	 * @return bool
	 */
	public function delete()
	{
		return $this->mapper->delete($this);
	}

	public function beforeInsert()
	{
		return true;
	}

	public function beforeUpdate()
	{
		return true;
	}

	public function beforeSave()
	{
		return true;
	}

	public function afterInsert()
	{
		return true;
	}

	public function afterUpdate()
	{
		return true;
	}

	public function afterSave()
	{
		return true;
	}

	public function afterDelete()
	{
		return true;
	}
}