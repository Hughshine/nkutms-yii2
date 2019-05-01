<?php

namespace admin\controllers;

use common\exceptions\ProjectException;
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

        try
        {
            if ($form->load(Yii::$app->request->post()) && $form->create())
            {
                Yii::$app->getSession()->setFlash('success', '创建成功');
                return $this->redirect(['view', 'id' => $form->tk_id]);
            }
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常'.$exception->getMessage());
        }

        return $this->render('create', ['model' => $form,]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Ticket::findOne(['id'=>$id]);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.$id.'的票务');
            return $this->redirect('index');
        }

        $form=new TicketForm();
        $form->tk_id=$model->id;
        $form->user_id=$model->user_id;
        $form->activity_id=$model->activity_id;
        $form->serial_number=$model->serial_number;

        try
        {
            if ($form->load(Yii::$app->request->post()) &&
                $form->infoUpdate($model,'Update'))
            {
                Yii::$app->getSession()->setFlash('success', '修改成功');
                return $this->redirect(['view', 'id' => $form->tk_id]);
            }

        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常'.$exception->getMessage());
        }

        return $this->render('update', ['model' => $form,]);
    }


    /**
     * 一键退票或置为有效功能
     * @param integer $id
     * @param string $status
     * @return \yii\web\Response
     */
    public function actionChangestatus($id,$status)
    {
        $model = Ticket::findOne(['id'=>$id]);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.$id.'的票务');
            return $this->redirect('index');
        }

        $form=new TicketForm();
        $form->tk_id=$model->id;
        $form->user_id=$model->user_id;
        $form->activity_id=$model->activity_id;
        $form->serial_number=$model->serial_number;
        $form->status=$status;

        try
        {
            if($form->infoUpdate($model,'ChangeStatus'))
                Yii::$app->getSession()->setFlash('success', '修改成功');
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常'.$exception->getMessage());
        }

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
