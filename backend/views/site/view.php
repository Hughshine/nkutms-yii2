<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = '我的资料';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-1">

    </div>
    <div class="organizer-view col-lg-10">
        <h1><?= Html::encode($model->org_name) ?></h1>
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
                        if($model->category>=count(ORG_CATEGORY))
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
                //'created_at:datetime',
                'updated_at'=>
                    [
                        'label'=>'资料更新时间',
                        'attribute'=>'updated_at',
                        'value' => function ($data) {
                                return date('Y-m-d:H:i:s',($data->updated_at));
                        },
                    ],
                //'access_token',
            ],
        ]) ?>
        <div class="col-lg-1">

        </div>
    </div>
</div>
