<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\ActivityEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动事件管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--
        <?= Html::a('Create Activity Event', ['create'], ['class' => 'btn btn-success']) ?>
        -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'organizer_id'=>
                [
                    'label'=>'发布者名称',
                    'value' => 'org_name',
                    'filter'=>Html::activeTextInput($searchModel, 'org_name', ['class'=>'form-control']), // 生成一个搜索框
                ],
            'activity_id'=>
                [
                    'label'=>'活动名称',
                    'value' => 'activity_name',
                    'filter'=>Html::activeTextInput($searchModel, 'activity_name', ['class'=>'form-control']), // 生成一个搜索框
                ],
            'status'=>
                [
                    'label'=>'状态',
                    'attribute'=>'status',
                    'value'=>
                        function($model)
                        {
                            if($model->status==0)
                                return '正常';
                            return '无效';
                        },
                    'filter'=>['0'=>'正常','1'=>'无效'],
                ],
            'update_at:datetime',
            //'operated_by_admin',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
