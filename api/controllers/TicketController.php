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
        //设置请求头不返回速率限制信息
        // $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        
        return $behaviors;
		}

		public function actionMyTickets()
		{	
			$request = Yii::$app->request;

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
							,['status' => 0]
							])
						->orderBy('created_at DESC'),//根据发布时间逆序排序
					
					'pagination' => ['pageSize'=>10],
				]
			);
			//TODO: asArray
			return ['code'=>0,'message'=>'success','data'=> $provider->getModels(),'pages'=>intval(($provider->getTotalCount()-1)/10+1)];
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
			
			if($ticket->status != 0)
			{
				return ['code' => 1, 'message' => 'invalid ticket'];
			}

			$ticket -> status = 1;
			$ticket->save(false);

			$activity = Activity::find()
						->where(['id'=>$ticket->activity->id])
						->limit(1)
						->one();
			$activity->current_people--;
			$activity->save(false);

			TicketEvent::generateAndWriteNewTicketEvent($ticket->id,$ticket->user_id,$ticket->activity_id,1,-1);

			return ['code' => 0, 'message' => 'success', 'data' => $ticket];
		}
}