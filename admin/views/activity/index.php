<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
//composer require "kartik-v/yii2-date-range": "*"   需要用此插件

/* @var $this yii\web\View */
/* @var $searchModel admin\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tk-activity-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创造一个活动记录', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'activity_name',
            //'introduction',
            'category'=>
            [
                'label'=>'活动类别',
                'attribute'=>'category',
                'value'=>
                function($model)
                {
                    return ACT_CATEGORY[$model->category];
                },
                'filter'=>['0'=>'暂无分类','1'=>'文体活动'],
            ],
            'status'=>
            [
                'label'=>'状态',
                'attribute'=>'status',
                'value'=>
                function($model)
                {
                    if($model->status==0)
                        return '未审核';
                    return ($model->status==1)?'已通过':'被驳回';
                },
                'filter'=>['0'=>'未审核','1'=>'已通过','2'=>'被驳回'],
            ],
            //'max_people',
            //'current_people',
            //'start_at:datetime',
            'release_at'=>
                [
                    'label' => '发布时间',
                    'attribute' => 'release_at',
                    'headerOptions' => ['style' => 'width: 240px;'],
                    'format' => 'raw',
                    'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->release_at));
                    },
                    'filter' => DateRangePicker::widget([    // 日期组件
                        'model'=>$searchModel,
                        'attribute' => 'release_at',
                        'value' => $searchModel->release_at,
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '/',
                            ]
                        ]
                    ])
                ],
            'start_at'=>
            [
                'label' => '活动开始时间',
                'attribute' => 'start_at',
                'headerOptions' => ['style' => 'width: 240px;'],
                'format' => 'raw',
                'value' => function ($data) {
                    return date('Y-m-d:H:i:s',($data->start_at));
                },
                'filter' => DateRangePicker::widget([    // 日期组件
                    'model'=>$searchModel,
                    'attribute' => 'start_at',
                    'value' => $searchModel->start_at,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'Y-m-d',
                            'separator' => '/',
                        ]
                    ]
                ])
            ],
            'org_name'=>
            [
                'attribute' => 'org_name',
                'label'=>'发布者名称',
                'value' => 'org_name',
                'filter'=>Html::activeTextInput($searchModel, 'org_name', ['class'=>'form-control']), // 生成一个搜索框
            ],
            //'time_release:datetime',
            //'time_lastedit:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
