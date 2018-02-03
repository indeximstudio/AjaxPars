<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 03.02.2018
 * Time: 21:50
 */

namespace Indeximstudio\AjaxPars_example;

include_once __DIR__ . '/vendor/autoload.php';

$Progress = '';
$Script = '';
if (isset($_POST['go'])) {
    $data = array();
    $pars = new subsidiaryAjaxPars('SubsidiaryAjaxPars', '200', __DIR__ . '/ajax.php', $data);
    $pars->StartPars();
    $Progress = $pars->getProgress();
    $Script = $pars->getScript();
}

$content = '<div class="col-md-6">
            <form enctype="multipart/form-data" action="" method="POST">
                <button type="submit" class="btn btn-success" id="go" name="go">GO</button>
            </form>
        </div>' . $Progress . ' ' . $Script;


echo $content;