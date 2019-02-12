<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
define('MODX_API_MODE', true);
include_once(dirname(__FILE__) . "/index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
$modx->invokeEvent("OnWebPageInit");

$session = $_SESSION['parsing'][$_GET['id']];
include_once 'AjaxPars.php';
include_once $session['classPath'];

$pars = new $session['className']($_GET['id']);
echo $pars->getValueJson();