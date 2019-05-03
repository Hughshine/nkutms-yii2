<?php

namespace admin\controllers;

use common\exceptions\ProjectException;
use common\models\Activity;
use Yii;
use admin\models\ActivitySearch;
use common\models\ActivityForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
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
                        'actions'=>['index','ticket-list','view','create','update','review','ueditor'],
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

    public function actions()
    {
        return
            [
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
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * 创建活动
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $form = new ActivityForm();

        $form->current_people=0;
        $form->status=Activity::STATUS_UNAUDITED;
        $form->current_serial=1;
        //直接在页面中向模型写入数据，但是时间和一些默认值需要在表单返回后写入
        if ($form->load(Yii::$app->request->post()))
        {
            try
            {
                $act=$form->create();
                Yii::$app->session->setFlash('success','创建成功');
                return $this->redirect(['view', 'id' => $act->id]);
            }
            catch(ProjectException $exception)
            {
                Yii::$app->session->setFlash('warning',$exception->getExceptionMsg());
            }
            catch (\Exception $exception)
            {
                Yii::$app->session->setFlash('warning','未知异常'.$exception->getMessage());
            }
        }
        return $this->render('create', ['model' => $form,]);
    }

    /*
     * 更新操作：先通过id找到需要更新的模型，再新建一个表单进行信息的输入，然后显示
     * 信息输入界面 ，如果提交并且更新成功，返回查看信息页面
     */
    public function actionUpdate($id)
    {
        if(!is_numeric($id))return $this->goBack();

        $model = Activity::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->getSession()->setFlash('warning', '找不到指定活动');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        //如果活动已经被取消,则不允许修改
        if($model->status!=Activity::STATUS_CANCEL)
        {
            $form=$this->updateAction_CopyModelIntoANewForm($model);
            try
            {
                if ($form->load(Yii::$app->request->post())&&
                    $form->infoUpdate($model,'Update'))
                {
                    Yii::$app->session->setFlash('success', '修改成功');
                    return $this->redirect(['view', 'id' => $model->id]);
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
        else
        {
            Yii::$app->getSession()->setFlash('warning', '不能修改被取消的活动');
            return $this->redirect(['view', 'id' => $model->id]);
        }
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

        $model=Activity::findOne(['id'=>$id]);

        if($model)
            return $this->render('ticket-view', ['model' => $model]);
        else
            return $this->goBack();
    }

    /**
     * 一键无效化或通过活动功能
     * @param integer $id
     * @param integer $status
     * @return \yii\web\Response
     */
    public function actionReview($id,$status)
    {
        $model = Activity::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->getSession()->setFlash('warning', '找不到指定活动');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $form=new ActivityForm();
        $form->status=$status;
        try
        {
            $form->infoUpdate($model,'ChangeStatus');
            Yii::$app->getSession()->setFlash('success', '修改成功');
        }
        catch (ProjectException $exception)
        {
            Yii::$app->getSession()->setFlash('warning', $exception->getExceptionMsg());
        }
        catch (\Exception $exception)
        {
            Yii::$app->getSession()->setFlash('success', '未知异常:'.$exception->getMessage());
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }


    /*删除动作，目前没有启用
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
        if (($model = Activity::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 从一个已存在的模型中复制信息到一个新建的表单模型中
     * @param Activity $model
     * @return ActivityForm
     */
    private function updateAction_CopyModelIntoANewForm($model)
    {
        $form =new ActivityForm();
        //复制model的信息
        $form->act_id=$model->id;
        $form->activity_name=$model->activity_name;
        $form->category=$model->category;
        $form->introduction=$model->introduction;
        $form->location=$model->location;
        $form->status=$model->status;
        $form->getStringTimeFromIntTime($model->start_at,$model->end_at,$model->ticketing_start_at,$model->ticketing_end_at);
        $form->release_by=$model->release_by;
        $form->max_people=$model->max_people;
        $form->current_serial=$model->current_serial;
        return $form;
    }
}
