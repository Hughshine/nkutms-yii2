<?php
namespace orgapi\controllers;

use Yii;

// use common\models\
use yii\rest\ActiveController;
use common\models\Organizer;

class OrganizerController extends ActiveController
{
	public $modelClass = 'common\models\Organizer';

	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		unset($actions['create']);
		unset($actions['view']); 
		unset($actions['update']);

		return $actions;
	}
	/*
	 POST organizers/login
	 paras:
	 		credential
	 		password
	 */
	public function actionLogin()
	{
		$request = Yii::$app->request;
		$sql_credential = $request->post('credential');
		$sql_password = $request->post('password');

		// $hash = Yii::$app->getSecurity()->generatePasswordHash($sql_password);
 		// return Yii::$app->getSecurity()->generatePasswordHash('123456');
		$organizer = Organizer::find()
					->where(['credential' => $sql_credential])
					->limit(1)
					->one();
		if($organizer == null)
			return ['message' => 'wrong paramters'];
		if (Yii::$app->getSecurity()->validatePassword($sql_password , $organizer->password)) 
		{
			$organizer->access_token = Yii::$app->getSecurity()->generateRandomString();
			$organizer->logged_at = time();
			$organizer->expire_at = time()+3600*24;
			$organizer->save();
			// return $hash;
			// 
			return ["message" => 'success',"org" => $organizer, 'access_token' => $organizer->access_token];
		}
		return ['message' => 'wrong password'];
	}

	/*
		test function
	 */
	public function actionSignup()
	{
		$request = Yii::$app->request;
		$sql_credential = $request->post('credential');
		$sql_password = $request->post('password');

 		// return Yii::$app->getSecurity()->generatePasswordHash('123456');
		$organizer = Organizer::find()
					->where(['credential' => $sql_credential])
					->limit(1)
					->one();
		if($organizer != null)
			return ['message' => 'org exists'];

		$organizer = new Organizer();

		$organizer->password = Yii::$app->getSecurity()->generatePasswordHash($sql_password);
		$organizer->credential = $sql_credential;
		$organizer->logged_at = time();
		$organizer->access_token = Yii::$app->getSecurity()->generateRandomString();

		$organizer->save();

		return [$organizer, "access-token" => $organizer->access_token ];
		
		// return ['message' => 'wrong password'];
	}
}

