<?php

namespace Indeximstudio\AjaxPars_example;

global $modx;
header("Content-type: text/html, charset=utf-8;");
define('MODX_API_MODE', true);

/**
 * TODO указать путь к корневому файлу index.php EVO CMS
 */
include_once(dirname(__FILE__) . "/../../../../../../index.php");
$modx->db->connect();
if (empty($modx->config)) {
    $modx->getSettings();
}



include_once __DIR__ . '/vendor/autoload.php';


$pars = new AjaxPars('аjaxPars', '', '', array(), 1);
echo $pars->getValueJson();