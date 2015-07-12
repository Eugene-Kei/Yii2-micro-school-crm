<?php

namespace backend\modules\config\controllers;

use Yii;
use common\models\Config;
use common\models\ConfigSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * DefaultController implements the CRUD actions for Config model.
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors          = parent::behaviors();
        $behaviors['verbs'] = [
            'class'   => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'edit'  => ['post'],
            ]
        ];

        return $behaviors;
    }

    /**
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel  = new ConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page
     * @return mixed
     */
    public function actionEdit() {

        if (Yii::$app->request->post('hasEditable') && Yii::$app->request->post('editableKey')) {
            $model             = $this->findModel(Yii::$app->request->post('editableKey'));
//            $model->setScenario('timetable-edit');
            $postEditableIndex = Yii::$app->request->post('editableIndex');
            $postConfig        = Yii::$app->request->post('Config')[$postEditableIndex];
            $arrayData         = unserialize($model->data);

            $model->value = $output       = $postConfig['value'];
            if ('' === $postConfig['value']) {
                $model->typeValue = 'string';
            } elseif ('0' === $postConfig['value']) {
                $model->typeValue = 'integer';
            }
            if (is_array($arrayData)) {
                $output = $arrayData[$model->value];
            }
            if (!$model->validate() || !$model->save(false)) {
                echo Json::encode(['output' => $output, 'message' => 'Error. ' . $model->errors['value'][0]]);
            } else {
                echo Json::encode(['output' => $output, 'message' => '']);
            }
        }
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($id) {
        if (($model = Config::find()->where(['id' => $id])->all()) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

}
