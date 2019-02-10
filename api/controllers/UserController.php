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

	public function actionIndex(){}

	public function actionWechatLogin()
	{
		//TODO
		$request = Yii::$app->request;

		$sql_wechat = $request->post('wechat_id');   
		if($sql_wechat == null)
			return ['message'=>'empty wechat_id'];
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
    			return ['message' => 'wrong', 'user_info' => null];
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
			return ['message' => 'create', 'user_info' => $user, 'access_token' => $access_token];
		}

		$access_token = $user->generateAccessToken();
		$user->expire_at = time()+3600*24;
		$user->save(false);//TODO


		return [ 'message'=> 'success', 'user_info' => $user,'access_token' => $access_token];
	}


}