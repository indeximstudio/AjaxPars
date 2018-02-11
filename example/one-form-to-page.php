<?php
/**
 * Пример использования одной формы на странице
 * Форма и все ее елементы, используют стили bootstrap 3
 * Если нужно чтоб прогресс отображался красиво,
 * подключите стили bootstrap
 */

/**
 * Подключение стилей (для примера отображения формы и полосы прогреса)
 */
//echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';

/**
 * Подключение автозагрузчика
 */
include_once __DIR__ . '/vendor/autoload.php';

/**
 * Подключаем файл с классом
 *
 * Если вы используете автозагрузчик от composer,
 * укажите пространство имен в котором находится класс,
 * или подключите класс с помощью include или require
 */

//use ExampleNamespace\classes\SubsidiaryAjaxPars;

// или
//include_once 'classes/SubsidiaryAjaxPars.php';

/**
 * Создаем экземпляр класса, котрый будет работать.
 * Это может быть любой класс который наследует класс Indeximstudio\AjaxPars\AjaxPars
 *
 * Указываем идентификатор текущего парсинга
 */
$one_form = new SubsidiaryAjaxPars('OneExampleID');
/**
 * Параметры парсинга (все параметры описаны в классе)
 */
// Задержка между запросами
$one_form->delay = 1000;
// За одну итерацию выполнить программу 5 раз
$one_form->flow = 5;
/**
 * Заносим необходиме для дальнейшей работы данные в сессию
 */
$one_form->setParams();
/**
 * Формируем шаблон блока в котором будет форма с кнопкой старта и процесс работы
 *
 * В форму можно добавить дополнительные поля если нужно,
 * после отправки формы данные будут доступны через массив $_POST
 */
$content = '
    <form id="form' . $one_form->id . '" enctype="multipart/form-data" method="POST">
        <div class="form-group">
            <button type="submit" class="btn btn-success" id="start' . $one_form->id . '">Start (' . $one_form->id . ')</button>
        </div>
        <div class="form-group">' . $one_form->getProgress() . '</div>
    </form>
    ' . $one_form->getScript();

echo $content;