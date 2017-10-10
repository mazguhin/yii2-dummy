<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Административная панель';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Текущий этап</h1>

        <p class="lead"><?= $stages[$stage] ?></p>
    </div>
</div>
