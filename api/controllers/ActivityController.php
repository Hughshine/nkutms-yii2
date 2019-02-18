<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;
use common\models\User;
use common\models\Ticket;
use common\models\TicketEvent;

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
	}


	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index']);
		unset($actions['create']);
		unset($actions['view']); //允许访问activity的细节
		unset($actions['update']);
		return $actions;
	}

	public function actionIndex()
	{
		$modelClass = $this->modelClass;

		$provider = new ActiveDataProvider(
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
		//TODO: asArray
		return ['code'=>0,'message'=>'success','data'=>$provider->getModels()];
	}

	public function actionView($id){
		$activity = Activity::find()->where(['id'=>$id])->limit(1)->one();

		if($activity == null)
			return ['code'=>1,'message'=>'activity inexists'];
		return ['code'=>0,'message'=>'success','data'=>$activity];
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


		$provider = new ActiveDataProvider(//暂时不增加message,前端通过返回的码判断

			[
				'query' => Activity::find() //暂时没有问题
						->where(['and', 
						['like','activity_name',$sql_name],
						['category' => $sql_category],
						['status' => $sql_status],
						])
						->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>5],
			]
		);

		return ['code'=>0,'message'=>'success','data' => $provider->getModels()];
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

	/*
		POST user_id,activity_id
		创建$ticket,$ticket_event
		return $ticket
	 */
	public function actionTicketing()
	{
		$request = Yii::$app->request;

		$user_id = $request->post('user_id');
		$activity_id = $request->post('activity_id');

		//TODO验证传入的信息是否符合规则;
		
		if( $user_id == null || $activity_id == null )
			return ['code'=> 1,'message' => 'empty paramters'];

		$user = User::find()
				->where(['id' => $user_id])
				->limit(1)
				->one();

		$activity = Activity::find()
				->where(['id' => $activity_id])
				->limit(1)
				->one();

		if( $user == null || $activity == null )
			return ['code'=> 1, 'message' => 'wrong id'];

		if(time()<$activity->ticketing_start_at||time()>$activity->ticketing_end_at)
		{
			return ['code'=> 1, 'message' => '未在抢票时间内'];
		}

		$current_serial = $activity->current_serial;

		$current_people = $activity->current_people;
		if($current_people > $activity->max_people)
			return ['code'=> 1, 'message'=>'已达上限'];

		// return ['1'=>($user!=null),'2'=>($activity!=null)];
		$ticket = Ticket::find()
				->where([
					'activity_id' => $activity_id,
					'user_id' => $user_id,
					'status' => 0
						])
				->limit(1)
				->one();

		if($ticket != null)
			return ['code'=> 1,'message' => '已抢过票！'];

		$activity->current_people++;
		$activity->current_serial++;
		$activity->save(false);

		$ticket = new Ticket();
		$ticket->user_id = $user_id;
		$ticket->activity_id = $activity_id;
		$ticket->created_at = time();
		$ticket->serial_number = $current_serial;
		$ticket->status = 0;
		$ticket->save(false);


		// return 

		$ticket_event  = new TicketEvent();
		$ticket_event->ticket_id = $ticket->id;
		$ticket_event->user_id = $ticket->user_id;
		// $ticket_event->activity_id = $ticket->ticket_id;
		$ticket_event->activity_id = $ticket->activity_id;
		$ticket_event->status = 0;
		$ticket_event->update_at = time();
		$ticket_event->operated_by_admin = -1;
		$ticket_event->save(false);


		return ['code'=> 0, 'message' => 'success', 'data' => $ticket];
	}
}