<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '通知管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('发布一条通知', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'updated_at'=>
                [
                    'label'=>'上一次编辑时间',
                    'attribute'=>'updated_at',
                    'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->updated_at));
                    },
                ],
            'created_at'=>
                [
                    'label'=>'记录创建时间',
                    'attribute'=>'created_at',
                    'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->created_at));
                    },
                ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
