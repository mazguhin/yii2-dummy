<?php

namespace common\modules\dummyapi\controllers;

use common\models\App;
use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Базовый API онтроллер
 * Данный контроллер реализует стандартные методы API
 *
 * Class BaseApiController
 * @package backend\controllers
 */

class BaseApiController extends Controller
{
    public $enableCsrfValidation = false;

    // модель используемая в контроллере
    public $modelClass = null;

    // стандартное количество записей на страницу
    public static $defaultPageSize = 100;

    // сопостовление методов и типов запроса
    public static $methodRules = [
        'get' => [
            'list',
            'get'
        ],

        'post' => [
            'create',
            'update',
            'delete'
        ]
    ];

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

    /**
     * Устанавливаем формат ответа - JSON
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Проверка на соответствие метода и тип запроса
     * @param $method
     * @param $type
     * @return array|bool
     */
    public static function validateMethod($method)
    {
        $type = Yii::$app->request->isGet ? 'get' : 'post';
        if (isset(static::$methodRules[$type]) && in_array($method, static::$methodRules[$type])) {
            return true;
        }

        return App::makeErrorResponse(405);
    }

    /**
     * Убирает из массива элемент с ключем 'id'
     * @param $data
     * @return mixed
     */
    public function unsetId($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        return $data;
    }

    /**
     * Находит и возвоащает объект модели
     * @return bool
     */
    protected function findModels()
    {
        if ($this->modelClass !== null && ($model = ($this->modelClass)::find()) !== null) {
            return $model;
        }

        return false;
    }

    /**
     *  Поиск модели по id
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if ($this->modelClass !== null && ($model = ($this->modelClass)::findOne($id)) !== null) {
            return $model;
        }

        return false;
    }

    /**
     * Список
     * @return array
     */
    public function actionList()
    {
        $valid = static::validateMethod('list');
        if ($valid !== true) {
            return $valid;
        }

        $get = Yii::$app->request->get();
        $pageSize = isset($get['limit']) ? intval($get['limit'] ) : static::$defaultPageSize;

        $searchArray = [];
        if (!empty($get)) {
            foreach ($get as $key => $param) {
                if (in_array($key, ($this->modelClass)::$cryptFilterFields)) {
                    $searchArray[$key] = Yii::$app->encrypter->encrypt(trim($param));
                } elseif (in_array($key, ($this->modelClass)::$filterFields)) {
                    $searchArray[$key] = $param;
                }
            }
        }

        $sortArray = [];
        if (isset($get['sort'])) {
            $sort = json_decode($get['sort']);
            if (!empty($sort)) {
                foreach ($sort as $key => $param) {
                    if (in_array($key, ($this->modelClass)::$cryptFilterFields) || in_array($key, ($this->modelClass)::$filterFields)) {
                        $sortArray[$key] = trim($param) == "SORT_DESC" ? SORT_DESC : SORT_ASC;
                    }
                }
            }
        }

        $query = ($this->modelClass)::find()
            ->where($searchArray);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
        $pages->pageSizeParam = false;
        $pages->defaultPageSize = $pageSize;
        $models = $query->offset($pages->offset)
            ->orderBy($sortArray)
            ->limit($pages->limit)
            ->all();

        foreach ($models as &$model) {
            $model = ($this->modelClass)::filterAllowDisplayFields($model);
        }

        return App::makeResponse([
            'participants' => $models,
            'total' => $pages->totalCount,
            'defaultPageSize' => $pages->defaultPageSize,
            'pageSizeLimit' => $pages->pageSizeLimit,
        ]);
    }

    /**
     * Получение записи по ее идентификатору
     * @param $id
     * @return array
     */
    public function actionGet($id)
    {
        $valid = static::validateMethod('get');
        if ($valid !== true) {
            return $valid;
        }

        $id = intval($id);
        $model = $this->findModel($id);

        if ($model === false) {
            return App::makeErrorResponse(404);
        }

        $model = ($this->modelClass)::filterAllowDisplayFields($model);
        if ($model === false) {
            return App::makeErrorResponse(500);
        }


        return App::makeResponse([
            'item' => $model,
        ]);
    }

    /**
     * Создание новой записи
     * @return array
     */
    public function actionCreate()
    {
        $valid = static::validateMethod('create');
        if ($valid !== true) {
            return $valid;
        }

        $data = Yii::$app->request->post();
        $data = $this->unsetId($data);

        $model = new $this->modelClass();
        $model->load($data);

        if (!$model->save()) {
            if (empty($model->getErrors())) {
                return App::makeErrorResponse(500, 'При сохранении возникла ошибка');
            }

            return App::makeErrorResponse(400);
        }

        return App::makeResponse([
            'item' => $model,
        ]);
    }

    /**
     * Редактирование записи
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $valid = static::validateMethod('update');
        if ($valid !== true) {
            return $valid;
        }

        $data = Yii::$app->request->post();

        $id = intval($id);
        if (empty($id)) {
            return App::makeErrorResponse(400, 'Отсутствует обязательный параметр ID');
        }

        try {
            $fields = Json::decode($data['fields']);
        } catch (\Exception $e) {
            return App::makeErrorResponse(400);
        }

        if (empty($fields) || !is_array($fields)) {
            return App::makeErrorResponse(400, 'Заполнены не все поля');
        }

        foreach ($fields as $key => $field) {
            if (!in_array($key, ($this->modelClass)::$allowEditFields)) {
                unset($fields[$key]);
            }
        }

        $model = $this->findModel($id);
        if ($model === false) {
            return App::makeErrorResponse(404);
        }

        $model->load($fields);
        if (!$model->save()) {
            if (empty($model->getErrors())) {
                return App::makeErrorResponse(500, 'При сохранении возникла ошибка');
            }

            return App::makeErrorResponse(400);
        }

        return App::makeResponse([
            'item' => $model,
        ]);
    }

    /**
     * Удаление записи
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        $valid = static::validateMethod('delete');
        if ($valid !== true) {
            return $valid;
        }

        $id = intval($id);
        if (empty($id)) {
            return App::makeErrorResponse(400, 'Отсутствует обязательный параметр ID');
        }

        $model = $this->findModel($id);
        if ($model === false) {
            return App::makeErrorResponse(404);
        }

        if (!$model->delete()) {
            return App::makeErrorResponse(500, 'При удалении возникла ошибка');
        }

        return App::makeResponse([]);
    }
}
