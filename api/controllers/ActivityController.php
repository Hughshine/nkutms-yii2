<?php
namespace api\controllers;

use common\exceptions\ProjectException;
use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Activity;
use common\models\ActivityForm;
use common\models\Ticket;

use api\models\ActivityBriefInfo;

use yii\filters\auth\QueryParamAuth;


class ActivityController extends ActiveController
{
	public $modelClass = 'common\models\Activity';

	//TODO 权限控制，对活动进行提交修改；
	public function behaviors() {
        $behaviors = parent::behaviors();
        
        // 当前操作的id
        $currentAction = Yii::$app->controller->action->id;
 
        // 需要进行认证的action
        $authActions = ['ticketing','ticketing-with-trigger'];
 
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
//		$modelClass = $this->modelClass;

		$provider = new ActiveDataProvider(
			[
				// 'msg' => 0,
				'query' => ActivityBriefInfo::find()
                    ->orderBy('start_at DESC'),//根据开始时间
				'pagination' => ['pageSize'=>3],
			]
		);
		//TODO: asArray
		return ['code'=>0,'message'=>'success','data'=>$provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/3+1)];
	}

	public function actionView($id){
		$activity = Activity::find()->where(['id'=>$id])->limit(1)->one();

		if($activity == null)
			return ['code'=>1,'message'=>'activity inexists'];
		return ['code'=>0,'message'=>'success','data'=>$activity];
	}

	public function actionSearch()
	{

		$request = Yii::$app->request;
		$name = $request->post('name','');
		$category = $request->post('category',0);
		$status = $request->post('status',0);


		$provider = new ActiveDataProvider(
			[
				'query' => Activity::find() //暂时没有问题
						->where(['and', 
						['like','activity_name',$name],
						['category' => $category],
						['status' => $status],
						])
						->orderBy('release_at DESC'),//根据发布时间逆序排序
				
				'pagination' => ['pageSize'=>10],
			]
		);

		return ['code'=>0,'message'=>'success','data' => $provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
	}

	/*
		POST user_id,activity_id
		return $ticket
	 */
	public function actionTicketing()//已经添加了trigger...在作业里不使用这个了
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

	}

	public function actionTicketingWithTrigger()
    {
        $request = Yii::$app->request;

        $user = Yii::$app->user;

        $user_id = $user->id;
        $activity_id = $request->post('activity_id');

        try {
            $ticket = new Ticket();
            $ticket->user_id = $user_id;
            $ticket->activity_id = $activity_id;
            $ticket->status = Ticket::STATUS_VALID;
            $ticket->save(false);
        }catch(\Exception $e)
        {
            return ['code' => 1, 'message' => $e->getMessage()];
        }
        return ['code' => 0, 'message' => 'success', 'data' => $ticket];
    }
}