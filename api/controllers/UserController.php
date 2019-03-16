<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\User;

use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;

class UserController extends ActiveController
{
	public $modelClass = 'common\models\User';

	public function behaviors() {
        $behaviors = parent::behaviors();
        
        // 当前操作的id
        $currentAction = Yii::$app->controller->action->id;
 
        // 需要进行认证的action
        $authActions = ['edit-profile'];
 
        // 需要进行认证的action就要设置安全认证类
        if(in_array($currentAction, $authActions)) {
 
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
            ];
    	}
        //设置不再请求头返回速率限制信息
        // $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        return $behaviors;
	}

	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		unset($actions['create']);
		unset($actions['view']);
		unset($actions['update']);
		//TODO 限制其他接口
		return $actions;
	}

	/*
		未绑定的微信号，绑定credential;
		POST wechat_id,credential,password,wechat_id,category; url验证access-token
	 */
	public function actionBindCredential()
	{
		$request = Yii::$app->request;

		$wechat_id = $request->post('wechat_id');   
		$credential = $request->post('credential');   
		$password = $request->post('password');
		$category = $request->post('category');   

		if($wechat_id==null||$credential==null||$password==null||$category==null)
		{
			return ['code'=>1,'message'=>'empty paramter'];
		}

		$user = User::find()
					->where(['wechat_id' => $wechat_id])
					->limit(1)
					->one();

		if($user != null)
			return ['code'=>1,'message'=>'user already bind credential!'];

		//TODO:改成hasOne？
		$tmp_user = User::find()
					->where(['credential' => $credential])
					->limit(1)
					->one();

		if($tmp_user!=null)
		{
			if($tmp_user->wechat_id!=null)
				return ['code'=>1,'message'=>'credentail already bind user!'];
			//credential未绑定账号，验证密码
			if(Yii::$app->getSecurity()->validatePassword($password , $tmp_user->password))
			{
				$tmp_user->wechat_id = $wechat_id;
				$tmp_user->save(false);//需要修改，体现对传入参数的控制
				return [ 
					'code'=>0,
					'message'=> 'bind success', 
					'data' => [
						'user_info' => $tmp_user,
						'access_token' => $access_token]
					];
			}
				return ['code'=>1,'message'=>'password doesn\'t match'];
		}

		//TODO
		$user = new User();
		$user->user_name = 'default-name';
		$user->wechat_id = $wechat_id;
		$user->credential = $credential;
		$user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
		$user->category = $category;
		$user->expire_at = time()+3600*7;
		$access_token = $user->generateAccessToken();
		$user->save(false);

		return [ 
			'code'=>0,
			'message'=> 'bind success', 
			'data' => [
				'user_info' => $user,
				'access_token' => $access_token]
			];
		//credential未存在，绑定密码
	}

	public function actionWechatLogin()
	{
		//TODO
		$request = Yii::$app->request;

		$sql_wechat = $request->post('wechat_id');   
		if($sql_wechat == null)
			return ['code'=>1,'message'=>'empty wechat_id'];
		$sql_category = $request->post('category', 3); 

		$user = User::find()
					->where(['wechat_id' => $sql_wechat])
					->limit(1)
					->one();

		// 未绑定的微信号直接禁止
		if($user == null){
			return [
					'code'=>2,
					'message'=> 'please bind credential and password',
					];
		}

		$access_token = $user->generateAccessToken();
		$user->expire_at = time()+3600*24;
		$user->save(false);//TODO

		return [ 'code'=>0,
		'message'=> 'success', 
		'data' => [
			'user_info' => $user,
			'access_token' => $access_token]
		];
	}

	public function actionEditProfile()
	{
		$request = Yii::$app->request;

		$user_id = $request->post('user_id');
		$user_name = $request->post('name');

		$user = User::find()
				->where(['id' => $user_id])
				->limit(1)
				->one();

		if($user == null)
			return ['code'=>1, 'message'=>'user inexists'];


		$user = User::editAndSaveUser($user,$user_name,null,null);

		return ['code'=>0, 'message'=>'success', 'data'=>$user];
	}
}