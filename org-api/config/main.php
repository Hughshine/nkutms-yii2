<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-orgapi',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'orgapi\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'orgapi\models\Organizer',
            'enableAutoLogin' => true,
            'enableSession' => false,
            // 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        // 'session' => [
        //     // this is the name of the session cookie used for login on the backend
        //     'name' => 'advanced-backend',
        // ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity'
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => [
                        'POST search' => 'search'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => [
                        'POST add-activity' => 'add-activity'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => [
                        'POST my-activities' => 'my-activities'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => [
                        'POST edit-activity' => 'edit-activity'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => [
                        'POST cancel-activity' => 'cancel-activity'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => [
                        'POST my-participants' => 'my-participants'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'organizer'
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'organizer',
                    'extraPatterns' => [
                        'POST login' => 'login'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'organizer',
                    'extraPatterns' => [
                        'POST signup' => 'signup'
                    ]
                ],

            ],
        ],
        
    ],
    'params' => $params,
];
