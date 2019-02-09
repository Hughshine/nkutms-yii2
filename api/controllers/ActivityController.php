<?php
namespace api\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;

class ActivityController extends ActiveController
{
	public $modelClass = 'common\models\Activity';

	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		return $actions;
	}

	public function actionIndex()
	{
		$modelClass = $this->modelClass;

		return new ActiveDataProvider(
			[
				'query' => $modelClass::find()->asArray(),
				'pagination' => ['pageSize'=>5],
			]
		);
	}
}