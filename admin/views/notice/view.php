<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Notice */

$this->title = '通知'.$model->title;
$this->params['breadcrumbs'][] = ['label' => '通知管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="notice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除这条通知?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'updated_at'=>
                [
                    'label'=>'上一次编辑时间',
                    'attribute'=>'updated_at',
                    'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->updated_at));
                    },
                ],
            'created_at'=>
                [
                    'label'=>'记录创建时间',
                    'attribute'=>'created_at',
                    'value' => function ($model) {
                        return date('Y-m-d:H:i:s',($model->created_at));
                    },
                ],
        ],
    ]) ?>

    <h3>内容:</h3>
    <?= $model->content?>

</div>
