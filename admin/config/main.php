<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-admin',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'modules' => ['yiigiiModule'],//将gii模块加入到配置文件中
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-admin',
        ],
        'user' => [
            // 'class' => 'admin\models\Admin',
            'identityClass' => 'admin\models\Admin',
            'enableAutoLogin' => false,
            //更新登录时间的代码
            /*'on beforeLogin' => function($event) {
            $user = $event->identity; //这里的就是Model的实例了
            $user->last_login = time()+7*3600;
            $user->save();
            },*/
            'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
        ],
        //路由设置：美化url
        'urlManager' => 
        [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the admin
            'name' => 'advanced-admin',
        ],
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
        //语言包
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '/messages',
                    'fileMap' => [
                        'common' => 'common.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
