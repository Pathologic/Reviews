<?php
$params = array_merge(array(
    'dir' => 'assets/snippets/reviews/DocLister/',
    'controller' => 'rating',
    'selectFields' => 'c.*,r.sorter,r.total,r.rating'
), $params);

return $modx->runSnippet('DocLister', $params);