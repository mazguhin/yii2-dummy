<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MailTypes */

$this->title = 'Create Mail Types';
$this->params['breadcrumbs'][] = ['label' => 'Mail Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
