<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/21
 * Time: 20:35
 */

/*
 * 活动控制器
 * */
namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\Activity;
use common\models\ActivityForm;
use common\models\Ticket;
use common\models\TicketForm;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class ActivityController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => ['index','error','view',],
                            'allow' => true,
                            'roles' => ['@','?'],
                        ],
                        [
                            'actions' => ['create-ticket','cancel-ticket',],
                            'allow' => false,
                            'roles' => ['?',],
                        ],
                        [
                            'actions' => ['create-ticket','cancel-ticket',],
                            'allow' => true,
                            'roles' => ['@',],
                        ],
                    ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /*
     * 活动列表
     * */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 创建一条票记录,接受活动id作为参数
     * 此方法为合法创建记录,创建时间必须在票务开始之后,结束之前
     * */
    public function actionCreateTicket($act_id)
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $act=Activity::findOne(['id'=>$act_id,'status'=>Activity::STATUS_APPROVED]);
        if(!$act)
        {
            Yii::$app->getSession()->setFlash('warning','该活动不存在');
            return $this->redirect(['view', 'id' => $act->id]);
        }
        if(time()+7*3600<$act->ticketing_start_at)
        {
            Yii::$app->getSession()->setFlash('warning','票务尚未开始,别急');
            return $this->redirect(['view', 'id' => $act->id]);
        }
        if(time()+7*3600>$act->ticketing_end_at)
        {
            Yii::$app->getSession()->setFlash('warning','票务已过期');
            return $this->redirect(['view', 'id' => $act->id]);
        }
        $ticketForm=new TicketForm();
        $actForm=new ActivityForm();
        $ticketForm->activity_id=$act_id;
        $ticketForm->user_id=Yii::$app->user->id;
        $ticketForm->status=Ticket::STATUS_VALID;
        $ticketForm->serial_number=$act->current_serial;
        $actForm->current_serial=$act->current_serial+1;
        $actForm->current_people=$act->current_people+1;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if($ticketForm->create()&&$actForm->infoUpdate($act,'ChangeSerial'))
                Yii::$app->getSession()->setFlash('success', '操作成功');
            else
                throw new \Exception('操作失败');

            $transaction->commit();
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('warning', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $act->id]);
    }

    /*
     * 取消参与一个活动
     * */
    public function actionCancelTicket($act_id)
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $ticket=Ticket::findOne(['user_id'=>Yii::$app->user->id,'activity_id'=>$act_id,'status'=>Ticket::STATUS_VALID]);
        $act=Activity::findOne(['id'=>$act_id,'status'=>Activity::STATUS_APPROVED]);
        if(!$ticket)
        {
            Yii::$app->getSession()->setFlash('warning','所操作票记录不存在');
            return $this->redirect(['view', 'id' => $act->id,'isTicketed'=>false]);
        }
        $ticketForm=new TicketForm();
        $actForm=new ActivityForm();
        $ticketForm->status=Ticket::STATUS_WITHDRAW;
        $actForm->current_people=$act->current_people-1;
        if($actForm->current_people<0)$actForm->current_people=0;
        $actForm->current_serial=$act->current_serial;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if($ticketForm->infoUpdate($ticket,'ChangeStatus')&&$actForm->infoUpdate($act,'ChangeSerial'))
                Yii::$app->getSession()->setFlash('success', '操作成功');
            else
                throw new \Exception('操作失败');
            $transaction->commit();
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('warning', $e->getMessage());

        }
        return $this->redirect(['view', 'id' => $act->id]);
    }

    /*
     * 活动详情页面调用
     * */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->render('view', ['model' => $this->findModel($id),'isTicketed'=>false]);
        $ticket=Ticket::findOne(['user_id'=>Yii::$app->user->id,'activity_id'=>$id,'status'=>Ticket::STATUS_VALID]);
        $model= $this->findModel($id);
        if(!$model)return $this->redirect('index');
        return $this->render('view', ['model' =>$model,'isTicketed'=>$ticket!=null,'serialNumber'=>$ticket->serial_number]);
    }


    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}