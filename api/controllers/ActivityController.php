<?php
namespace api\controllers;
use yii\rest\ActiveController;
use common\models\Activity;

class ActivityController extends ActiveController
{
	public $modelClass = 'common\models\Activity';
}