<?php
namespace api\controllers;

use common\exceptions\ProjectException;
use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;
use common\models\ActivityForm;
use common\models\User;
use common\models\Ticket;
use common\models\TicketForm;
// use common\models\TicketEvent;

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
					['status' => Activity::STATUS_APPROVED]])
				->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>5],
			]
		);
		//TODO: asArray
		return ['code'=>0,'message'=>'success','data'=>$provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
	}

	public function actionView($id){
		$activity = Activity::find()->where(['id'=>$id])->limit(1)->one();

		if($activity == null)
			return ['code'=>1,'message'=>'activity inexists'];
		return ['code'=>0,'message'=>'success','data'=>$activity,'pages'=>intval(($provider->getTotalCount()-1)/20)];
	}

	public function actionSearch()
	{

		$request = Yii::$app->request;
		$sql_name = $request->post('name','');   
		$sql_category = $request->post('category',0);   
		$sql_status = $request->post('status',0);   


		$provider = new ActiveDataProvider(
			[
				'query' => Activity::find() //暂时没有问题
						->where(['and', 
						['like','activity_name',$sql_name],
						['category' => $sql_category],
						['status' => $sql_status],
						])
						->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>10],
			]
		);

		return ['code'=>0,'message'=>'success','data' => $provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
	}

	/*
		POST user_id,activity_id
		创建$ticket,$ticket_event
		return $ticket
	 */
	public function actionTicketing()
	{
		$request = Yii::$app->request;

        $activity_id = $request->post('activity_id');

        try
        {
            $ticket=ActivityForm::createTicket(Yii::$app->user->id,$activity_id);
            return ['code'=> 0, 'message' => 'success', 'data' => $ticket];
        }
        catch (ProjectException $exception)
        {
            return ['code' => 1, 'message' => $exception->getExceptionMsg()];
        }
        catch(\Exception $exception)
        {
            return ['code' => 1, 'message' => $exception->getMessage()];
        }


		/*$user = Yii::$app->user->identity;

		$user_id = $user->id;

		$activity_id = $request->post('activity_id');
		
		if($activity_id == null)
			return ['code'=> 1,'message' => 'empty activity_id'];
        if( $user == null )//作用可能不大
            return ['code' => 1, 'message' => 'inner problem -- user'];
		$activity = Activity::find()
				->where(['id' => $activity_id])
				->limit(1)
				->one();

		if( $activity == null )
			return ['code'=> 1, 'message' => 'invalid activity id'];

		if( $activity->status != Activity::STATUS_APPROVED)
		{
			return ['code'=> 1, 'message' => 'invalid activity status'];
		}

		if(time()+7*3600<$activity->ticketing_start_at||time()+7*3600>$activity->ticketing_end_at)
		{
			return ['code'=> 1, 'message' => 'invalid ticketing time'];
		}

		$ticketForm=new TicketForm();
		$ticketForm->is_api=true;
        $ticketForm->activity_id=$activity_id;
        $ticketForm->user_id=$user->id;
        $ticketForm->status=Ticket::STATUS_VALID;
        $ticketForm->serial_number=$activity->current_serial;

		$actForm=new ActivityForm();
        $actForm->is_api=true;
        $actForm->current_serial=$activity->current_serial+1;
        $actForm->current_people=$activity->current_people+1;
        
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
        	$ticket = $ticketForm->create();
        	if(!$ticket)
        	{
 				throw new \Exception('cannot ticket twice');
        	}

            if($actForm->infoUpdate($activity,'ChangeSerial')){
            	$transaction->commit();
                return ['code'=> 0, 'message' => 'success', 'data' => $ticket];
            }
            else
            {
            	throw new \Exception('full');
            }

        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            return ['code' => 1, 'message' => $e->getMessage()];
        }*/

	}
}