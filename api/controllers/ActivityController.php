<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;

use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;

use yii\behaviors\TimestampBehavior;


class ActivityController extends ActiveController
{
	public $modelClass = 'common\models\Activity';
	//TODO 权限控制，对活动进行提交修改；
	public function behaviors() {
        $behaviors = parent::behaviors();
        
        // 当前操作的id
        $currentAction = Yii::$app->controller->action->id;
 
        // 需要进行认证的action
        $authActions = ['ticketing'];
 
        // 需要进行认证的action就要设置安全认证类
        if(in_array($currentAction, $authActions)) {
 
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
            ];
    	}
        //设置不再请求头返回速率限制信息
        // $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        
        return $behaviors;
        	// [
         //        'class' => TimestampBehavior::className(),
         //        'updatedAtAttribute' => 'allowance_update_at',
         //        // 'value' => new Expression('NOW()'),
         //    ]];
        // return ArrayHelper::merge([
        //     //设置可以接收访问的域和方法。
        //     [
        //         'class' => Cors::className(),
        //         'cors' => [
        //             'Origin' => ['*'],
        //             // 'Access-Control-Request-Method' => ['GET', 'HEAD', 'OPTIONS'],
        //             'Access-Control-Request-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept'],
        //             'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        //         ],
        //     ],
        // ], $behaviors);      
 
	}


	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		unset($actions['create']);
		// unset($actions['view']); //允许访问activity的细节
		unset($actions['update']);
		return $actions;
	}

	public function actionIndex()
	{
		$modelClass = $this->modelClass;

		//TODO: asArray
		return new ActiveDataProvider(
			[
				// 'msg' => 0,
				'query' => $modelClass::find()
				->where(['and',
					['category' => 0],
					['status' => 0],])
				->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>5],
			]
		);
	}
	// public function actionValid()
	// {
	// 	return $customer = Activity::find() //暂时没有问题
	// 	->where(['and',
	// 	['category' => 0],
	// 	['status' => 0],
	// 	])
	// 	->orderBy('release_at DESC')//根据发布时间逆序排序
	// 	// ->asArray() //会破坏fields
	// 	->all();
	// }

	public function actionSearch()
	{

		$request = Yii::$app->request;
		/*
			根据传入的活动名称、类别、状态进行搜索 根据发布时间逆序排序
			其中活动名称是相似检索
		 */
		$sql_name = $request->post('name','');   
		$sql_category = $request->post('category',0);   
		$sql_status = $request->post('status',0);   


		return new ActiveDataProvider(//暂时不增加message,前端通过返回的码判断

			[
				'query' => Activity::find() //暂时没有问题
						->where(['and', 
						['like','name',$sql_name],
						['category' => $sql_category],
						['status' => $sql_status],
						])
						->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>5],
			]
		);
		// return $customer = Activity::find() //暂时没有问题
		// ->where(['and', 
		// ['like','name',$sql_name],
		// ['category' => $sql_category],
		// ['status' => $sql_status],
		// ])
		// ->orderBy('release_at DESC')//根据发布时间逆序排序
		// // ->asArray() //会破坏fields
		// ->all();
	}

	public function actionTicketing()
	{
		return true;
	}
}