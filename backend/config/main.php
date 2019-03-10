<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\Organizer',
            'enableAutoLogin' => true,
            //更新登录时间的代码：来源于网络
            'on beforeLogin' => function($event)
            {
                $user = $event->identity; //这里的就是Model的实例了
                /*updated_at在此的意义是上一次登录时间，因为
                yii中的save()方法需要用到updated_at字段，而该字段对该应用的其他所有
                类都有用，yii在每次save()都会保存当前时间在updated_at字段内，
                所以就不好改名，直接用于记录上一次登录的时间
                */
                $user->logged_at = time()+7*3600;
                $user->save();
            },
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'urlManager' =>
            [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
            ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
