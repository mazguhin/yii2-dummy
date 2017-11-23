<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CronLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at:datetime',
            'process_title',
            'process_description',
            'message:ntext',
             'time',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}'],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visible' => User::isAdmin()],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
