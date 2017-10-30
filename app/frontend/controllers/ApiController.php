<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/10/2017
 * Time: 2:19 AM
 */

namespace frontend\controllers;

use common\components\ApiErrorHandler;
use Yii;
use yii\web\Controller;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [],
                'actions' => [
                    'incoming' => [
                        'Origin' => ['*'],
                        'Access-Control-Request-Method' => ['GET', 'POST'/*, 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'*/],
                        'Access-Control-Request-Headers' => ['*'],
                        'Access-Control-Allow-Credentials' => null,
                        'Access-Control-Max-Age' => 86400,
                        'Access-Control-Expose-Headers' => [],
                    ],
                ],
            ],
        ];
    }
}