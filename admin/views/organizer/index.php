<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\OrganizerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '组织者管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建一个组织者账号', ['create'], ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            /*以下可以修改在view页面能看到的项目
             * */
            'id',
            'org_name',
            'credential',
            //'auth_key',
            //'password',
            //'wechat_id',
            'category'=>
            [
                'label'=>'用户类别',
                'attribute'=>'category',
                'value'=>
                function($model)
                {
                    if(!is_numeric($model->category)||$model->category>=count(ORG_CATEGORY)||$model->category<0)
                        return '未知';
                    return ORG_CATEGORY[$model->category];
                },
                'filter'=>ORG_CATEGORY,
            ],

            'status'=>
            [
                'label'=>'状态',
                'attribute'=>'status',
                'value'=>
                function($model)
                {
                    return ($model->status==10)?'有效':'无效';
                },
                'filter'=>['0'=>'无效','10'=>'有效'],
            ],
            //'signup_time:datetime',
            //'updated_time:datetime',
            //'access_token',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
