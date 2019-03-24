<?php

namespace admin\controllers;

use common\models\TicketForm;
use Yii;
use common\models\Ticket;
use admin\models\TicketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
{
    public $lastError;//用于事务跑出异常后记录异常信息
    /**
     * {@inheritdoc}
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
                                'actions'=>['index','view','update','create','changestatus',],
                                'roles'=>['@'],//登录用户
                            ],
                        ],
                ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ticket models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form=new TicketForm();
        $form->status=Ticket::STATUS_VALID;
        if ($form->load(Yii::$app->request->post()) && $form->create())
        {
            Yii::$app->getSession()->setFlash('success', '创建成功');
            return $this->redirect(['view', 'id' => $form->tk_id]);
        }
        return $this->render('create', ['model' => $form,]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form=new TicketForm();
        $form->tk_id=$model->id;
        $form->user_id=$model->user_id;
        $form->activity_id=$model->activity_id;
        $form->serial_number=$model->serial_number;
        if ($form->load(Yii::$app->request->post()) && $form->infoUpdate($model))
        {
            Yii::$app->getSession()->setFlash('success', '修改成功');
            return $this->redirect(['view', 'id' => $form->tk_id]);
        }
        return $this->render('update', [
            'model' => $form,
        ]);
    }


    /*
        一键退票或置为有效功能
    */
    public function actionChangestatus($id,$status)
    {
        $model = $this->findModel($id);
        $form=new TicketForm();
        $form->tk_id=$model->id;
        $form->user_id=$model->user_id;
        $form->activity_id=$model->activity_id;
        $form->serial_number=$model->serial_number;
        $form->status=$status;
        if($form->infoUpdate($model,'ChangeStatus'))
            Yii::$app->getSession()->setFlash('success', '修改成功');
        else
            Yii::$app->getSession()->setFlash('success', '修改失败');
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    //不需要删除功能
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
