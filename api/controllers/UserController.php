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

		// user 的返回格式特殊处理
		if($user == null){
			$user = new User();
			$user->wechat_id = $sql_wechat;
			$user->category = $sql_category;
			$user->expire_at = time()+3600*24;
			/*
				新微信号的注册处理。没有验证来源是微信小程序。
			 */
			if (!$user->save(false)) 
			{
    			return ['code'=>1,'message' => 'wrong', 'user_info' => null];
			}

			/*
				生成access_token
			 */
			$user->access_token = Yii::$app->security->generateRandomString();
			$access_token = $user->generateAccessToken();
			$user->save(false);//TODO

			$user = User::find()
					->where(['wechat_id' => $sql_wechat])
					->limit(1)
					->one();
			return ['code'=>0,
			'message' => 'create',
			 'data'=>['user_info' => $user,'access_token' => $access_token]];
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

		$sql_id = $request->post('user_id');
		$sql_name = $request->post('name');
		$sql_category = $request->post('category');
		$sql_credential = $request->post('credential');

		$user = User::find()
				->where(['id' => $sql_id])
				->limit(1)
				->one();
		if($user == null)
			return ['code'=>1, 'message'=>'user inexists'];

		$user2 = User::find()
				->where(['credential'=>$sql_credential])
				->limit(1)
				->one();
		if($user2 != null){
			if($user->user_name == $user2->user_name){
				break;
			}
			return ['code'=>1, 'message'=>'dulpilicate credential'];
		}


		$user->user_name = $sql_name==null?$user->user_name:$sql_name;
		$user->category = $sql_category==null?$user->category:$sql_category;
		$user->credential = $sql_credential==null?$user->credential:$sql_credential;

		$user->save(false);

		return ['code'=>0, 'message'=>'success', 'data'=>$user];
	}


}