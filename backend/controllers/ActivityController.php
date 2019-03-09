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
                        'actions' => ['index','error','create','mine'],
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

    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }
        $this->viewAction();
        //$model=new ActivityFrom();
        //return $this->render('create',['model'=>$model]);
         $model = new ActivityFrom();
        if ($model->load(Yii::$app->request->post())&&(($act = $model->create())!==null) )
        {
            //return $this->redirect(['view', 'id' => $act->id]);
            return $this->redirect(['activity/index']);
        }
        return $this->render('create', ['model' => $model]);
        /*$model = new ActivityFrom();
        //直接在页面中向模型写入数据，但是时间和一些默认值需要在表单返回后写入
        if ($model->load(Yii::$app->request->post()))
        {
            $model->start_at=strtotime($model->time_start_stamp);
            $model->end_at=strtotime($model->time_end_stamp);
            $model->ticketing_start_at=strtotime($model->ticket_start_stamp);
            $model->ticketing_end_at=strtotime($model->ticket_end_stamp);
            $model->updated_at=$model->release_at=time()+7*3600;
            $model->current_people=0;
            $model->release_by=Yii::$app->user->identity->id;
            if($model->save())
            {
                //以后加入前端活动页面的时候换到那个页面
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);*/
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
}