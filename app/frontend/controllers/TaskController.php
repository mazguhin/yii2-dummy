<?php

namespace frontend\controllers;

use Yii;
use common\models\Task;
use common\modules\dummyapi\controllers\BaseApiController;

/**
 * Демонстрационный контроллер
 *
 * Class TaskController
 * @package backend\controllers
 */

class TaskController extends BaseApiController{
    public $modelClass = Task::class;

}
