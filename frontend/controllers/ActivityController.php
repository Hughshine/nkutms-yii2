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

use common\exceptions\ProjectException;
use common\exceptions\ValidateException;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Activity;
use common\models\ActivityForm;
use common\models\Ticket;
use yii\web\Controller;

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

    /**
     * 活动列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 创建一条票记录,接受活动id作为参数
     * 此方法为合法创建记录,创建时间必须在票务开始之后,结束之前
     * @param integer $act_id
     * @return \yii\web\Response
     */
    public function actionCreateTicket($act_id)
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        try
        {
            ActivityForm::createTicket(Yii::$app->user->id,$act_id);
            Yii::$app->getSession()->setFlash('success', '操作成功');
        }
        catch(ProjectException $exception)
        {
            Yii::$app->getSession()->setFlash('warning', $exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->getSession()->setFlash('warning', '未知异常:'.$exception->getMessage());
        }
        return $this->redirect(['view', 'id' => $act_id]);
    }

    /**
     * 取消参与一个活动
     * @param integer $act_id 活动id
     * @return \yii\web\Response
     */
    public function actionCancelTicket($act_id)
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        try
        {
            ActivityForm::cancelTicket($act_id,Yii::$app->user->id);
            Yii::$app->getSession()->setFlash('success', '操作成功');
        }
        catch(ProjectException $exception)
        {
            Yii::$app->getSession()->setFlash('warning', $exception->getExceptionMsg());
        }
        catch(\Exception $exception)
        {
            Yii::$app->getSession()->setFlash('warning', '未知异常:'.$exception->getMessage());
        }
        return $this->redirect(['view', 'id' => $act_id]);
    }

    /**
     * 活动详情页面调用
     * @param integer $id 活动id
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        $model= Activity::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','你要查找的活动不存在');
            return $this->goBack();
        }

        if (Yii::$app->user->isGuest)
            return $this->render('view', ['model' => $model,'isTicketed'=>false]);

        $ticket=Ticket::findOne(['user_id'=>Yii::$app->user->id,'activity_id'=>$id,'status'=>Ticket::STATUS_VALID]);

        if($ticket)
            return $this->render('view', ['model' =>$model,'isTicketed'=>$ticket!=null,'serialNumber'=>$ticket->serial_number]);
        else
            return $this->render('view', ['model' =>$model,'isTicketed'=>$ticket!=null]);
    }

}