<?php namespace FormLister;
include_once(MODX_BASE_PATH . 'assets/snippets/reviews/model/reviews.php');
/**
 * Class Form
 * @package FormLister
 */
class Reviews extends Form
{
    protected $reviews = null;

    /**
     * Form constructor.
     * @param \DocumentParser $modx
     * @param array $cfg
     */
    public function __construct(\DocumentParser $modx, array $cfg = array())
    {
        parent::__construct($modx, $cfg);
        $this->reviews = new \Reviews\Model($modx);
        $this->lexicon->fromFile('reviews', '', 'assets/snippets/reviews/FormLister/lang/');
    }

    public function process()
    {
        $id = $this->getCFGDef('id', 0);
        if ($id) {
            if ($this->reviews->edit($id)->getID()) {
                $this->reviews->fromArray($this->getFormData('fields'));
                if (!$this->reviews->save()) {
                    $this->addMessage($this->translate('form.error'));
                } else {
                    $this->setFormStatus(true);
                }
            } else {
                $this->addMessage($this->translate('form.error'));
            }
        } else {
            $this->reviews->create($this->getFormData('fields'));
            $this->reviews->set('active', (int)!$this->getCFGDef('moderation', 1));
            if ($this->reviews->save()) {
                $doc = new \modResource($this->modx);
                $this->setFields($doc->edit($this->getField('rid'))->toArray(), 'page');
                parent::process();
            } else {
                $this->addMessage($this->translate('form.error'));
            }
        }
    }
}

