<?php
$defaults = [
    'ignoreMailerResult' => 1,
    'dir'                => 'assets/snippets/reviews/FormLister/',
    'controller'         => 'Reviews',
    'filters'            => [
        'name'   => ['stripTags', 'trim', 'removeExtraSpaces'],
        'email'  => ['email', 'trim'],
        'review' => ['stripTags', 'trim', 'compressText'],
        'rid'    => ['castInt'],
        'rate'   => ['castInt']
    ],
    'rules' => [
        'name' => [
            'required' => '[%error.name.required%]',
        ],
        'email' => [
            'required' => '[%error.email.required%]',
            'email' => '[%error.email.email%]',
        ],
        'review' => [
            'required' => '[%error.review.required%]',
        ],
        'rate' => [
            'required' => '[%error.rate.required%]',
            'numeric' => '[%error.rate.numeric%]',
            'between' => [
                'params' => [0, 5],
                'message' => '[%error.rate.between%]'
            ]
        ],
        'rid' => [
            'required' => '[%error.rid.required%]',
            'numeric' => '[%error.rid.numeric%]',
        ]
    ]
];

return $modx->runSnippet('FormLister', array_merge($defaults, $params));