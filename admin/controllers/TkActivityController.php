<?php

namespace admin\controllers;

use Yii;
use admin\models\TkActivity;

use common\models\Organizer;

use admin\models\TkActivitySearch;
use admin\models\ActivityUpdateForm;
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
                        //可访问的页面名字
                        'actions'=>['index','view',],
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
        //直接在页面中向模型写入数据，但是时间和一些默认值需要在表单返回后写入
        if ($model->load(Yii::$app->request->post()))
        {
            $model->start_at=strtotime($model->time_start_stamp);
            $model->end_at=strtotime($model->time_end_stamp);
            $model->ticketing_start_at=strtotime($model->ticket_start_stamp);
            $model->ticketing_end_at=strtotime($model->ticket_end_stamp);
            $model->updated_at=$model->release_at=time()+7*3600;
            $model->current_people=0;
            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /*
     * 更新操作：先通过id找到需要更新的模型，再新建一个表单进行信息的输入，然后显示
     * 信息输入界面 ，如果提交并且更新成功，返回查看信息页面
     */
    public function actionUpdate($id)
    {
        /*$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
            $model->time_start_stamp=$model->start_at;
            $model->time_end_stamp=$model->end_at;
            $model->updated_at=time()+7*3600;
            if($model->save(false))//此处需要将false去掉
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model,]);*/

        $model = $this->findModel($id);
        $updateform =new ActivityUpdateForm($model);
        if ($updateform->load(Yii::$app->request->post()) &&
            $updateform->update($model)) 
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $updateform,
        ]);
    }

    /*删除动作，目前没有接口
     * */
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
