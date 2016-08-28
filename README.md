# SearchEngine
Модуль предоставляет единый интерфейс для работы с разлными поисковыми машинами из под PHP.
На данный момент реализован только интерфейс для доступа к поисковой машине Sphinx.

## Установка
Установка производится через composer

``` bash
$ composer require motorway/searchengine
```

## Использование
### Инициализация
``` php
// инициализация с конфигом по указанному пути
$usersSearch = \Motorway\SearchEngine\Index::getInstance('users', '/path/to/users-config.php');

// Если вторым параметром не передать путь до файла конфигурации он (файл конфига) будет искаться по дефолтному пути
// будет загружен конфиг по адресу /vendor/motorway/searchengine/config/users.php
$usersSearch = \Motorway\SearchEngine\Index::getInstance('users');

// Для переопределения дефолтного пути конфигов используйте
\Motorway\SearchEngine\Index::configSavePath('/new/path/to/configs/');
// будет загружен конфиг по адресу /new/path/to/configs/users.php
$usersSearch = \Motorway\SearchEngine\Index::getInstance('users');
```

### ORM
Для работы с поисковым индексом определен единый интерфейс ORM, который позволяет добавлять/изменять/удалять данный из индекса. 
Пользовательский код необходимо будет доработать таким образом, чтобы в момент изменения данных были вызваны соответсвующие методы orm

``` php
// например добавление/изменение данных
function createOrUpdateUser($data)
{
	// ... save user data code

	$entity = \Motorway\SearchEngine\Index::getInstance('users')->orm()->entity();
	$entity->id = $userId;
	$entity->fieldOne = 'aaa';
	$entity->fieldTwo = 'aaa';
	$entity->save();
}

function deleteUser($userId)
{
	// ... delete user code
	
	$entity = \Motorway\SearchEngine\Index::getInstance('users')->orm()->entity();
	$entity->id = $userId;
	$entity->delete();
}

```