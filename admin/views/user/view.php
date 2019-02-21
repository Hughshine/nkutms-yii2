<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->user_name;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改状态', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <!--
        /*
        删除操作接口，需要尖括号和问号括起来
        Html::a('删除该用户', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/
        -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_name',
            //'auth_key',
            //'password',
            //'password_reset_token',
            'status'=>
            [
                'label'=>'状态',
                'attribute'=>'status',
                'value'=>
                function ($model)
                {
                    return ($model->status==0)?'无效':'有效';
                },
            ],
            
            'wechat_id',
            'category'=>
            [
                'label'=>'类别',
                'attribute'=>'category',
                'value'=>
                function($model)
                {
                    if ($model->category==0)
                        return '学生';
                    if ($model->category==1)
                        return '教职员工';
                    return '其他';
                },
            ],
            'credential',
            'signup_at:datetime',
            'updated_at:datetime',
            'access_token',
        ],
    ]) ?>

</div>
