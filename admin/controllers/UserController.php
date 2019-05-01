<?php

namespace admin\controllers;

use common\exceptions\ProjectException;
use Yii;
use common\models\User;
use common\models\UserForm;
use admin\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions'=>['index','update','view','changestatus','repassword'],
                        'roles'=>['@'],//登录用户
                    ],
                ],
            ],
            //目前未知。。。。
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 下面的我没碰，magic
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pagesize' => '10'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = User::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.$id.'的用户');
            return $this->redirect('index');
        }

        //由于只有改变用户分类功能,所以没有那么多场景,在这里直接调用save即可
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            Yii::$app->getSession()->setFlash('success', '修改成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model,]);
    }

    /**
     * 一键封号功能
     * @param integer $id
     * @param string $status
     * @return \yii\web\Response
     */
    public function actionChangestatus($id,$status)
    {
        $model = User::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.$id.'的用户');
            return $this->redirect('index');
        }

        $form=new UserForm();
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

    //修改分类功能
    public function actionChangeCategory($id,$category)
    {
        $model = User::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.$id.'的用户');
            return $this->redirect('index');
        }

        $form=new UserForm();
        $form->category=$category;

        try
        {
            if($form->infoUpdate($model,'ChangeCategory'))
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
     * 修改用户密码功能
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionRepassword($id)
    {
        $model = User::findIdentity_admin($id);
        if(!$model)
        {
            Yii::$app->session->setFlash('warning','找不到ID为'.$id.'的用户');
            return $this->redirect('index');
        }

        $form =new UserForm();
        /*注意:需要先往$this->user_id,$this->user_name写入相应的数据
        因为页面显示需要id和名字数据,而传递的模型是表单模型而不是实例模型,所以需要补充数据*/
        $form->user_name=$model->user_name;
        $form->user_id=$model->id;

        try
        {
            if ($form->load(Yii::$app->request->post()) &&$form->rePassword($model,false))
            {
                Yii::$app->getSession()->setFlash('success', '密码修改成功');
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

        return $this->render('password', ['model' => $form,]);
    }

    /**
     * 删除功能不需要
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
