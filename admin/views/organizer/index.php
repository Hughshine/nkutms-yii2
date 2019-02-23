<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrganizerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '组织者管理';
?>
<div class="organizer-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建一个组织者', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
                    if ($model->category==0)
                        return '校级组织';
                    return '学生社团';
                },
                'filter'=>['0'=>'校级组织','1'=>'学生社团'],
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
