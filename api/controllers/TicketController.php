<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;

use yii\data\ActiveDataProvider;
use common\models\Ticket;
use common\models\TicketEvent;
use common\models\Activity;


use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;
use yii\behaviors\TimestampBehavior;

class TicketController extends ActiveController
{
		public $modelClass = 'common\models\Ticket';

		public function actions()
		{
			$actions = parent::actions();
			unset($actions['index']);
			unset($actions['view']);
			unset($actions['update']);
			unset($actions['create']);
			return $actions;
		}

		public function behaviors() {
        $behaviors = parent::behaviors();
 
        // 需要进行认证的action就要设置安全认证类
        $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className()
        ];
        //设置不再请求头返回速率限制信息
        // $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        
        return $behaviors;
		}

		public function actionMyTickets()
		{	
			$request = Yii::$app->request;
		/*
			根据传入的活动名称、类别、状态进行搜索 根据发布时间逆序排序
			其中活动名称是相似检索
		 */
			$sql_id = $request->post('user_id');   

			if($sql_id == null)
				return ['code'=>1,'message' => 'empty user_id'];
			
			$modelClass = $this->modelClass;

			$provider = new ActiveDataProvider(
				[
					// 'msg' => 0,
					'query' => $modelClass::find()
						->where(['and',
							['user_id' => $sql_id]
							// ,['status' => 0]
							])
						->orderBy('created_at DESC'),//根据发布时间逆序排序
					
					'pagination' => ['pageSize'=>5],
				]
			);
			//TODO: asArray
			return ['code'=>0,'message'=>'success','data'=> $provider->getModels()];
		}

		/*
			POST, ticket_id 
		 */
		public function actionSearchById()
		{
			$request = Yii::$app->request;
			$sql_ticketid = $request->post('ticket_id');   

			$ticket = Ticket::find()
					->where(['id' => $sql_ticketid])
					->limit(1)
					->one();
			if($ticket == null)
			{
				return ['code' => 1, 'message' => 'ticket do not exist'];
			}
			return $ticket;
		}
		/*
		 	退票
		 */
		public function actionWithdraw()
		{
			$request = Yii::$app->request;
			$sql_ticketid = $request->post('ticket_id');   

			$ticket = Ticket::find()
					->where(['id' => $sql_ticketid])
					->limit(1)
					->one();
			if($ticket == null)
			{
				return ['code' => 1, 'message' => 'ticket do not exist'];
			}

			$ticket -> status = 1;
			$ticket->save(false);

			$activity = Activity::find()
						->where(['id'=>$ticket->activity->id])
						->limit(1)
						->one();
			$activity->current_people--;
			$activity->save(false);

			$ticket_event = new TicketEvent();
			$ticket_event->ticket_id = $ticket->id;
			$ticket_event->user_id = $ticket->user_id;		
			$ticket_event->activity_id = $ticket->activity_id;
			$ticket_event->status = 1;
			$ticket_event->update_at = time();
			$ticket_event->save(false);

			return ['code' => 0, 'message' => 'success', 'data' => $ticket];
		}
}