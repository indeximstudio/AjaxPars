<?php
namespace Indeximstudio\AjaxPars\example\classes;

use Indeximstudio\AjaxPars;

if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}

/**
 * Class SubsidiaryAjaxPars1
 * Этот класс будет выполняться при каждой итерации программы
 */
class TwoSubsidiaryAjaxPars extends AjaxPars\AjaxPars
{
    /**
     * НЕ ИЗМЕНЯТЬ!!!
     * Передает имя текущего класса и путь к файлу в родительский класс
     */
    protected function communicationWithParent()
    {
        $this->transferThisFileInfo(__CLASS__, __FILE__);
    }

    /**
     * Считает количество итераций
     *
     * Из базы - делаем запрос на количество
     * Из файла - получаем количество строк
     * Или же просто жестко вписать количество
     * @return int
     */
    protected function getCountIterations()
    {
        $vsego = 300;

        /*- ПОЛУЧЕНИЕ КОЛИЧЕСТВА -*/

        return (int) $vsego;
    }

    /**
     * выполнение действий С полученными данными
     *
     * @return mixed
     */
    function getAction()
    {
        if ($this->getSessionParam('tekyshiy') > 20) {
            $this->StopPars();
        }
        $_SESSION['parsing'][$this->id]['ssd']=3443;
        $text = 'Текущий элемент: ' . $this->getSessionParam('current') . '';

        return '<hr>' . $text;
    }
}