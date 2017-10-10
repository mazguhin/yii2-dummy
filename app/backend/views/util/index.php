<?php

/* @var $this yii\web\View */

$this->title = 'Утилиты';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <h2>Deploy</h2>

                <p>Запускает процесс деплоя</p>

                <p><?= \yii\bootstrap\Html::a('Start', ['/util/deploy'], ['class'=>'btn btn-primary']) ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h2>Rebuild js & css</h2>

                <p>Запускает процесс пересборки js и css через Grunt</p>

                <p><?= \yii\bootstrap\Html::a('Start', ['/util/rebuild_js_css'], ['class'=>'btn btn-primary']) ?></p>
            </div>
        </div>

    </div>
</div>
