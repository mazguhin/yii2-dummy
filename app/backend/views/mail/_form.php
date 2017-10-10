<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Mail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'participant_id')->textInput() ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'sent')->textInput() ?>

    <?= $form->field($model, 'sent_at')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approved')->textInput() ?>

    <?= $form->field($model, 'locked')->textInput() ?>

    <?= $form->field($model, 'type_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
