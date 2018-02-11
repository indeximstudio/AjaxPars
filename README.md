AjaxPars.php
========

Класс для создания переборов любых данных посредством ajax c лёгкой реализацией.


## Установка

Via Composer

``` bash
$ composer require indeximstudio/ajaxpars
```
Подключаем загрузчик
```
include 'vendor/autoload.php';
```  
Добавлаем пространство имен
```
use Indeximstudio\AjaxPars;
```


## Параметры  
#### $id string (обязательный)
Уникальный идентификатор парсинга. Нужен для возможности запускать несколько парсингов  
#### $delay integer (по умолчанию 1000)
Задержка между ajax запросами, мс  
#### $ajaxPath string (по умолчанию пусто)
Путь к файлу обработчику формы  
#### $data array
Массив для передачи данных для работы в момет создания екземпляра класса  
#### $flow integer
Потоки (сколько раз выполнить метод getAction за одну итерацию)  
#### $start boolean
  
#### $countIterations integer
Сколько раз должен повториться скрипт  
#### $debug integer
Режим отладки (вывод информации в консоль)  
0 - отключен, 1 - краткая информация, 2 - полная информация  
## Использование

### Методы, которые доступны при создании екземпляра
#### getScript() @return string
формирует javascript с ajax запросом и подключает его к странице

#### getProgress() @return string
вывод полосы прогресса

#### setParams() (ранее StartPars())
Заносит все параметры в сессию

### Методы, доступны внутри дочернего класса  

#### StopPars()
Останавливает работу. Используется, если нужно преждевременно остановить работу программы

#### replaceReverseSlashes(string $str) @return string
Заменяет обратные слеши на обычные. Они не нравятся JavaScript (если нужно что нибудь вывести в консоль)

#### setSessionParam( string $key, mixed $value)
Заносит данные в сессию с ключем текущей программы

#### getSessionParam( string $key) @return mixed
Возвращает данные из сессии текущего парсинга по ключу

### Абстрактные методы

#### getCountIterations() @return integer
Для получения количества итераций

#### getAction() @return mixed
Выполнение действий c полученными данными. Метод, в котором происходят все манипуляции.

#### communicationWithParent()
Системный метод для связи файла дочернего класса с родителем. В нем нужно вызвать метод transferThisFileInfo() и передать в него константы `__CLASS__` и `__FILE__`

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE) for more information.
