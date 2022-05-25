<?php
$params = array_merge([
    'controller'   => 'onetable',
    'table'        => 'reviews',
    'parentField'  => 'rid',
    'showParent'   => -1,
    'controller'   => 'onetable',
    'addWhereList' => 'c.active = 1',
    'dateSource'   => 'createdon',
    'dateFormat'   => 'd.m.Y'
], $params);

include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');

$_prepare = explode(",", $prepare ?? '');
$prepare = [];
$prepare[] = \APIhelpers::getkey($modx->event->params, 'BeforePrepare', '');
$prepare = array_merge($prepare, $_prepare);
$prepare[] = 'ReviewLister::prepare';
$prepare[] = \APIhelpers::getkey($modx->event->params, 'AfterPrepare', '');
$params['prepare'] = trim(implode(",", $prepare), ',');

if (!class_exists("ReviewLister", false)) {
    class ReviewLister
    {
        public static function prepare(array $data = [], DocumentParser $modx, $_DL, prepare_DL_Extender $_extDocLister)
        {
            $data['rate'] = $data['rating'] = (int) $data['rate'];
            $data['relrating'] = number_format($data['rate'] / 5 * 100, 2, '.', '');
            $date = $_DL->getCFGDef('dateSource', 'createdon');
            if (isset($data[$date])) {
                $dateFormat = $_DL->getCFGDef('dateFormat', 'd.m.Y H:i');
                if ($dateFormat) {
                    $data['date'] = date($dateFormat, strtotime($data[$date]));
                }
            }
            if (!isset($data['date']) && isset($data[$date])) {
                $date = strtotime($data[$date]);
                $month = date('m', $date) - 1;
                $months = [
                    'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября',
                    'ноября', 'декабря'
                ];
                $data['date'] = date('d', $date) . ' ' . $months[$month] . ' ' . date('Y', $date);
            }

            return $data;
        }
    }
}
$doc = (int)($params['id'] ?? $modx->documentIdentifier);
$q = $modx->db->query("SELECT * FROM {$modx->getFullTableName('reviews_rating')} WHERE `rid` = {$doc}");
$row = $modx->db->getRow($q);
if ($row) {
    $modx->setPlaceholder('reviews.total', (int) $row['total']);
    $modx->setPlaceholder('reviews.rating', number_format($row['rating'], 2, '.', ''));
    $modx->setPlaceholder('reviews.relrating', number_format($row['rating'] / 5 * 100, 2,
    '.', ''));
}

return $modx->runSnippet('DocLister', $params);
