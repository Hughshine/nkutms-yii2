<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = $model->org_name;
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('修改密码', ['repassword', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <!--
        删除操作接口：
        需要尖括号和问号括起来
        Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除该账号?',
                'method' => 'post',
            ],
        ])

        -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'org_name',
            //'auth_key',
            //'password',
            'credential',
            // 'wechat_id',
            'category'=>
            [
                'label'=>'类别',
                'attribute'=>'category',
                'value'=>
                function($model)
                {
                    if(!is_numeric($model->category)||$model->category>=count(ORG_CATEGORY)||$model->category<0)
                        return '未知';
                    return ORG_CATEGORY[$model->category];
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
            //'created_at:datetime',
            'updated_at'=>
            [
                'label'=>'上一次编辑时间',
                'attribute'=>'updated_at',
                'value' => function ($data) {
                        return date('Y-m-d:H:i:s',($data->updated_at));
                    },
            ],
            //'access_token',
        ],
    ]) ?>

</div>
