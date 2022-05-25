<?php
define('MODX_API_MODE', true);
include_once(__DIR__ . "/../../../index.php");
$modx->db->connect();
if (empty($modx->config)) {
    $modx->getSettings();
}
$modx->invokeEvent("OnWebPageInit");
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') || strpos($_SERVER['HTTP_REFERER'],
        $modx->config['site_url']) !== 0 || empty($_POST['formid']) || !is_scalar($_POST['formid'])) {
    $modx->sendErrorPage();
}
$formid = str_replace('.', '', $_POST['formid']);
$config = MODX_BASE_PATH . 'assets/snippets/reviews/forms/' . $formid . '.php';
if (file_exists($config)) {
    $params = require($config);
} else {
    $modx->sendErrorPage();
}
$snippet = 'ReviewForm';

echo $modx->runSnippet($snippet, $params);

exit;
