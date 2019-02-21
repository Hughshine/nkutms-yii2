<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\TkActivity */

$this->title = '修改活动状态：'.$model->activity_name;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->act->activity_name, 'url' => ['view', 'id' => $model->act->id]];
$this->params['breadcrumbs'][] = '修改状态';
?>
<div class="tk-activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_updateform', [
        'model' => $model,
    ]) ?>

</div>
