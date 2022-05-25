<?php namespace Reviews;
include_once MODX_BASE_PATH . 'assets/lib/MODxAPI/autoTable.abstract.php';
include_once MODX_BASE_PATH . 'assets/snippets/reviews/model/rating.php';
/**
 * Class Model
 * @package Reviews
 */
class Model extends \autoTable {
    protected $table = 'reviews';
    protected $rating = null;
    protected $default_field = array(
        'name' => '',
        'email' => '',
        'image' => '',
        'rid' => 0,
        'createdon' => '',
        'updatedon' => '',
        'review' => '',
        'active' => 0,
        'rate' => 0,
    );

    public function __construct($modx, $debug = false)
    {
        parent::__construct($modx, $debug);
        $this->rating = new Rating\Model($modx);
    }

    public function save($fire_events = null, $clearCache = false)
    {
        if (!$rid = $this->get('rid')) return false;

        if ($this->newDoc) {
            $this->touch('createdon');
            $this->touch('updatedon');
        } else {
            $this->touch('updatedon');
        }
        $out = parent::save($fire_events, $clearCache);
        if (!$this->newDoc && $out && $this->isChanged('active')) {
            if ($this->get('active')) {
                $this->rating->add($rid, $this->get('rate'));
            } else {
                $this->rating->sub($rid, $this->get('rate'));
            }
        };
        
        return $out;
    }

    /**
     * @param $ids
     * @param null $fire_events
     * @return $this
     * @throws Exception
     */
    public function delete($ids, $fire_events = null)
    {
        $_ids = $this->cleanIDs($ids, ',');
        if (is_array($_ids) && $_ids != array()) {
            $id = $this->sanitarIn($_ids);
            $this->rating->delete($id);
        }

        return parent::delete($ids, $fire_events);
    }

    /**
     * @return false|string
     */
    public function touch($field = '')
    {
        if (empty($this->get($field))) {
            $this->set($field, date('Y-m-d H:i:s', time() + $this->modx->config['server_offset_time']));
        }
        
        return $this;
    }

    public function createTable() 
    {
        $q = "CREATE TABLE IF NOT EXISTS {$this->makeTable($this->table)} (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `rid` INT(10) NOT NULL DEFAULT 0,
            `name` VARCHAR(50) NOT NULL,
            `email` VARCHAR(50) NOT NULL,
            `review` TEXT NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            `rate` INT(10) NOT NULL DEFAULT 0,
            `active` INT(1) NOT NULL DEFAULT 0,
            `createdon` datetime NOT NULL,
            `updatedon` datetime NOT NULL,
            PRIMARY KEY  (`id`),
            KEY `rid` (`rid`),
            KEY `createdon` (`createdon`),
            KEY `rate` (`rate`)
            ) Engine=InnoDB
            ";
        $this->modx->db->query($q);
        $this->rating->createTable();
    }
}
