<?php
namespace orgapi\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

use common\models\Activity;
use common\models\ActivityForm;

// use common\models\User;
use common\models\Ticket;
use common\models\TicketForm;

use common\models\Organizer;
use common\models\OrganizerForm;

// use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;

// use yii\behaviors\TimestampBehavior;

/**
 * 
 */
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
        				'my-participants',
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

	/**
	 * API
	 * http:GET /activities
	 * params: 无
	 * discription: 按照标准json返回格式，10为一组，返回当前有效活动
	 * @return JSON ValidActivities
	 */
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
					['status' => Activity::STATUS_APPROVED]])
				->andWhere(['>','ticketing_end_at',time()+3600*7])
				->orderBy('release_at DESC'),//根据发布时间逆序排序
				'pagination' => ['pageSize'=>10],
			]
		);
		return ['code'=>0,'message'=>'success','data'=>$provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
	}

	/**
	 * API
	 * http: GET /activities/view?id=x
	 * params: id
	 * discription: 查询某具体活动
	 * @param  [int] $id [活动id]
	 * @return [array] [该activity信息]
	 */
	public function actionView($id){
		$activity = Activity::find()->where(['id'=>$id])->limit(1)->one();

		if($activity == null)
			return ['code'=>1,'message'=>'activity inexists'];
		return ['code'=>0,'message'=>'success','data'=>$activity];
	}

	/**
	 * API
	 * http: POST /activities/search
	 * params: name, category, status
	 * discription: 按活动名称，类别，效果，对活动进行搜索，其中活动名称是相似检索。
	 * @return [array] [符合的活动]
	 */
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
	}

	/**
	 * API
	 * POST /activities/my-activities
	 * params: org_id
	 * discription: 查看某组织者发布的全部活动
	 */
	public function actionMyActivities()
	{
		$organizer = Yii::$app->user->identity;

		if($organizer == null)
			return ['code'=>1,'message' => 'inner problem -- organizer is null'];

		return ['code'=>0,'message'=>'success','data'=>Activity::find()
				->where(['release_by' => $organizer->id])
				->all()];
	}

	/**
	 * API
	 * POST /activities/my-participants
	 * params: org_id,activity_id
	 * return tickets(Lists)
	 * discription: 组织者查看某个自己的某个activity的全部参与者
	 */
	public function actionMyParticipants()
	{
		$organizer = Yii::$app->user->identity;

		$request = Yii::$app->request;

		$activity_id = $request->post('activity_id');

		if($activity_id == null)
			return ['code'=>1,'message' => 'empty activity id'];


		if($organizer == null)
			return ['code'=>1,'message' => 'inner problem -- organizer is null'];

		$activity = Activity::find()
					->where(['id'=>$activity_id])
					->limit(1)
					->one();

		if($activity == null)
			return ['code'=>1,'message' => 'activity inexists'];


		return ['code'=>0,'message'=>'success','data'=> Ticket::find()
				->where(['activity_id' => $activity_id])
				->andWhere(['status' => 0])
				->orderBy('serial_number')
				->all()];

	}
	/**
	 * POST /activities/add-activity
	   params: 
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
		discription: 传入参数，新建活动。不允许发布相同名称的活动。
	 */
	public function actionAddActivity()
	{
		$request = Yii::$app->request;
		$organizer = Yii::$app->user->identity;

		$activity_name = $request->post('activity_name');
		$category= $request->post('category');
		$location = $request->post('location');
		$ticketing_start_at = $request->post('ticketing_start_at');
		$ticketing_end_at = $request->post('ticketing_end_at');
		$start_at = $request->post('start_at');
		$end_at = $request->post('end_at');
		$max_people = $request->post('max_people');
		$intro = $request->post('intro','no introduction');

		if($activity_name == null || $category == null|| $location == null || $ticketing_start_at == null || $ticketing_end_at == null || $start_at == null  || $end_at == null || $max_people == null )
			return ['code'=>1,'message' => 'incomplete paramters'];

		// $activity = Activity::find()//TODO: backend 没有加这个限制
		// 			->where(['activity_name' => $activity_name])
		// 			->limit(1)
		// 			->one();
		// if($activity != null)
		// 	return ['code'=>1,'message' => 'duplicate activity name'];

		$act_form = new ActivityForm();
		$act_form->is_api = true;

		$act_form->category = $category;
		$act_form->location = $location;
		$act_form->ticketing_start_at = $ticketing_start_at;
		$act_form->ticketing_end_at = $ticketing_end_at;
		$act_form->start_at = $start_at;
		$act_form->end_at = $end_at;
		$act_form->max_people = $max_people;
		$act_form->introduction = $intro;
		$act_form->activity_name = $activity_name;
		$act_form->release_by = $organizer->id;
		$act_form->status = 0;
		$act_form->pic_url = null;
		$act_form->current_people = 0;
        $act_form->current_serial = 1;
        $act_form->release_at = time()+7*3600;
        // $act_form->time_start_stamp=date('Y-m-d H:i' , $start_at);
        // $act_form->time_end_stamp=date('Y-m-d H:i' , $end_at);
        // $act_form->ticket_start_stamp=date('Y-m-d H:i' , $ticketing_start_at);
        // $act_form->ticket_end_stamp=date('Y-m-d H:i' , $ticketing_end_at);

        $activity=$act_form->create();
		if(!$activity)
		{
			return ['code'=>1,'message' => 'Activity created failed. Check your whether your parameters is valid.'];
		}

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
		$organizer = Yii::$app->user->identity;

		$activity_id = $request->post('activity_id');
		$activity_name = $request->post('activity_name');

		if($activity_id == null)
			return ['code'=>1, 'message' => 'empty activity id'];

		if($organizer == null)
			return ['code'=>1, 'message' => 'inner problem -- organizer not found'];

		$activity = Activity::find()
					->where(['id'=>$activity_id])
					->limit(1)
					->one();

		if($activity == null)
			return ['code'=>1, 'message' => 'activity inexists'];

		if($activity->release_by != $organizer->id)
			return ['code'=>1, 'message' => 'illegal request'];

		if($activity->status == Activity::STATUS_APPROVED)
		{
			return ['code'=>1, 'message' => '已经成功发布，无法修改'];
		}
		if($activity->status == Activity::STATUS_CANCEL)
		{
			return ['code'=>1, 'message' => '已经被取消，无法修改'];
		}

		$category= $request->post('category');
		$location = $request->post('location');
		$ticketing_start_at = $request->post('ticketing_start_at');
		$ticketing_end_at = $request->post('ticketing_end_at');
		$start_at = $request->post('start_at');
		$end_at = $request->post('end_at');
		$max_people = $request->post('max_people');
		$intro = $request->post('intro');

		$act_form = new ActivityForm();
		$act_form->is_api = true;

        $act_form->activity_name = $activity_name==null?$activity->activity_name:$activity_name;
		$act_form->status = Activity::STATUS_APPROVED;
        $act_form->category = $category==null?$activity->category:$category;
        $act_form->location = $location==null?$activity->location:$location;
        $act_form->max_people = $max_people==null?$activity->max_people:$max_people;
        $act_form->introduction = $intro==null?$activity->introduction:$intro;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
			if(!$act_form->infoUpdate($activity,'Update'))
			{
				throw new \Exception('activity update failed');
			}

			$activity->ticketing_start_at = $ticketing_start_at==null?$activity->ticketing_start_at:$ticketing_start_at;
			$activity->ticketing_end_at = $ticketing_end_at==null?$activity->ticketing_end_at:$ticketing_end_at;
			$activity->start_at = $start_at==null?$activity->start_at:$start_at;
			$activity->end_at = $end_at==null?$activity->end_at:$end_at;

			if(!$activity->save())
			{
				throw new \Exception('activity time update failed');
			}

			$transaction->commit();//success
			return ['code'=>0,'message' => 'success' , 'data'=>$activity];
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            return ['code' => 1, 'message' => $e->getMessage()];
        }
        
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
		$organizer = Yii::$app->user->identity;

		$activity_id = $request->post('activity_id');

        $transaction=Yii::$app->db->beginTransaction();
		try
		{
			if($activity_id == null)
				throw new \Exception('empty activity id');

			if($organizer == null)
				throw new \Exception('empty activity id');

			$activity = Activity::find()
						->where(['id'=>$activity_id])
						->limit(1)
						->one();

			if($activity == null)
				throw new \Exception('activity inexists');

			if($activity->release_by != $organizer->id)
				throw new \Exception('illegal request');

			if($activity->status == Activity::STATUS_CANCEL)
				throw new \Exception('already cancelled');
			
			$act_form = new ActivityForm();
			$act_form->status = Activity::STATUS_CANCEL;
			if(!$act_form->infoUpdate($activity,'ChangeStatus'))
			{
				throw new \Exception('cancel failed');
			}

			$transaction->commit();
			return ['code'=>0,'message' => 'cancel success','data'=>$activity];
		}
		catch(\Exception $e)
		{
			$transaction->rollBack();
			return ['code'=>1, 'message'=> $e->getMessage()];
		}
	}
}