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
        <?php switch($model->status):
            case \common\models\User::STATUS_ACTIVE :?>
                <?= Html::a('封号',
                [
                    'changestatus',
                    'id' => $model->id,
                    'status'=>\common\models\User::STATUS_DELETED
                ],
                [
                    'class' => 'btn btn-warning',
                    'data' =>
                        [
                            'confirm' => '确定封号?',
                            'method' => 'post',
                        ],
                ]) ?>
            <?php break;?>
            <?php case \common\models\User::STATUS_DELETED :?>
                <?= Html::a('解封',
                    [
                        'changestatus',
                        'id' => $model->id,
                        'status'=>\common\models\User::STATUS_ACTIVE
                    ],
                    [
                        'class' => 'btn btn-success',
                        'data' =>
                            [
                                'confirm' => '确定解封?',
                                'method' => 'post',
                            ],
                    ]) ?>
                <?php break;
            default:break;
        endswitch;?>
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
            'email',
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
                    if($model->category>=count(USER_CATEGORY)||$model->category<0)
                        return '未知';
                    return USER_CATEGORY[$model->category];
                },
            ],
            'credential',
            'created_at'=>
            [
                'label'=>'记录创建时间',
                'attribute'=>'created_at',
                'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->created_at));
                    },
            ],
            'updated_at'=>
            [
                'label'=>'记录更新时间',
                'attribute'=>'updated_at',
                'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->updated_at));
                    },
            ],
            //'access_token',
        ],
    ]) ?>

</div>
