<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Notice */

$this->title = '发布一条通知';
$this->params['breadcrumbs'][] = ['label' => '通知管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
