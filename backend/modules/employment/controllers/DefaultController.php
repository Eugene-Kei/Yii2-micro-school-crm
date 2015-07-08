<?php

namespace backend\modules\employment\controllers;

use Yii;
use backend\modules\employment\models\PaidEmployment;
use backend\modules\employment\models\EmploymentSearch;
use backend\modules\employment\models\EmploymentUpdate;
use backend\modules\timetable\models\Timetable;
use backend\modules\group\models\Group;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Carbon\Carbon;

/**
 * DefaultController implements the CRUD actions for PaidEmployment model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'view' => ['get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    /**
     * Lists all PaidEmployment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $groupsArray = ArrayHelper::map(
            Group::find()
                ->asArray()
                ->select('id, name')
                ->orderBy('name')
                ->all(),
            'id', 'name');
        $searchModel = new EmploymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!empty($searchModel->userFullName)) {
            $filterUserDataArray = [
                $searchModel->userFullName => \common\models\Profile::getFullNameByUserId($searchModel->userFullName)
            ];
        } else {
            $filterUserDataArray = [];
        }

        return $this->render('index', compact([
                'searchModel',
                'dataProvider',
                'groupsArray',
                'filterUserDataArray'
            ])
        );
    }

    /**
     * Displays a single PaidEmployment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = PaidEmployment::find()
                ->joinWith(['pay.profile', 'pay', 'timetable', 'timetable.group'])
                ->where(['paid_employment.id'=>$id])
                ->one();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PaidEmployment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelUpdete = new EmploymentUpdate;
        $modelUpdete->id = $model->id;
        $modelUpdete->date = $model->date;
        if(null !== Yii::$app->request->post('EmploymentUpdate')['date']){
            $date = Carbon::createFromFormat('Y-m-d', Yii::$app->request->post('EmploymentUpdate')['date']);
            $model->date = $date->toDateString();
        }
        if(null !== Yii::$app->request->post('EmploymentUpdate')['id']){
            $model->timetable_id = Yii::$app->request->post('EmploymentUpdate')['id'];
        }
        echo Yii::$app->request->post('id');
        
        if (null !== Yii::$app->request->post('EmploymentUpdate')['date'] && 
                null !== Yii::$app->request->post('EmploymentUpdate')['id'] && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }else {
            return $this->render('update', [
                'model' => $modelUpdete,
            ]);
        }
    }
    
    public function actionTimetableList() {
        $parents = Yii::$app->request->post('depdrop_parents');
        if ($parents !== null) {
            $out       = [];
            $date      = Carbon::createFromFormat('Y-m-d', $parents[0]);
            $condition = 'week_day = :week_day';
            $params    = [':week_day' => $date->dayOfWeek];

            $groups = Timetable::find()
                    ->with('group')
                    ->where($condition, $params)
                    ->orderBy('start, end')
                    ->all();

            foreach ($groups as $row) {
                $out[] = ['id' => $row->id, 'name' => $row->group->name . ' ' . $row->start . ' - ' . $row->end];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
        }
        return;
    }

    /**
     * Deletes an existing PaidEmployment model.
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
     * Delete multiple PaidEmployment page.
     */
    public function actionBatchDelete() {
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
     * Finds the PaidEmployment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaidEmployment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaidEmployment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
    
    /**
     * Finds the PaidEmployment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaidEmployment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($id)
    {
        if (($model = PaidEmployment::find()->where(['id' => $id])->all()) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }
}
