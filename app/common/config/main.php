<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cacheConsole' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => \Yii::getAlias('@console') . '/runtime/cache'
        ],
        'cacheFrontend' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => \Yii::getAlias('@frontend') . '/runtime/cache'
        ],
        'cacheBackend' => [
            'class' => \yii\caching\FileCache::class,
            'cachePath' => \Yii::getAlias('@backend') . '/runtime/cache'
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=dummy',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'encrypter' => [
            'class'=>'\nickcv\encrypter\components\Encrypter',
            'globalPassword'=>'',
            'iv'=>'',
            'useBase64Encoding'=>false,
            'use256BitesEncoding'=>true,
        ],
    ],
];
