<?php

namespace backend\controllers;

use common\models\UserSearch;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UtilController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'deploy', 'rebuild_js_css'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * Деплой
     * @return mixed
     */
    public function actionDeploy()
    {
        $script_filename = 'pull_repo.sh';
        $script_file_path = Yii::getAlias('@sh') . DIRECTORY_SEPARATOR . $script_filename;
        $result = trim(shell_exec($script_file_path . " 2>&1"));
        $result = preg_replace("#[^а-яА-ЯA-Za-z0-9;:_.,? -]+#u", '', $result);
        Yii::$app->getSession()->setFlash('success', $result);

        return $this->redirect('index');
    }

    /**
     * Пересборка исходников
     * @return mixed
     */
    public function actionRebuild_js_css()
    {
        $script_filename = 'rebuild_css_js.sh';
        $script_file_path = Yii::getAlias('@sh') . DIRECTORY_SEPARATOR . $script_filename;
        $result = trim(shell_exec($script_file_path . " 2>&1"));
        $result = preg_replace("#[^а-яА-ЯA-Za-z0-9;:_.,? -]+#u", '', $result);
        Yii::$app->getSession()->setFlash('success', $result);

        return $this->redirect('index');
    }
}
