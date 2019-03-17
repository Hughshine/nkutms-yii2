<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
?>
<div class="user-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_name',
            //'auth_key',
            //'password',
            //'password_reset_token',
            //'email_validate_token:email',
            //'email:email',
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
            //'updated_time:datetime',
            'credential',
            'category'=>
            [
                'label'=>'用户类别',
                'attribute'=>'category',
                'value'=>
                function($model)
                {
                    return USER_CATEGORY[$model->category];
                },
                'filter'=>['0'=>'学生','1'=>'教职员工','2'=>'其他'],
            ],
            //'credential',
            //'ticket_total',
            //'access_token',
            //'ticket_valid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
