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
use yii\widgets\ActiveForm;

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
                        'actions' =>
                            [
                                'index','error','create',
                                'mine','view','update',
                                'cancel','upload','change-picture',
                                'remove-picture','ueditor',
                            ],
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

    public function actions()
    {
        return 
        [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'upload'=>[//图片上传组件
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' =>
                    [
                          'imagePathFormat' =>"/upload_files/temp/images/{yyyy}{mm}{dd}{time}{rand:6}",
                    ]
            ],
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/upload_files/ueditor/image/activity/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
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
        return $this->render('index');
    }

    /*
     * 修改活动页面调用
     * */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        $model=Activity::findIdentity_admin($id);
        if($model->release_by!=Yii::$app->user->id) return $this->goBack();
        $form = new ActivityForm();
        $form->activity_name=$model->activity_name;
        $form->act_id=$model->id;
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
        $form->pic_url=$model->pic_url;
        if ($form->load(Yii::$app->request->post())&&$form->infoUpdate($model,'Update') )
        {
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['modelForm' => $form,'scenario'=>'Update']);
    }

    /*
     * 发布活动页面调用
     * */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
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
        return $this->render('mine');
    }

    /*
     * 活动详情页面调用
     * */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /*
     * 一键取消活动功能
     * */
    public function actionCancel($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $model=$this->findModel($id);
        $form=new ActivityForm();
        $form->status=Activity::STATUS_CANCEL;
        if($form->infoUpdate($model,'ChangeStatus'))
            Yii::$app->session->setFlash('success','修改成功');
        return $this->redirect(['view', 'model' => $model,]);
    }

    /*
     * 修改活动预览图
     * */
    public function actionChangePicture($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $model=$this->findModel($id);
        $form=new ActivityForm();
        $form->act_id=$model->id;
        $form->activity_name=$model->activity_name;
        $form->pic_url=$model->pic_url;
        if($form->load(Yii::$app->request->post())&&$form->infoUpdate($model,'ChangePicture'))
        {
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['view', 'model' => $model,]);
        }
        return $this->redirect(['update', 'modelForm' => $form,'scenario'=>'ChangePicture']);
    }

    /*
     * 去除活动预览图
     * */
    public function actionRemovePicture($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        $model=$this->findModel($id);
        $form=new ActivityForm();
        $form->pic_url=null;
        if($form->infoUpdate($model,'ChangePicture'))
        {
            Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['view', 'model' => $model,]);
        }
        return $this->redirect(['update', 'model' => $form,'scenario'=>'ChangePicture']);
    }

    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}