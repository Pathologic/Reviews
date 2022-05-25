<?php namespace Reviews\Rating;

/**
 * Class Model
 * @package PageViews
 */
class Model
{
    protected $modx = null;
    protected $table = 'reviews_rating';

    /**
     * PageViews constructor.
     * @param  \DocumentParser  $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->table = $modx->getFullTableName($this->table);
    }

    /**
     * @param  int  $resourceId
     * @return $this
     */
    public function add($resourceId, $rate)
    {
        $resourceId = (int) $resourceId;
        if ($resourceId) {
            $pos = [0, 0, 0.25, 0.5, 0.75, 1];
            $neg = [1, 1, 0.75, 0.5, 0.25, 0];
            $positive = str_replace(',', '.', $pos[$rate]);
            $negative = str_replace(',', '.', $neg[$rate]);
            $this->modx->db->query("INSERT INTO {$this->table} (`rid`, `positive`, `negative`, `total`) VALUES ({$resourceId}, {$positive}, {$negative}, 1) ON DUPLICATE KEY UPDATE `positive` = `positive` + {$positive}, `negative` = `negative` + {$negative}, `total` = `total` + 1");
            $this->calculateRating($resourceId);
        }

        return $this;
    }

    public function sub($resourceId, $rate)
    {
        $resourceId = (int) $resourceId;
        if ($resourceId) {
            $pos = [0, 0, 0.25, 0.5, 0.75, 1];
            $neg = [1, 1, 0.75, 0.5, 0.25, 0];
            $positive = str_replace(',', '.', $pos[$rate]);
            $negative = str_replace(',', '.', $neg[$rate]);
            $this->modx->db->query("UPDATE {$this->table} SET `positive` = `positive` - {$positive}, `negative` = `negative` - {$negative}, `total` = `total` - 1 WHERE `rid` = {$resourceId}");
            $this->calculateRating($resourceId);
        }

        return $this;
    }

    public function delete($id)
    {
        $rids = [];
        if (!empty($id)) {
            $q = $this->modx->db->query("SELECT `rid`,`rate` FROM {$this->modx->getFullTableName('reviews')} WHERE `active`=1 AND `id` IN ({$id})");
            while ($row = $this->modx->db->getRow($q)) {
                $rids[$row['rid']][] = $row['rate'];
            }

        }
        $pos = [0, 0, 0.25, 0.5, 0.75, 1];
        $neg = [1, 1, 0.75, 0.5, 0.25, 0];
        if (!empty($rids)) {
            foreach ($rids as $rid => $rates) {
                $positive = $negative = $total = 0;
                foreach ($rates as $rate) {
                    $positive += str_replace(',', '.', $pos[$rate]);
                    $negative += str_replace(',', '.', $neg[$rate]);
                    $total++;
                }
                $this->modx->db->query("UPDATE {$this->table} SET `positive` = `positive` - {$positive}, `negative` = `negative` - {$negative}, `total` = `total` - {$total} WHERE `rid` = {$rid}");
                $this->calculateRating($rid);
            }
        }
    }

    public function calculateRating($resourceId)
    {
        $resourceId = (int) $resourceId;
        $q = $this->modx->db->query("UPDATE {$this->table} SET `sorter` = ((`positive` + 1.9208) / (`positive` + `negative`) - 1.96 * SQRT((`positive` * `negative`) / (`positive` + `negative`) + 0.9604) / (`positive` + `negative`)) / (1 + 3.8416 / (`positive` + `negative`)), `rating` = (((`positive` / (`positive` + `negative`)) * 4) + 1) WHERE `rid`={$resourceId}");
    }

    /**
     * @param  int  $resourceId
     * @return array
     */
    public function get($resourceId)
    {
        $out = 0;
        $resourceId = (int) $resourceId;
        if ($resourceId) {
            $q = $this->modx->db->query("SELECT COALESCE(`rating`,0) FROM {$this->table} WHERE `rid`={$resourceId}");
            $out = $this->modx->db->getValue($q);
        }

        return $out;
    }

    public function createTable()
    {
        $q = "CREATE TABLE IF NOT EXISTS {$this->table} (
            `rid` INT(10) NOT NULL UNIQUE,
            `positive` FLOAT NOT NULL DEFAULT 0,
            `negative` FLOAT NOT NULL DEFAULT 0,
            `sorter` FLOAT NOT NULL DEFAULT 0,
            `rating` FLOAT NOT NULL DEFAULT 0,
            `total` INT(10) NOT NULL DEFAULT 0,
            KEY `sorter` (`sorter`),
            KEY `rating` (`rating`),
            KEY `total` (`total`)
            ) Engine=InnoDB";
        $this->modx->db->query($q);
    }
}
