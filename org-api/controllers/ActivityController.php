<?php
namespace orgapi\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;
// use common\models\User;
use orgapi\models\Ticket;
// use common\models\TicketEvent;
use common\models\Organizer;
use common\models\ActivityEvent;

// use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;

// use yii\behaviors\TimestampBehavior;


class ActivityController extends ActiveController
{
	public $modelClass = 'common\models\Activity';

	//TODO 权限控制，对活动进行提交修改；
	public function behaviors() {
        $behaviors = parent::behaviors();
        
        // 当前操作的id
        $currentAction = Yii::$app->controller->action->id;
 
        // 需要进行认证的action
        $authActions = ['my-activities',
        				'my-partipicipants',
        				'add-activity',
        				'edit-activity',
        				'cancel-activity'
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
		unset($actions['view']); //允许访问activity的细节
		unset($actions['update']);
		return $actions;
	}

	public function actionIndex()
	{
		$modelClass = $this->modelClass;

		//TODO: asArray
		$provider = new ActiveDataProvider(
			[
				// 'msg' => 0,
				'query' => $modelClass::find()
				->where(['and',
					['category' => 0],
					['status' => 0],])
				->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>10],
			]
		);

		return ['code'=>0,'message'=>'success','data'=>$provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
	}

	public function actionView($id){
		$activity = Activity::find()->where(['id'=>$id])->limit(1)->one();

		if($activity == null)
			return ['code'=>1,'message'=>'activity inexists'];
		return ['code'=>0,'message'=>'success','data'=>$activity];
	}
	// // public function actionValid()
	// // {
	// // 	return $customer = Activity::find() //暂时没有问题
	// // 	->where(['and',
	// // 	['category' => 0],
	// // 	['status' => 0],
	// // 	])
	// // 	->orderBy('release_at DESC')//根据发布时间逆序排序
	// // 	// ->asArray() //会破坏fields
	// // 	->all();
	// // }

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
		return ['code'=>0, 'message'=>'success', 'data'=> $provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
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
	 POST /activities/my-activities
	 para: org_id
	 */
	public function actionMyActivities()
	{
		$request = Yii::$app->request;
		$sql_id = $request->post('org_id');

		if($sql_id == null)
			return ['code'=>1,'message' => 'wrong paramters'];

		$organizer = Organizer::find()
					->where(['id'=>$sql_id])
					->limit(1)
					->one();

		if($organizer == null)
			return ['code'=>1,'message' => 'organizer inexists'];

		return ['code'=>0,'message'=>'success','data'=>Activity::find()
				->where(['release_by' => $sql_id])
				->all()];
	}

	/*
	 POST /activities/my-activities
	 para: org_id,activity_id
	 return tickets(Lists)
	 */
	public function actionMyParticipants()
	{
		$request = Yii::$app->request;

		$org_id = $request->post('org_id');
		$activity_id = $request->post('activity_id');

		if($org_id == null || $activity_id == null)
			return ['code'=>1,'message' => 'wrong paramters'];

		$organizer = Organizer::find()
					->where(['id'=>$org_id])
					->limit(1)
					->one();

		if($organizer == null)
			return ['code'=>1,'message' => 'organizer inexists'];

		$activity = Activity::find()
					->where(['id'=>$activity_id])
					->limit(1)
					->one();

		if($organizer == null)
			return ['code'=>1,'message' => 'organizer inexists'];
		if($activity == null)
			return ['code'=>1,'message' => 'activity inexists'];


		return ['code'=>0,'message'=>'success','data'=> Ticket::find()
				// ->where(['organizer_id' => $org_id])
				->where(['activity_id' => $activity_id])
				->andWhere(['status' => 0])
				->orderBy('serial_number')
				->all()];

	}
	/*
		POST /activities/add-activity
		para: 
			org_id 
			activity_name 
			category
			location 
			ticketing_start_at
			ticketing_end_at
			start_at 
			end_at
			max_people
			intro
	 */
	public function actionAddActivity()
	{
		$request = Yii::$app->request;



		$org_id = $request->post('org_id');
		$activity_name = $request->post('activity_name');
		$category= $request->post('category');
		$location = $request->post('location');
		$ticketing_start_at = $request->post('ticketing_start_at');
		$ticketing_end_at = $request->post('ticketing_end_at');
		$start_at = $request->post('start_at');
		$end_at = $request->post('end_at');
		$max_people = $request->post('max_people');
		$intro = $request->post('intro','no introduction');

		if($org_id == null || $activity_name == null || $category == null|| $location == null || $ticketing_start_at == null || $ticketing_end_at == null || $start_at == null  || $end_at == null || $max_people == null )
			return ['code'=>1,'message' => 'incomplete paramters'];

		$activity = Activity::find()
					->where(['activity_name' => $activity_name])
					->limit(1)
					->one();

		if($activity != null)
			return ['code'=>1,'message' => 'duplicate activity name'];



		$activity = new Activity();
		//太可怕了
		$activity->release_by = $org_id;
		$activity->activity_name = $activity_name;
		$activity->category = $category;
		$activity->location = $location;
		$activity->ticketing_start_at = $ticketing_start_at;
		$activity->ticketing_end_at = $ticketing_end_at;
		$activity->start_at = $start_at;
		$activity->end_at = $end_at;
		$activity->release_at = time();
		$activity->max_people = $max_people;
		$activity->introduction = $intro;
		$activity->current_people = 0;
		$activity->current_serial = 1;
		$activity->save(false);

		$activity_event = new ActivityEvent();
		$activity_event->organizer_id = $org_id;
		$activity_event->activity_id = $activity->id;
		$activity_event->status = 0;
		$activity_event->update_at = time();
		$activity_event->operated_by_admin = -1;
		$activity_event->save(false);

		return ['code'=>0, 'message' => 'success', 'data' => $activity];
	}

	/*
		POST /activities/edit-activity
		para: 
			org_id 
			category
			activity_id
			location 
			ticketing_start_at
			ticketing_end_at
			start_at 
			end_at
			intro
	 */
	public function actionEditActivity()
	{
		$request = Yii::$app->request;

		$org_id = $request->post('org_id');
		$activity_id = $request->post('activity_id');
		$activity_name = $request->post('activity_name');

		if($org_id == null || $activity_id == null)
			return ['code'=>1, 'message' => 'empty paramters'];

		$organizer = Organizer::find()
					->where(['id'=>$org_id])
					->limit(1)
					->one();

		if($organizer == null)
			return ['code'=>1, 'message' => 'organizer inexists'];

		$activity = Activity::find()
					->where(['activity_name' => $activity_name])
					->limit(1)
					->one();

		if($activity != null)
			return ['code'=>1, 'message' => 'duplicate activity name'];

		$activity = Activity::find()
					->where(['id'=>$activity_id])
					->limit(1)
					->one();

		if($activity == null)
			return ['code'=>1, 'message' => 'activity inexists'];



		if($organizer == null)
			return ['code'=>1, 'message' => 'organizer inexists'];


		if($activity->release_by != $org_id)
			return ['code'=>1, 'message' => 'illegal request'];



		$category= $request->post('category');
		$location = $request->post('location');
		$ticketing_start_at = $request->post('ticketing_start_at');
		$ticketing_end_at = $request->post('ticketing_end_at');
		$start_at = $request->post('start_at');
		$end_at = $request->post('end_at');
		$max_people = $request->post('max_people');
		$intro = $request->post('intro');



		

		$activity->activity_name = $activity_name==null?$activity->name:$activity_name;

		$activity->category = $category==null?$activity->category:$category;
		// $activity->name = $activity_name==null?$activity->name:$activity_name;
		$activity->location = $location==null?$activity->location:$location;
		$activity->ticketing_start_at = $ticketing_start_at==null?$activity->ticketing_start_at:$ticketing_start_at;
		$activity->ticketing_end_at = $ticketing_end_at==null?$activity->ticketing_end_at:$ticketing_end_at;
		$activity->start_at = $start_at==null?$activity->start_at:$start_at;
		$activity->end_at = $end_at==null?$activity->end_at:$end_at;
		$activity->max_people = $max_people==null?$activity->max_people:$max_people;
		$activity->introduction = $intro==null?$activity->introduction:$intro;
		$activity->save(false);

		return ['code'=>0,'message' => 'success' , 'data'=>$activity];
	}


	/*
		POST /activities/cancel-activity
		para: 
			org_id
			activity_id
	*/
	public function actionCancelActivity()
	{
		$request = Yii::$app->request;

		$org_id = $request->post('org_id');
		$activity_id = $request->post('activity_id');

		if($org_id == null || $activity_id == null)
			return ['code'=>1,'message' => 'empty paramters'];

		$organizer = Organizer::find()
					->where(['id'=>$org_id])
					->limit(1)
					->one();

		if($organizer == null)
			return ['code'=>1,'message' => 'organizer inexists'];

		$activity = Activity::find()
					->where(['id'=>$activity_id])
					->limit(1)
					->one();

		if($organizer == null)
			return ['code'=>1,'message' => 'organizer inexists'];

		if($activity == null)
			return ['code'=>1,'message' => 'activity inexists'];

		if($activity->release_by != $org_id)
			return ['code'=>1,'message' => 'illegal request'];

		if($activity->status == 1)
			return ['code'=>1,'message' => 'already cancelled'];
		$activity->status = 1;
		$activity->save(false);

		$activity_event = new ActivityEvent();

		// 'id' => 'ID',
  //           'organizer_id' => 'Organizer ID',
  //           'activity_id' => 'Activity ID',
  //           'status' => '0-发布1-取消',
  //           'update_at' => 'Update At',
  //           'operated_by_admin' => '-1时，非管理员操作',
		$activity_event->organizer_id = $org_id;
		$activity_event->activity_id = $activity_id;
		$activity_event->status = 1;
		$activity_event->update_at = time()+7*3600;
		$activity_event->operated_by_admin = -1;
		$activity_event->save(false);
		return ['code'=>0,'message' => 'cancel success','data'=>$activity];
	}
}