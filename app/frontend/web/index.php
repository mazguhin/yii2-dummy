<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php')
);

$common_main_local = __DIR__ . '/../../common/config/main-local.php';

if(file_exists($common_main_local)){
    $config = yii\helpers\ArrayHelper::merge($config, require($common_main_local));
}

/*$main_local = __DIR__ . '/../config/main-local.php';

if(file_exists($main_local)){
    $config = yii\helpers\ArrayHelper::merge($config, require($main_local));
}*/

(new yii\web\Application($config))->run();
