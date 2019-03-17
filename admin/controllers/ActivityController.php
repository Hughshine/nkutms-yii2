<?php

namespace admin\controllers;

use common\models\Activity;
use Yii;
use admin\models\ActivitySearch;
use admin\models\NOW;
use common\models\ActivityForm;
use yii\debug\panels\EventPanel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessRule;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
{
    public $lastError;//用于事务提交失败后存放信息
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' =>
            [
                'class'=>\yii\filters\AccessControl::className(),
                'only'=>['*'],
                //'except'=>[],//除了什么方法之外
                'rules'=>
                [
                    [//未登录用户不能访问这个控制器里的方法
                        'allow'=>false,
                        'actions'=>['*'],//所有方法不可访问
                        'roles'=>['?'],//未登录用户
                    ],
                    [//登录用户能访问这个控制器里的方法
                        'allow'=>true,
                        //可访问的页面名字
                        'actions'=>['index','view','create','update','review'],
                        'roles'=>['@'],//登录用户
                    ],
                ],
            ],
            //目前未知。。。
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    /**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  #分页 
        $dataProvider->pagination = ['pagesize' => '10']; 
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ActivityForm();
        //直接在页面中向模型写入数据，但是时间和一些默认值需要在表单返回后写入
        if ($form->load(Yii::$app->request->post()))
        {
            $form->start_at=strtotime($form->time_start_stamp);
            $form->end_at=strtotime($form->time_end_stamp);
            $form->ticketing_start_at=strtotime($form->ticket_start_stamp);
            $form->ticketing_end_at=strtotime($form->ticket_end_stamp);
            $form->updated_at=$form->release_at=time()+7*3600;
            $form->current_people=0;
            $form->status=Activity::STATUS_UNAUDITED;
            $form->current_serial=1;
            $act=$form->create();
            if($act!=null) return $this->redirect(['view', 'id' => $act->id]);
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /*
     * 更新操作：先通过id找到需要更新的模型，再新建一个表单进行信息的输入，然后显示
     * 信息输入界面 ，如果提交并且更新成功，返回查看信息页面
     */
    public function actionUpdate($id)
    {
        try
        {
            $model = $this->findModel($id);
            if($model->status!=Activity::STATUS_CANCEL)
            {
                $form =new ActivityForm();

                //复制model的信息
                $form->act_id=$model->id;
                $form->activity_name=$model->activity_name;
                $form->category=$model->category;
                $form->introduction=$model->introduction;
                $form->location=$model->location;
                $form->status=$model->status;
                $form->time_start_stamp=date('Y-m-d H:i' , $model->start_at);
                $form->time_end_stamp=date('Y-m-d H:i' , $model->end_at);
                $form->ticket_start_stamp=date('Y-m-d H:i' , $model->ticketing_start_at);
                $form->ticket_end_stamp=date('Y-m-d H:i' , $model->ticketing_end_at);
                $form->release_by=$model->release_by;
                $form->max_people=$model->max_people;
                $form->current_serial=$model->current_serial;

                if ($form->load(Yii::$app->request->post()) &&
                    $form->infoUpdate($model))
                {
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                return $this->render('update', ['model' => $form,]);
            }
            else
            {
                Yii::$app->getSession()->setFlash('warning', '不能修改被取消的活动');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        catch(NotFoundHttpException $exception)
        {
            Yii::$app->getSession()->setFlash('error', '找不到指定内容');
            return $this->redirect(['index']);
        }
    }

    /*
        一键无效化或通过功能
    */
    public function actionReview($id,$status)
    {
        $model = $this->findModel($id);
        $form=new ActivityForm();
        if($form->review($model,$status))
            Yii::$app->getSession()->setFlash('success', '修改成功');
        return $this->redirect(['view', 'id' => $model->id]);
    }


    /*删除动作，目前没有接口
     * */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
