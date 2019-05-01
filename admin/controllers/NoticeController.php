<?php

namespace admin\controllers;

use common\exceptions\ProjectException;
use Yii;
use common\models\Notice;
use common\models\NoticeForm;
use admin\models\NoticeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends Controller
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
                                'actions'=>['index','view','create','update','ueditor','delete'],
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

    public function actions()
    {
        return
            [
                'ueditor'=>[
                    'class' => 'common\widgets\ueditor\UeditorAction',
                    'config'=>[
                        //上传图片配置
                        'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                        'imagePathFormat' => "/upload_files/ueditor/image/notice/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                    ]
                ]
            ];
    }

    /**
     * Lists all Notice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoticeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);
    }

    /**
     * Displays a single Notice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new Notice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $form = new NoticeForm();
        try
        {
            if ($form->load(Yii::$app->request->post()) &&
                ($model=$form->create())!=null)
            {
                Yii::$app->getSession()->setFlash('success','创建成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        catch (ProjectException $exception)
        {
            Yii::$app->getSession()->setFlash('warning',$exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->getSession()->setFlash('warning','未知异常'.$exception->getMessage());
        }
        return $this->render('create', ['model' => $form,]);
    }

    /**
     * Updates an existing Notice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Notice::findOne(['id'=>$id]);
        if(!$model)
        {
            Yii::$app->getSession()->setFlash('warning',sprintf('找不到ID为%d的公告',$id));
            return $this->redirect('index');
        }

        $form=new NoticeForm();
        $form->title=$model->title;
        $form->content=$model->content;

        try
        {
            if ($form->load(Yii::$app->request->post()) &&
                $form->infoUpdate($model))
            {
                Yii::$app->getSession()->setFlash('success','修改成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        catch (ProjectException $exception)
        {
            Yii::$app->getSession()->setFlash('warning',$exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->getSession()->setFlash('warning','未知异常'.$exception->getMessage());
        }
        return $this->render('update', ['model' => $form,]);
    }

    /**
     * 删除接口,目前不需要
     * Deletes an existing Notice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notice::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
