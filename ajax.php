<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);

global $modx;
header("Content-type: text/html, charset=utf-8;");
define('MODX_API_MODE', true);

require_once $_GET['modx_manager_path'];
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}

$session = $_SESSION['parsing'][$_GET['id']];
include_once 'AjaxPars.php';
include_once $session['classPath'];

$pars = new $session['className']($_GET['id']);
echo $pars->getValueJson();