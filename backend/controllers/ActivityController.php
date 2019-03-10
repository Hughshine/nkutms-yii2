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
use backend\models\ActivityFrom;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\Activity;

class ActivityController extends BaseController
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        return $this->render('index');
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();

        $activity=Activity::findIdentity_admin($id);
        if($activity->release_by!=Yii::$app->user->id) return $this->goBack();
        $model = new ActivityFrom();
        $model->activity_name=$activity->activity_name;
        $model->release_by=$activity->release_by;
        $model->category=$activity->category;
        $model->introduction=$activity->introduction;
        $model->location=$activity->location;
        $model->time_start_stamp=date('Y-m-d H:i' , $activity->start_at);
        $model->time_end_stamp=date('Y-m-d H:i' , $activity->end_at);
        $model->ticket_start_stamp=date('Y-m-d H:i' , $activity->ticketing_start_at);
        $model->ticket_end_stamp=date('Y-m-d H:i' , $activity->ticketing_end_at);
        $model->max_people=$activity->max_people;
        if ($model->load(Yii::$app->request->post())&&$model->infoUpdate($activity) )
        {
            //return $this->redirect(['view', 'id' => $act->id]);
            return $this->redirect(['activity/mine']);
        }
        Yii::$app->session->setFlash('warning',$model->lastError);
        return $this->render('update', ['model' => $model]);
    }
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        $model = new ActivityFrom();
        if ($model->load(Yii::$app->request->post())&&(($act = $model->create())!==null) )
        {
            //return $this->redirect(['view', 'id' => $act->id]);
            return $this->redirect(['activity/mine']);
        }
        Yii::$app->session->setFlash('warning',$model->lastError);
        return $this->render('create', ['model' => $model]);
    }

    public function actionMine()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        return $this->render('mine');
    }

    private function viewAction()
    {
        $this->layout='main.php';
        $view=Yii::$app->view;
        $org=Yii::$app->user->identity;
        $view->params['org_name']=$org->org_name;
        $view->params['created_at']=$org->created_at;
    }

    public function actionView($id)
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
}