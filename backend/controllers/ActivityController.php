<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 9:58
 */

/*
 * 活动控制器
 * */
namespace backend\controllers;
use common\models\ActivityForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\Activity;
use yii\web\Controller;

class ActivityController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index','error','create','mine','view','update'],
                        'allow' => true,
                        'roles' => ['@'],
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
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $this->viewAction();
        return $this->render('index');
    }

    /*
     * 修改活动页面调用
     * */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $this->viewAction();

        $model=Activity::findIdentity_admin($id);
        if($model->release_by!=Yii::$app->user->id) return $this->goBack();
        $form = new ActivityForm();
        $form->activity_name=$model->activity_name;
        $form->status=$model->status;
        $form->release_by=$model->release_by;
        $form->category=$model->category;
        $form->introduction=$model->introduction;
        $form->location=$model->location;
        $form->time_start_stamp=date('Y-m-d H:i' , $model->start_at);
        $form->time_end_stamp=date('Y-m-d H:i' , $model->end_at);
        $form->ticket_start_stamp=date('Y-m-d H:i' , $model->ticketing_start_at);
        $form->ticket_end_stamp=date('Y-m-d H:i' , $model->ticketing_end_at);
        $form->max_people=$model->max_people;
        if ($form->load(Yii::$app->request->post())&&$form->infoUpdate($model) )
        {
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['view', 'id' => $model->id]);
            //return $this->redirect(['activity/mine']);
        }
        return $this->render('update', ['model' => $form]);
    }

    /*
     * 发布活动页面调用
     * */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $this->viewAction();
        $form = new ActivityForm();
        $form->release_by=Yii::$app->user->id;
        $form->status=Activity::STATUS_UNAUDITED;
        if ($form->load(Yii::$app->request->post())&&(($act = $form->create())!==null) )
        {
            Yii::$app->session->setFlash('success','创建成功');
            return $this->redirect(['view', 'id' => $act->id]);
        }
        return $this->render('create', ['model' => $form]);
    }

    /*
     * 我的已发布活动页面调用
     * */
    public function actionMine()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $this->viewAction();
        return $this->render('mine');
    }

    /*
     * 活动详情页面调用
     * */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $this->viewAction();
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /*
     * 一键取消活动功能
     * */
    public function actionCancel($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //这个是用于向页面组件传数据的:不知为何,在页面转换的时候,页面组件不会接收到上一个页面的数据
    //在这个样式下,最好每个页面调用前都执行这个动作,否则会报错
    private function viewAction()
    {
        $this->layout='main.php';
        $view=Yii::$app->view;
        $org=Yii::$app->user->identity;
        $view->params['org_name']=$org->org_name;
        $view->params['created_at']=$org->created_at;
    }
}