<?php
namespace Motorway\SearchEngine\SearchORM;

class Entity implements EntityInterface
{
	protected $mapper;

	protected $attrs = array();

	/**
	 * Конструктор класса
	 * 
	 * @param \Motorway\SearchEngine\SearchORM\MapperInterface $mapper ссылка на мапер
	 */
	public function __construct(MapperInterface $mapper)
	{
		$this->mapper = $mapper;
		$this->setup();
	}

	/**
	 * Возвращает значение первичного ключа
	 * В случае если ключ составной возвразается строка разделенная :
	 * 
	 * @return mixed
	 */
	public function id()
	{
		$key = $this->mapper->key();
		
		if (is_array($key)) {
			$keys = array();
			foreach($key as $v) {
				$keys[] = $this->$v;
			}

			return implode(':', $keys);
		}

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
		foreach($parms as $parm => $value) {
			if ($this->__isset($parm)) {
				$this->__set($parm, $value);
			}
		}

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

		return isset($this->attrs[$prop]) ? $this->attrs[$prop] : null;
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
			$this->$method($value);
			return;
		}

		$this->attrs[$prop] = $value;
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

	/**
	 * Вызывается перед добавлением записи
	 * 
	 * @return bool
	 */
	public function beforeInsert()
	{
		return true;
	}

	/**
	 * Вызывается перед изменением записи
	 * 
	 * @return bool
	 */
	public function beforeUpdate()
	{
		return true;
	}

	/**
	 * Вызывается перед сохранением записи
	 * 
	 * @return bool
	 */
	public function beforeSave()
	{
		return true;
	}

	/**
	 * Вызывается перед удалением записи
	 * 
	 * @return bool
	 */
	public function beforeDelete()
	{
		return true;
	}

	/**
	 * Вызывается после добавления записи
	 * 
	 * @return bool
	 */
	public function afterInsert()
	{
		return true;
	}

	/**
	 * Вызывается после изменения записи
	 * 
	 * @return bool
	 */
	public function afterUpdate()
	{
		return true;
	}

	/**
	 * Вызывается после сохранения записи
	 * 
	 * @return bool
	 */
	public function afterSave()
	{
		return true;
	}

	/**
	 * Вызывается после удаления записи
	 * 
	 * @return bool
	 */
	public function afterDelete()
	{
		return true;
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
}