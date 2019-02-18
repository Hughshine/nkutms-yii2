<?php

namespace admin\controllers;

use Yii;
use admin\models\TkActivity;
use admin\models\TkActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessRule;

/**
 * TkActivityController implements the CRUD actions for TkActivity model.
 */
class TkActivityController extends Controller
{
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
                        'actions'=>['index','view','update','delete','create'],
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
     * Lists all TkActivity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TkActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  #分页 
        $dataProvider->pagination = ['pagesize' => '10']; 
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TkActivity model.
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
     * Creates a new TkActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TkActivity();
        if ($model->load(Yii::$app->request->post())) 
        {
            $model->start_at=strtotime($model->time_start_stamp);
            $model->end_at=strtotime($model->time_end_stamp);
            $model->updated_at=$model->release_at=time()+7*3600;
            $model->current_people=0;
            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
            {
                print_r($model->time_start);
                print_r('\n');
                print_r($model->time_end);
                return null;
            }
            
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TkActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->time_start_stamp=$model->start_at;
            $model->time_end_stamp=$model->end_at;
            $model->updated_at=time()+7*3600;
            if($model->save(false))
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing TkActivity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TkActivity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TkActivity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TkActivity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
