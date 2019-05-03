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
use common\exceptions\ProjectException;
use common\models\ActivityForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Activity;
use yii\web\Controller;

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
                                'ticket-list',
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

    /**
     * 展示index页面
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        return $this->render('index');
    }

    /**
     * 展示修改活动页面
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        $model=$this->ValidateActivityId($id);
        if(!$model) return $this->goBack();

        $form=$this->updateAction_FormCopyModel($model);

        try
        {
            if($form->load(Yii::$app->request->post())&&$form->infoUpdate($model,'Update'))
            {
                Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
                return $this->render('update', ['modelForm' => $form,'scenario'=>'Update']);
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
            return $this->render('update', ['modelForm' => $form,'scenario'=>'Update']);
        }
        catch(\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常'.$exception->getMessage());
            return $this->render('update', ['modelForm' => $form,'scenario'=>'Update']);
        }
    }

    /**
     * 展示发布活动页面调用
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        //创建活动时规定的信息
        $form = new ActivityForm();
        $form->release_by=Yii::$app->user->id;
        $form->status=Activity::STATUS_UNAUDITED;

        try
        {
            if($form->load(Yii::$app->request->post()))
            {
                if (($act = $form->create())!==null)
                {
                    Yii::$app->session->setFlash('success','发布成功');
                    return $this->redirect(['view', 'id' => $act->id]);
                }
            }

            return $this->render('create', ['model' => $form]);
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
            return $this->render('create', ['model' => $form]);
        }
        catch(\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常:'.$exception->getMessage());
            return $this->render('create', ['model' => $form]);
        }
    }

    /**
     * 我的已发布活动页面调用
     * @return string|\yii\web\Response
     */
    public function actionMine()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        return $this->render('mine');
    }

    /**
     * 活动详情页面调用
     * @param integer $id 活动ID
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        if(!is_numeric($id))
            return $this->goBack();
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        $model=Activity::findOne(['id',$id]);
        if($model)
            return $this->render('view', ['model' => $model]);
        else
            return $this->goBack();
    }

    /**
     * 活动票务信息查看
     * @param integer $id 活动ID
     * @return string|\yii\web\Response
     */
    public function actionTicketList($id)
    {
        if(!is_numeric($id))
            return $this->goBack();
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        $model=$this->ValidateActivityId($id);

        if($model)
            return $this->render('ticket-view', ['model' => $model]);
        else
            return $this->goBack();
    }


    /**
     * 一键取消活动功能
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionCancel($id)
    {
        if(!is_numeric($id))
            return $this->goBack();
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');
        try
        {
            $model=$this->ValidateActivityId($id);
            if(!$model)return $this->goBack();

            $form=new ActivityForm();
            $form->status=Activity::STATUS_CANCEL;
            if($form->infoUpdate($model,'ChangeStatus'))
                Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['view', 'id' => $id,]);
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
            return $this->goBack();
        }
        catch (\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常:'.$exception->getMessage());
            return $this->goBack();
        }
    }

    /**
     * 修改活动预览图
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionChangePicture($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        $model=$this->ValidateActivityId($id);
        if(!$model) return $this->redirect('site/index');

        $form=new ActivityForm();
        $form->act_id=$model->id;
        $form->activity_name=$model->activity_name;
        $form->pic_url=$model->pic_url;
        $form->status=Activity::STATUS_UNAUDITED;

        try
        {
            if($form->load(Yii::$app->request->post())&&$form->infoUpdate($model,'ChangePicture'))
            {
                Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['view', 'id' => $id,]);
            }
            return $this->render('update', ['modelForm' => $form,'scenario'=>'ChangePicture']);
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
            return $this->render('update', ['modelForm' => $form,'scenario'=>'ChangePicture']);
        }
        catch(\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常:'.$exception->getMessage());
            return $this->render('update', ['modelForm' => $form,'scenario'=>'ChangePicture']);
        }
    }

    /**
     * 去除活动预览图
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionRemovePicture($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect('site/login');

        $model=$this->ValidateActivityId($id);
        if(!$model) return $this->redirect('index');

        $form=new ActivityForm();
        $form->pic_url=null;
        try
        {
            if($form->infoUpdate($model,'ChangePicture'))
            {
                Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['view', 'id' => $id,]);
            }
            return $this->redirect(['update', 'model' => $form,'scenario'=>'ChangePicture']);
        }
        catch (ProjectException $exception)
        {
            Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
            return $this->redirect(['update', 'model' => $form,'scenario'=>'ChangePicture']);
        }
        catch(\Exception $exception)
        {
            Yii::$app->session->setFlash('warning','未知异常:'.$exception->getMessage());
            return $this->redirect(['update', 'model' => $form,'scenario'=>'ChangePicture']);
        }
    }


    /**
     *将现有模型的信息复制到一个新表单
     * @param Activity $model
     * @return ActivityForm
     */
    private function updateAction_FormCopyModel($model)
    {
        $form = new ActivityForm();
        $form->activity_name=$model->activity_name;
        $form->act_id=$model->id;
        $form->status=Activity::STATUS_UNAUDITED;
        $form->release_by=$model->release_by;
        $form->category=$model->category;
        $form->introduction=$model->introduction;
        $form->location=$model->location;
        $form->getStringTimeFromIntTime($model->start_at,$model->end_at,$model->ticketing_start_at,$model->ticketing_end_at);
        $form->max_people=$model->max_people;
        $form->pic_url=$model->pic_url;
        return $form;
    }

    /**
     * 验证ID为$id的活动的发布者是否是已经登录的组织者,若是且存在,则返回model
     * 否则返回null
     * @param integer $id
     * @return Activity|null
     */
    private function ValidateActivityId($id)
    {
        $model=Activity::findIdentity_admin($id);
        if(!$model||$model->release_by!=Yii::$app->user->id)return null;
        return $model;
    }
}