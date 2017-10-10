<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php')
);

$common_params_local = __DIR__ . '/../../common/config/params-local.php';

if(file_exists($common_params_local)){
    $params = array_merge($params, require($common_params_local));
}

return [
    // set target language to be Russian
    'language' => 'ru-RU',
    // set source language to be Russian
    'sourceLanguage' => 'ru-RU',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'formatter' => [
            'class' => 'common\components\Formatter'
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => 'bC8xdRWhw32RiJhp1OVDtcS0Fozi94-N',
        ],
        'response' => [
            //'format' => 'json'
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-frontend',
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
        'view' => [
            // переопределено стандартное расширение вьюшки
            'defaultExtension' => 'phtml'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',

                /*
                [
                    'pattern' => '/<id:\d+>',
                    'route' => 'site/index',
                    'defaults' => ['id' => null]
                ],
                */

            ],
        ],
    ],
    'params' => $params,
];
