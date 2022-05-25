<?php namespace Reviews;

include_once(MODX_BASE_PATH . 'assets/snippets/reviews/model/reviews.php');

/**
 * Class Controller
 */
class ModuleController
{
    protected $modx;
    protected $data;
    public $dlParams = [
        "controller"   => "reviews",
        "dir"          => "assets/modules/reviews/DocLister/",
        "table"        => "reviews",
        "api"          => 1,
        "idType"       => "documents",
        'ignoreEmpty'  => 1,
        'makeUrl'      => 0,
        'JSONformat'   => "new",
        'display'      => 10,
        'offset'       => 0,
        'sortBy'       => "id",
        'selectFields' => "c.*,sc.pagetitle",
        'sortDir'      => "desc",
    ];


    /**
     * Controller constructor.
     * @param  \DocumentParser  $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->data = new Model($modx);
        $this->dlInit();
    }

    public function getItem()
    {
        $out = [
            'success' => false
        ];
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id && $this->data->edit($id)->getID()) {
            $out = [
                'success' => true,
                'row'     => $this->data->toArray()
            ];
        }

        return $out;
    }

    public function edit()
    {
        $out = $this->modx->runSnippet('ReviewForm', [
            'api'               => 1,
            'formid'            => 'review',
            'id'                => isset($_POST['id']) ? (int) $_POST['id'] : 0,
            'rules'             => [
                'name'   => [
                    'required' => '[%error.name.required%]',
                ],
                'email'  => [
                    'required' => '[%error.email.required%]',
                    'email'    => '[%error.email.email%]',
                ],
                'review' => [
                    'required' => '[%error.review.required%]',
                ]
            ],
            'protectSubmit'     => 0,
            'submitLimit'       => 0,
            'emptyFormControls' => [
                'active' => 0
            ]
        ]);

        return $out;
    }

    public function delete()
    {
        $out = [
            'success' => false
        ];
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($this->data->delete($id)) {
            $out = [
                'success' => true
            ];
        }

        return $out;
    }

    /**
     * @return string
     */
    public function listing()
    {
        return $this->modx->runSnippet("DocLister", $this->dlParams);
    }

    /**
     *
     */
    public function dlInit()
    {
        if (isset($_POST['rows'])) {
            $this->dlParams['display'] = (int) $_POST['rows'];
        }
        $offset = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        $offset = $offset ? $offset : 1;
        $offset = $this->dlParams['display'] * abs($offset - 1);
        $this->dlParams['offset'] = $offset;
        if (isset($_POST['sort'])) {
            $this->dlParams['sortBy'] = '`' . preg_replace('/[^A-Za-z0-9_\-]/', '', $_POST['sort']) . '`';
        }
        if (isset($_POST['order']) && in_array(strtoupper($_POST['order']), ["ASC", "DESC"])) {
            $this->dlParams['sortDir'] = $_POST['order'];
        }

        foreach ($this->dlParams as &$param) {
            if (empty($param)) {
                unset($param);
            }
        }
    }
}
