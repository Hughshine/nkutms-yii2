<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Organizer */

$this->title = '注册组织者';
$this->params['breadcrumbs'][] = ['label' => '组织者管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '注册';

?>
<div class="organizer-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
