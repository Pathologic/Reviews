<?php
include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/core/controller/onetable.php');

/**
 * Class pageviewsDocLister
 */
class reviewsDocLister extends onetableDocLister
{
    public function __construct($modx, $cfg = [], $startTime = null)
    {
        parent::__construct($modx, $cfg, $startTime);
        $this->setFiltersJoin("LEFT JOIN {$this->modx->getFullTableName('site_content')} `sc` ON `c`.`rid`=`sc`.`id`");
    }

    public function getDocs($tvlist = '') {
        $docs = parent::getDocs($tvlist);
        foreach ($docs as &$doc) {
            $doc['rate'] = (int)$doc['rate'];
            $doc['review'] = trim(strip_tags($doc['review']));
            if (mb_strlen($doc['review']) > 80) {
                $doc['review'] = mb_substr($doc['review'], 0, 80) . '...';
            }
        }
        $this->_docs = $docs;

        return $docs;
    }
}
