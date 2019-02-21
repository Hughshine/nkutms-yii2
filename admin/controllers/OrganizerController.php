<?php

namespace admin\controllers;

use Yii;
use admin\models\Organizer;
use admin\models\OrganizerSearch;
use admin\models\OrganizerSignupForm;
use admin\models\OrganizerUpdateForm;
use admin\models\OrganizerPasswordForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrganizerController implements the CRUD actions for Organizer model.
 */
class OrganizerController extends Controller
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
                        'actions'=>['index','update','create','repassword','delete','view'],
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
     * Lists all Organizer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrganizerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //index页面的每页条目数pagesize
        $dataProvider->pagination = ['pagesize' => '10'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organizer model.
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
     * Creates a new Organizer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrganizerSignupForm();
        if ($model->load(Yii::$app->request->post())&&(($organizer = $model->signup())!==null) )
        {
            return $this->redirect(['view', 'id' => $organizer->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Organizer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $updateform =new OrganizerUpdateForm($model);
        if ($updateform->load(Yii::$app->request->post()) &&
            $updateform->update($model)) 
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $updateform,
        ]);
    }

    public function actionRepassword($id)
    {
        $model = $this->findModel($id);
        $passwordform =new OrganizerPasswordForm($model);
        if ($passwordform->load(Yii::$app->request->post()) &&$passwordform->repassword($model)) 
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('password', [
            'model' => $passwordform,
        ]);
    }


    /**
     * Deletes an existing Organizer model.
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
     * Finds the Organizer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organizer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organizer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
