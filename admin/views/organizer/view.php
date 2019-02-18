<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = $model->org_name;
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改密码', ['repassword', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除该账号?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'org_name',
            //'auth_key',
            //'password',
            'wechat_id',
            'category'=>
            [
                'label'=>'类别',
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
            'signup_at:datetime',
            'updated_at:datetime',
            'access_token',
        ],
    ]) ?>

</div>
