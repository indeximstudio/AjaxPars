<?php

namespace Indeximstudio\AjaxPars_example;

use Indeximstudio\AjaxPars\AjaxPars;

if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}


class SubsidiaryAjaxPars extends AjaxPars
{

    function getCountIterations()
    {
        if (isset($_SESSION['parsing'][$this->id]['vsego']) and $_SESSION['parsing'][$this->id]['vsego'] > 0) {
            $vsego = $_SESSION['parsing'][$this->id]['vsego'];
        } else {
            /**
             * TODO получаем количество итераций - запросом в базу, количество строк в файле или можно жестко вписать
             */
            $vsego = 100;

            $_SESSION['parsing'][$this->id]['vsego'] = $vsego;
        }
        return $vsego;
    }

    /**
     * выполнение действий С полученными данными
     *
     * @return mixed
     */
    function getAction()
    {

        $text = 'Текущий элемент: ' . $_SESSION['parsing'][$this->id]['tekyshiy'] . '';

        return '<hr>' . $text;
    }
}