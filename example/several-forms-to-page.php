<?php
/**
 * Пример использования нескольких форм на странице
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
 * Подключаем файлы с классом
 *
 * Если вы используете автозагрузчик от composer,
 * укажите пространство имен в котором находится класс,
 * или подключите класс с помощью include или require
 */

//use ExampleNamespace\classes\SubsidiaryAjaxPars;
//use ExampleNamespace\classes\TwoSubsidiaryAjaxPars;

// или
//include_once 'classes/SubsidiaryAjaxPars.php';
//include_once 'classes/TwoSubsidiaryAjaxPars.php';

/** === ПЕРВАЯ ФОРМА === */

/**
 * Создаем  экземпляр класса, котрый будет работать.
 * Это может быть любой класс который наследует класс Indeximstudio\AjaxPars\AjaxPars
 *
 * Указываем идентификатор текущего парсинга
 */
$one_example = new SubsidiaryAjaxPars('OneExampleID');
/**
 * Параметры парсинга (все параметры описаны в классе)
 */
// Задержка между запросами
$one_example->delay = 1000;
// За одну итерацию выполнить программу 5 раз
$one_example->flow = 5;
/**
 * Заносим необходиме для дальнейшей работы данные в сессию
 */
$one_example->setParams();
/**
 * Формируем шаблон блока в котором будет форма с кнопкой старта и процесс работы
 *
 * В форму можно добавить дополнительные поля если нужно,
 * после отправки формы данные будут доступны через массив $_POST
 */
$one_form = '
    <form id="form' . $one_example->id . '" enctype="multipart/form-data" method="POST">
        <div class="form-group">
            <button type="submit" class="btn btn-success" id="start' . $one_example->id . '">Start (' . $one_example->id . ')</button>
        </div>
        <div class="form-group">' . $one_example->getProgress() . '</div>
    </form>
    ' . $one_example->getScript();

echo $one_form;

/**
 * ============
 * ВТОРАЯ ФОРМА
 * ============
 *
 * Отличается только идентификатором передаваемым при создании класса
 * и именем переменной для екземпляра
 */

/**
 * Создаем  экземпляр класса, котрый будет работать.
 * Это может быть любой класс который наследует класс Indeximstudio\AjaxPars\AjaxPars
 *
 * Указываем идентификатор текущего парсинга
 */
$two_example = new TwoSubsidiaryAjaxPars('TwoExampleID');
/**
 * Параметры парсинга (все параметры описаны в классе)
 */
// Задержка между запросами
$two_example->delay = 1000;
// За одну итерацию выполнить программу 5 раз
$two_example->flow = 5;
/**
 * Заносим необходиме для дальнейшей работы данные в сессию
 */
$two_example->setParams();
/**
 * Формируем шаблон блока в котором будет форма с кнопкой старта и процесс работы
 *
 * В форму можно добавить дополнительные поля если нужно,
 * после отправки формы данные будут доступны через массив $_POST
 */
$two_form = '
    <form id="form' . $two_example->id . '" enctype="multipart/form-data" method="POST">
        <div class="form-group">
            <button type="submit" class="btn btn-success" id="start' . $two_example->id . '">Start (' . $two_example->id . ')</button>
        </div>
        <div class="form-group">' . $two_example->getProgress() . '</div>
    </form>
    ' . $two_example->getScript();

echo $two_form;