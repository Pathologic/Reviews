<?php
define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

include_once(__DIR__."/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if(!isset($_SESSION['mgrValidated'])){
    die();
}
$modx->invokeEvent('OnManagerPageInit');

$mode = (isset($_REQUEST['mode']) && is_scalar($_REQUEST['mode'])) ? $_REQUEST['mode'] : null;
include_once('lib/controller.php');

$controller = new \Reviews\ModuleController($modx);
if (!empty($mode) && method_exists($controller, $mode)) {
    $out = call_user_func_array(array($controller, $mode), []);
}else{
    $out = call_user_func_array(array($controller, 'listing'), []);
}

echo is_array($out) ? json_encode($out) : $out;
