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
                //'created_at:datetime',
                'updated_at:datetime',
                'logged_at:datetime',
                //'access_token',
            ],
        ]) ?>
        <div class="col-lg-1">

        </div>
    </div>
</div>
