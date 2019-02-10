<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'api\models\User',
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
                    'extraPatterns' => ['POST search' => 'search'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => ['GET valid' => 'valid'],
                ],
                [//可能only字段无用
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'only' => ['POST'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'extraPatterns' => ['POST wechat-login' => 'wechat-login'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'activity',
                    'extraPatterns' => ['POST ticketing' => 'ticketing'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'ticket',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'ticket',
                    'extraPatterns' => ['POST my-tickets' => 'my-tickets'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'ticket',
                    'extraPatterns' => ['POST search-by-id' => 'search-by-id'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'ticket',
                    'extraPatterns' => ['POST withdraw' => 'withdraw'],
                ],
            ],
        ],
        
    ],
    'params' => $params,
];
