<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
//composer require "kartik-v/yii2-date-range": "*"需要用此插件

/* @var $this yii\web\View */
/* @var $searchModel admin\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '票务管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <?= Html::a('创建票务记录', ['create'], ['class' => 'btn btn-warning pull-right']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'user_id',
            [
                'attribute' => 'user_name',
                'label'=>'持有者名称',
                'value' => 'user.user_name',
                'filter'=>Html::activeTextInput($searchModel, 'user_name', ['class'=>'form-control']), // 生成一个搜索框
            ],

            //'activity_id',
            [
                'attribute' => 'activity_name',
                'label'=>'活动名称',
                'value' => 'activity.activity_name',
                'filter'=>Html::activeTextInput($searchModel, 'activity_name', ['class'=>'form-control']), // 生成一个搜索框
            ],
            //'created_at:datetime',
            'created_at'=>
                [
                    'label' => '记录创建时间',
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
            'serial_number',
            'status'=>
                [
                    'label'=>'状态',
                    'attribute'=>'status',
                    'value'=>
                        function($model)
                        {
                            switch($model->status)
                            {
                                case 0: return '有效';
                                case 1: return '已退回';
                                case 2: return '过期';
                                default: return '未知';
                            }
                        },
                    'filter'=>['0'=>'有效','1'=>'已退回','2'=>'过期','3'=>'未知'],
                ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
