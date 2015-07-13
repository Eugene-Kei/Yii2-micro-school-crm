<?php

namespace backend\modules\pay\controllers;

use backend\modules\employment\models\PaidEmployment;
use backend\modules\group\models\Group;
use backend\modules\pay\models\Pay;
use backend\modules\pay\models\PaySearch;
use backend\modules\ticket\models\SeasonTicket;
use backend\modules\user\models\UserSearch;
use common\models\Profile;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Pay model.
 */
class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'view' => ['get'],
                'create' => ['get', 'post'],
                'edit' => ['put', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    /**
     * Lists all Pay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $ticketsArray = ArrayHelper::map(
            SeasonTicket::find()
                ->asArray()
                ->select('id, title')
                ->orderBy('title')
                ->all(),
            'id', 'title');


        $searchModel = new PaySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ticketsArray' => $ticketsArray,
        ]);
    }

    /**
     * Displays a single Pay model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $paidEmployment = new ActiveDataProvider([
            'query' => PaidEmployment::find()
                ->select([
                    '{{%paid_employment}}.id',
                    '{{%paid_employment}}.timetable_id',
                    '{{%paid_employment}}.date',
                    '{{%timetable}}.start',
                    '{{%timetable}}.end',
                    '{{%group}}.name',
                ])
                ->joinWith('timetable.group')
                ->where(['pay_id' => $id])
                ->orderBy('date'),
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => false,
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'paidEmployment' => $paidEmployment,
        ]);
    }

    /**
     * Finds the Pay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pay::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Creates a new Pay model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @return mixed
     */
    public function actionCreate($user_id = false)
    {
        if ($user_id) {
            $profile = Profile::findOne(['user_id' => $user_id]);
            if (!$profile) {
                throw new HttpException(404);
            }

            $model = new Pay();
            $model->setScenario('pay-create');
            $model->maxBonusBalance = $profile->bonus_balance;
            $tickets = SeasonTicket::getTicketArray();

            //ajax validation
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $groups = Group::getGroupArray();

                return $this->render('create', [
                    'model' => $model,
                    'profile' => $profile,
                    'tickets' => $tickets,
                    'groups' => $groups,
                ]);
            }
        } else {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->get());

            return $this->render('listusers', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        }
    }

    /**
     * Deletes an existing Pay model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Delete multiple Pay page.
     */
    public function actionBatchDelete()
    {
        if (($ids = Yii::$app->request->post('ids')) !== null) {
            $models = $this->findModelAll($ids);
            foreach ($models as $model) {
                $model->delete();
            }

            return $this->redirect(['index']);
        } else {
            throw new HttpException(400);
        }
    }

    /**
     * Finds the Pay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($id)
    {
        if (($model = Pay::find()->where(['id' => $id])->all()) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

}
