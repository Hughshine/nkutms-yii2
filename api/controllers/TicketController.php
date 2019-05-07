<?php

namespace api\controllers;

use common\exceptions\ProjectException;
use Yii;
use yii\rest\ActiveController;

use yii\data\ActiveDataProvider;
use common\models\Ticket;
use common\models\TicketForm;

use common\models\Activity;
use common\models\ActivityForm;

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
 
        // 需要进行认证的action就要设置安全认证类。TicketController全部需要认证呀
        $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className()
        ];
        //设置请求头不返回速率限制信息
        // $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        
        return $behaviors;
		}

		public function actionMyTickets()
		{	
			$user = Yii::$app->user->identity;

			$modelClass = $this->modelClass;

			$provider = new ActiveDataProvider(
				[
					// 'msg' => 0,
					'query' => $modelClass::find()
						->where(['and',
							['user_id' => $user->id]
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
			$ticket_id = $request->post('ticket_id');

			$ticket = Ticket::findOne(['id' => $ticket_id]);

			if($ticket == null)
			{
				return ['code' => 1, 'message' => 'ticket do not exist'];
			}
			return ['code' => 0, 'message' => 'success', 'data' => $ticket];
		}
		/*
		 	退票，在作业中也暂时不使用啦
		 */
		public function actionWithdraw()
		{
			$request = Yii::$app->request;

            $ticket_id = $request->post('ticket_id');

            $ticket=Ticket::findOne(['id' => $ticket_id,'user_id' => Yii::$app->user->id,'status'=>Ticket::STATUS_VALID]);

            if(!$ticket)
            {
                return ['code' => 1, 'message' => 'ticket do not exist or operation is invalid'];
            }

            try
            {
                ActivityForm::cancelTicket($ticket->activity->id,Yii::$app->user->id);
                return ['code' => 0, 'message' => 'success', 'data' => $ticket];
            }
            catch (ProjectException $exception)
            {
                return ['code' => 1, 'message' => $exception->getExceptionMsg()];
            }
            catch (\Exception $exception)
            {
                return ['code' => 1, 'message' => $exception->getMessage()];
            }

		}

        public function actionWithdrawWithProcedure()
        {
            $request = Yii::$app->request;

            $ticket_id = $request->post('ticket_id');

            $user_id = Yii::$app->user->identity->id;

            try {
                Yii::$app->db->createCommand("CALL withdraw_ticket(:ticket_id, :in_user_id)")
                    ->bindValue(':ticket_id', $ticket_id)
                    ->bindValue(':in_user_id', $user_id)
                    ->execute();
            }catch( \Exception $e ) {
                return ['code' => 1, 'message' => $e->getMessage()];
            }
            return ['code' => 0, 'message' => 'success'];

        }
}