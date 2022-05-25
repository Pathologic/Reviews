<?php
include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/core/controller/site_content.php');

/**
 * Class pageviewsDocLister
 */
class ratingDocLister extends site_contentDocLister
{
    public function __construct($modx, $cfg = array(), $startTime = null) {
        parent::__construct($modx, $cfg, $startTime);
        $this->setFiltersJoin("LEFT JOIN {$this->getTable('reviews_rating','r')} ON `r`.`rid`=`c`.`id`");
    }

    public function getDocs($tvlist = '') {
        $docs = parent::getDocs($tvlist);
        foreach ($docs as &$doc) {
            $rating = $doc['rating'];
            $doc['rating'] = number_format($rating,2, '.', '');
            $doc['relrating'] = number_format($rating / 5 * 100, 2, '.', '');
            $doc['total'] = (int)$doc['total'];
            $doc['sorter'] = $doc['sorter'] ?: 0;
        }
        $this->_docs = $docs;
        
        return $docs;
    }
}
