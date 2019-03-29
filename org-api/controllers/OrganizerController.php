<?php
namespace orgapi\controllers;

use Yii;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use common\models\Organizer;
use common\models\OrganizerForm;

class OrganizerController extends ActiveController
{
	public $modelClass = 'common\models\Organizer';

	public function behaviors() {
        $behaviors = parent::behaviors();
        
        // 当前操作的id
        $currentAction = Yii::$app->controller->action->id;
 
        // 需要进行认证的action
        $authActions = [
        				'edit-profile',
        			];
 
        // 需要进行认证的action就要设置安全认证类
        if(in_array($currentAction, $authActions)) {
 
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
            ];
    	}
    	return $behaviors;
	}

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
			return ['code'=>1,'message' => 'wrong paramters'];
		if (Yii::$app->getSecurity()->validatePassword($sql_password , $organizer->password)) 
		{
			$organizer->access_token = Yii::$app->getSecurity()->generateRandomString();
			$organizer->logged_at = time();
			$organizer->expire_at = time()+3600*24;
			if(!$organizer->save())
			{
				return ['code'=>1, 'org failed to get access-token'];
			}

			return ['code'=>0, "message" => 'success','data'=>["org_info" => $organizer, 'access_token' => $organizer->access_token]];
		}
		return ['code'=>1,'message' => 'wrong password'];
	}

	/**
	 * 
	 */
	public function actionSignup()
	{
		return null;
		/*
		$request = Yii::$app->request;
		$sql_credential = $request->post('credential');
		$sql_password = $request->post('password');

 		// return Yii::$app->getSecurity()->generatePasswordHash('123456');
		$organizer = Organizer::find()
					->where(['credential' => $sql_credential])
					->limit(1)
					->one();
		if($organizer != null)
			return ['code'=>1,'message' => 'org exists'];

		$organizer = new Organizer();

		$organizer->password = Yii::$app->getSecurity()->generatePasswordHash($sql_password);
		$organizer->credential = $sql_credential;
		$organizer->logged_at = time();
		$organizer->access_token = Yii::$app->getSecurity()->generateRandomString();

		$organizer->save();

		return ['code'=>1, 'message' => 'success', 'data' => ['org_info'=>$organizer, "access-token" => $organizer->access_token ]];
		
		// return ['message' => 'wrong password'];
		*/
	}

	/*
		需要access-token
		/organizers/edit-profile
		param:
			org_id（不为空）
			org_id
			category
			credential
			oldpassword（不为空）
			newpassword
	 */
	public function actionEditProfile()
	{
		$request = Yii::$app->request;
		$organizer = Yii::$app->user->identity;

		$sql_name = $request->post('org_name');
		$sql_category = $request->post('category');
		$sql_credential = $request->post('credential');

		$sql_oldpassword = $request->post('oldpassword');
		$sql_newpassword = $request->post('newpassword');

		if($sql_oldpassword == null)
			return ['code' => 1, 'message' => 'no password'];

		if(!Yii::$app->getSecurity()->validatePassword($sql_oldpassword , $organizer->password))
		{
			return ['code' => 1, 'message' => 'wrong password'];
		}

		if($organizer == null)
			return ['code'=>1, 'message'=>'organizer inexists'];

        $organizer->org_name = $category==null?$organizer->org_name:$org_name;
        $organizer->category = $category==null?$organizer->category:$category;
        $organizer->credential = $credential==null?$organizer->credential:$credential;
        $organizer->password = Yii::$app->getSecurity()->generatePasswordHash($newpassword);
        if(!$organizer->save())
        {
        	return ['code'=>1, 'message' => 'organizer update failed'];
        }

		return ['code'=>0, 'message'=>'success','data'=>$organizer];
	}
}

