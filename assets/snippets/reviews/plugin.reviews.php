<?php
if (!IN_MANAGER_MODE) die();
if (empty($ids)) return;
$where = implode(',', $ids);
$reviewsTable = $modx->getFullTableName('reviews');
$ratingTable = $modx->getFullTableName('reviews_rating');
$modx->db->delete($reviewsTable, "`rid` IN ($where)");
$modx->db->delete($ratingTable, "`rid` IN ($where)");
$modx->db->query("ALTER TABLE {$reviewsTable} AUTO_INCREMENT = 1");
