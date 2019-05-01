<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\UserForm;

use common\models\ApiConfig;

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
		$js_code = $request->post('code');
		$user_name = $request->post('user_name');  
		$credential = $request->post('credential');   
		$email = $request->post('email');   
		$password = $request->post('password');
		$category = $request->post('category');   

		if($credential==null||$password==null||$category==null||$email==null)
		{
			return ['code'=>1,'message'=>'empty wechat_id/credential/password/category/email'];
		}

	  	if(!$js_code)
		{
			return ['code'=>1, 'message'=>'empty code'];
		}

		$wechat_id;

		$redis = Yii::$app->redis;

		if (!$wechat_id = $redis->get($js_code)) 
		{
			$url = ApiConfig::code2session_url.'jscode2session?appid='.ApiConfig::app_id.'&secret='.ApiConfig::app_secret.'&js_code='.$js_code.'&grant_type=authorization_code';
//调用微信接口
			$headerArray =array("Content-type:application/json;","Accept:application/json");
	        $ch = curl_init();

	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
	        $output = curl_exec($ch);
	        curl_close($ch);
	        $output = json_decode($output,true);

	        $err_msg = 'none';

	        switch ($output['errcode']) {
	        	case 0:
	        		$wechat_id = $output['openid'];
	        		$sql_session_key = $output['session_key'];
	        		break;
	        	case 1:
	        		return ['code'=>1, 'message'=>'wx server is busy'];
	        		break;
	        	case 40029:
		        	return ['code'=>1, 'message'=>'code invalid'];
	        		break;
	        	case 45011:
		        	return ['code'=>1, 'message'=>'访问频繁'];
	        		break;
	        	default:
	        		// $wechat_id = $request->post('wechat_id');//for development sake
	        		$err_msg = $output['errcode'];
	        		return ['code'=>1,'message'=>'wx invalid code'];
	        }
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
				return ['code'=>1,'message'=>'credential already bind other user!'];
			//credential未绑定账号，验证密码
			if($email != $tmp_user->email)
			{
				return ['code'=>1,'message'=>'wrong email！'];
			}

			if(Yii::$app->getSecurity()->validatePassword($password , $tmp_user->password))
			{

				$tmp_user->wechat_id = $wechat_id;
				if(!$tmp_user->save())
				{
					return ['code' => 1, 'bind failed'];
				}
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

		$user = User::find()
					->where(['email' => $email])
					->limit(1)
					->one();
		if($user)
		{
			return ['code'=>1,'message'=>'email has already been taken'];
		}

		//TODO
		$user_form  = new UserForm();
		$user_form->is_api = true;
		$user_form->user_name = $user_name==null?'default-name':$user_name;

		$user_form->wechat_id = $wechat_id; 
		$user_form->email = $email;//TODO
		$user_form->credential = $credential;
		$user_form->password = $password;
		$user_form->rePassword = $password;

		$user_form->category = $category;

		$transaction=Yii::$app->db->beginTransaction();
		try{
			$user = $user_form->create('Create');
			if(!$user)
			{
				throw new \Exception('create failed');
			}
			$user->expire_at = time()+3600*7;
			$access_token = $user->generateAccessToken();

			if(!$user->save())
			{
				throw new \Exception('generate access-token failed');
			}

			$transaction->commit();
			return [ 
				'code'=>0,
				'message'=> 'bind success', 
				'data' => [
					'user_info' => $user,
					'access_token' => $access_token]
				];
		}
		catch(\Exception $e)
		{
			$transaction->rollBack();
			return ['code'=>1,'message'=>$e->getMessage()];
		}
		//credential未存在，绑定密码
	}

	public function actionWechatLogin()
	{
		//TODO 验证来源是微信小程序
		$request = Yii::$app->request;

		$js_code = $request->post('code');

		if(!$js_code)
		{
			return ['code'=>1, 'message'=>'empty code'];
		}


		$url = ApiConfig::code2session_url.'jscode2session?appid='.ApiConfig::app_id.'&secret='.ApiConfig::app_secret.'&js_code='.$js_code.'&grant_type=authorization_code';
//调用微信接口
		$headerArray =array("Content-type:application/json;","Accept:application/json");
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output,true);

        $err_msg = 'none';

        if(array_key_exists("errcode",$output))
        {
        	switch ($output['errcode']) {
	        	case 0:
	        		$sql_wechat = $output['openid'];
	        		$sql_session_key = $output['session_key'];
	        		break;
	        	case 1:
	        		return ['code'=>1, 'message'=>'wx server is busy'];
	        		break;
	        	case 40029:
		        	return ['code'=>1, 'message'=>'code invalid'];
	        		break;
	        	case 45011:
		        	return ['code'=>1, 'message'=>'访问频繁'];
	        		break;
	        	default:
	        		$err_msg = $output['errcode'];
	        		return ['code'=>1, 'message' => $err_msg];
        	}
        }
        else
        {
        	$sql_wechat = $output['openid'];
	        $sql_session_key = $output['session_key'];
        }
		// $sql_category = $request->post('category', 0); 

		$user = User::find()
					->where(['wechat_id' => $sql_wechat])
					->limit(1)
					->one();
		// 未绑定的微信号直接禁止，存入redis
		if($user == null){
			$redis = Yii::$app->redis;
			if (!$val = $redis->get($js_code)) {
				$redis->set($js_code, $sql_wechat);
				$redis->expire($js_code, 3600);
			} 

			return [
					'code'=>2,
					'message'=> 'please bind credential and password',
					'err_msg' => $err_msg
					];
		}

		$access_token = $user->generateAccessToken();
		$user->expire_at = time()+3600*24;

		if (!$user->save()) 
		{
			return ['code'=>1, 'message'=>'generate access-token failed'];
         }

		return [ 'code'=>0,
		'message'=> 'success', 
		'data' => [
			'user_info' => $user,
			'access_token' => $access_token]
		];
	}

	/*
	接口暂取消；
	 */
	public function actionEditProfile()
	{
		return ['code' => 1, 'message' => 'this api is suspended, please turn to web version'];
		/*
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
		*/
	}
}