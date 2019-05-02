<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '通知管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建通知', ['create'], ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'status'=>
            [
                'label'=>'是否对用户可见',
                'attribute'=>'status',
                'value'=>
                function($model)
                {
                    return $model->status==\common\models\Notice::STATUS_ACTIVE?'是':'否';
                },
                'filter'=>['否','是'],
            ],
            'updated_at'=>
                [
                    'label' => '上一次编辑时间',
                    'attribute' => 'updated_at',
                    'headerOptions' => ['style' => 'width: 240px;'],
                    'format' => 'raw',
                    'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->updated_at));
                    },
                    'filter' => DateRangePicker::widget([    // 日期组件
                        'model'=>$searchModel,
                        'attribute' => 'updated_at',
                        'value' => $searchModel->updated_at,
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '/',
                            ]
                        ]
                    ])
                ],
            'created_at'=>
                [
                    'label' => '创建时间',
                    'attribute' => 'created_at',
                    'headerOptions' => ['style' => 'width: 240px;'],
                    'format' => 'raw',
                    'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->created_at));
                    },
                    'filter' => DateRangePicker::widget([    // 日期组件
                        'model'=>$searchModel,
                        'attribute' => 'created_at',
                        'value' => $searchModel->created_at,
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '/',
                            ]
                        ]
                    ])
                ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
