<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CronLog */

$this->title = 'Create Cron Log';
$this->params['breadcrumbs'][] = ['label' => 'Cron Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
