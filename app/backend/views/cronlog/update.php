<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CronLog */

$this->title = 'Update Cron Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cron Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cron-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
