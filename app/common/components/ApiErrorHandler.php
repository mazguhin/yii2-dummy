<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/10/2017
 * Time: 3:08 AM
 */

namespace common\components;

use Yii;
use yii\web\Response;

class ApiErrorHandler extends \yii\web\ErrorHandler
{

    /**
     * @inheridoc
     */

    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        $response->data = $this->convertExceptionToArray($exception);
        $response->setStatusCode($exception->statusCode);

        $response->send();
    }

    /**
     * @inheritdoc
     */

    protected function convertExceptionToArray($exception)
    {
        return [
            'success' => false,
            'errors'=> ['message'=>$exception->getName(),'code'=>$exception->statusCode]
        ];
    }
}