<?php

namespace common\modules\dummyapi\controllers;

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
     * Тип запроса POST?
     */
    public function isPost()
    {
        if(!Yii::$app->request->isPost){
            return [
                'success' => false,
                'errors' => 'Метод не поддерживается',
                'code' => 405,
            ];
        }
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

        return [
            'participants' => $models,
            'total' => $pages->totalCount,
            'defaultPageSize' => $pages->defaultPageSize,
            'pageSizeLimit' => $pages->pageSizeLimit,
        ];
    }

    /**
     * Получение записи по ее идентификатору
     * @param $id
     * @return array
     */
    public function actionGet($id)
    {
        $id = intval($id);
        $model = $this->findModel($id);

        if ($model === false) {
            return [
                'success' => false,
                'error' => 'Запись не найдена',
                'code' => 404,
            ];
        }

        $model = ($this->modelClass)::$filterAllowDisplayFields($model);
        if ($model === false) {
            return [
                'success' => false,
                'error' => 'Возникла ошибка на сервере',
                'code' => 500,
            ];
        }


        return [
            'success' => true,
            'item' => $model,
        ];
    }

    /**
     * Создание новой записи
     * @return array
     */
    public function actionCreate()
    {
        $isPost = $this->isPost();
        if ($isPost['success'] === false) {
            return $isPost;
        }

        $data = Yii::$app->request->post();
        $data = $this->unsetId($data);

        $model = new $this->modelClass();
        $model->load($data);

        if (!$model->save()) {
            if (empty($model->getErrors())) {
                return [
                    'success' => false,
                    'error' => 'При сохранении возникла ошибка',
                    'code' => 500,
                ];
            }

            return [
                'success' => false,
                'error' => 'Введены неверные данные',
                'code' => 400,
            ];
        }

        return [
            'success' => true,
            'item' => $model,
        ];
    }

    /**
     * Редактирование записи
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $isPost = $this->isPost();
        if ($isPost['success'] === false) {
            return $isPost;
        }

        $data = Yii::$app->request->post();

        $id = intval($id);
        if (empty($id)) {
            return [
                'success' => false,
                'error' => 'Отсутствует обязательный параметр ID',
                'code' => 400,
            ];
        }

        try {
            $fields = Json::decode($data['fields']);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Введены неверные данные',
                'code' => 400,
            ];
        }

        if (empty($fields) || !is_array($fields)) {
            return [
                'success' => false,
                'error' => 'Заполнены не все поля',
                'code' => 400,
            ];
        }

        foreach ($fields as $key => $field) {
            if (!in_array($key, ($this->modelClass)::$allowEditFields)) {
                unset($fields[$key]);
            }
        }

        $model = $this->findModel($id);
        if ($model === false) {
            return [
                'success' => false,
                'error' => 'Запись не найдена',
                'code' => 404,
            ];
        }

        $model->load($fields);
        if (!$model->save()) {
            if (empty($model->getErrors())) {
                return [
                    'success' => false,
                    'error' => 'При сохранении возникла ошибка',
                    'code' => 500,
                ];
            }

            return [
                'success' => false,
                'error' => 'Введены неверные данные',
                'code' => 400,
            ];
        }

        return [
            'success' => true,
            'item' => $model,
        ];
    }

    /**
     * Удаление записи
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        $isPost = $this->isPost();
        if ($isPost['success'] === false) {
            return $isPost;
        }

        $id = intval($id);
        if (empty($id)) {
            return [
                'success' => false,
                'error' => 'Отсутствует обязательный параметр ID',
                'code' => 400,
            ];
        }

        $model = $this->findModel($id);
        if ($model === false) {
            return [
                'success' => false,
                'error' => 'Запись не найдена',
                'code' => 404,
            ];
        }

        if (!$model->delete()) {
            return [
                'success' => false,
                'error' => 'При удалении возникла ошибка',
                'code' => 500,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
