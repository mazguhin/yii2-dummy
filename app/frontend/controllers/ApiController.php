<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/10/2017
 * Time: 2:19 AM
 */

namespace frontend\controllers;

use common\components\ApiErrorHandler;
use common\models\Account;
use common\models\App;
use common\models\Photo;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

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

    /*public function init()
    {
        parent::init();
        $handler = new ApiErrorHandler();
        \Yii::$app->set('errorHandler', $handler);
        //необходимо вызывать register, это обязательный метод для регистрации обработчика
        $handler->register();
    }*/

    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * Возвращает текущий этап проекта
     * @return null
     */
    public function actionStage () {
        return [
            'success' => true,
            'stage' => App::stage()
        ];
    }

    /**
     * Загрузка фото
     */
    public function actionUpload_photo(){
        if(!Yii::$app->request->isPost){
            return [
                'success' => false,
                'errors' => App::$errors[1],
                'code' => 1,
            ];
        }

        $data = Yii::$app->request->post();
        $required_fields = ['token'];

        foreach ($required_fields as $required_field) {
            if(empty($data[$required_field])){
                return [
                    'success' => false,
                    'errors' => App::$errors[6] . ' ' . $required_field,
                    'code' => 6,
                ];
            }
        }

        $token = $data['token'];

        $account = Account::findByToken($token);
        if (empty($account)) {

            $response = Account::getSocialInfo($token);

            if ($response) {
                $user = json_decode($response, true);

                // если отсутсвует нужное поле в ответе от соц. сети
                if (empty($user['identity'])) {
                    return [
                        'success' => false,
                        'errors' => !empty($user['error']) ? $user['error'] : $this->errors[200],
                        'code' => 15,
                        'info' => App::$errors[15]
                    ];
                }

                $account = Account::findOne(['identity' => Yii::$app->encrypter->encrypt(trim($user['identity']))]);

                // если акк не найден
                if (empty($account)) {
                    return [
                        'success' => false,
                        'errors' => App::$errors[23],
                        'code' => 23,
                    ];
                }
            }
        }

        $model = new Photo();
        $model->file = UploadedFile::getInstance($model, 'file');

        if($model->upload()){
            return [
                'success' => true,
                'id' => $model->id,
                'photo_small_url' => $model->photo_small_url,
                'photo_big_url' => $model->photo_big_url
            ];
        } else {
            return [
                'success' => false,
                'errors' => $model->getErrors(),
                'code' => 10
            ];
        }
    }
}