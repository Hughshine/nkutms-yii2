<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = '组织者：'.$model->org->org_name.'，ID：'.$model->org->id;
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->org->org_name, 'url' => ['view', 'id' => $model->org->id]];
$this->params['breadcrumbs'][] = '修改密码';
?>
<div class="organizer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_passwordform', [
        'model' => $model,
    ]) ?>

</div>
