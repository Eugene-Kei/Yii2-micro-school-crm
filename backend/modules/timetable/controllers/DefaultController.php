<?php

namespace backend\modules\timetable\controllers;

use backend\modules\employment\models\PaidEmployment;
use backend\modules\group\models\Group;
use backend\modules\timetable\models\Timetable;
use backend\modules\timetable\models\TimetableCancel;
use backend\modules\timetable\models\TimetableSearch;
use backend\modules\timetable\Module;
use Carbon\Carbon;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Timetable model.
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
                'publish' => ['get'],
                'edit' => ['post'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete'],
                'cancel-lessons' => ['post', 'get']
            ]
        ];

        return $behaviors;
    }

    /**
     * Lists all Timetable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimetableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $groupsArray = Group::getGroupArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groupsArray' => $groupsArray,
        ]);
    }

    /**
     * Displays a single Timetable model.
     * @return mixed
     */
    public function actionView()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Timetable::find()->orderBy('week_day, start, end'),
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Save Timetable to Timetable::$staticTableView.
     * @return mixed
     */
    public function actionPublish()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Timetable::find()
                ->select('*, MOD((`week_day` + 6), 7) AS `mod_day`')
                ->orderBy('mod_day, start, end'),
            'pagination' => false,
            'sort' => false,
        ]);
        $timeTableContent = $this->renderPartial('_view', [
            'dataProvider' => $dataProvider,
        ]);

        $staticTableFileFullPath = Yii::getAlias(Module::getInstance()->staticTableFullPath
            . DIRECTORY_SEPARATOR .
            Timetable::$staticTableView . '.php');

//        FileHelper::
        if (@file_put_contents($staticTableFileFullPath, $timeTableContent)) {
            Yii::$app->session->setFlash('success', Module::t('timetable-admin', 'Schedule published on the site.'));
        } else {
            Yii::$app->session->setFlash('danger',
                Module::t('timetable-admin', 'Error! The schedule is not published on the website.'));
        }
        $this->redirect(['index']);
    }

    /**
     * Clone a single Timetable model.
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {


        $groupsArray = Group::getGroupArray();
        $model = $this->findModel($id);
        $model->setScenario('timetable-clone');
        if (!$model) {
            Yii::$app->session->setFlash('error',
                Module::t('timetable-admin', 'Error! Unable to find a cloned record in the database.'));
        } else {
            $model->setAttribute('id', null);
            $model->setIsNewRecord(true);
            if ($model->save()) {
                //Генерируем сообщение
                Yii::$app->session->setFlash('success',
                    Module::t('timetable-admin', 'Cloned record "{group} | {weekDay} | {start} - {end}".',
                        [
                            'group' => $groupsArray[$model->group_id],
                            'weekDay' => $model->getWeekArray()[$model->week_day],
                            'start' => Yii::$app->formatter->asTime(strtotime($model->start), 'H:i'),
                            'end' => Yii::$app->formatter->asTime(strtotime($model->end), 'H:i'),
                        ]
                    ));
            } else {
                Yii::$app->session->setFlash('danger', Module::t('timetable-admin', 'Error cloning.'));
            }
        }
        $this->redirect(['index']);
    }

    /**
     * Finds the Timetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Timetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Timetable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Creates a new Timetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Timetable();
        $model->setScenario('timetable-create');
        $groupsArray = Group::getGroupArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',
                Module::t('timetable-admin', 'Created record "{group} | {weekDay} | {start} - {end}".',
                    [
                        'group' => $groupsArray[$model->group_id],
                        'weekDay' => $model->getWeekArray()[$model->week_day],
                        'start' => Yii::$app->formatter->asTime(strtotime($model->start), 'H:i'),
                        'end' => Yii::$app->formatter->asTime(strtotime($model->end), 'H:i'),
                    ]
                ));

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'groupsArray' => $groupsArray,
            ]);
        }
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('timetable-update');
        $groupsArray = Group::getGroupArray();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('timetable-admin', 'The data are updated'));

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'groupsArray' => $groupsArray,
            ]);
        }
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page
     * @return mixed
     */
    public function actionEdit()
    {

        if (Yii::$app->request->post('hasEditable') && Yii::$app->request->post('editableKey')) {
            $model = $this->findModel(Yii::$app->request->post('editableKey'));
            $model->setScenario('timetable-edit');
            $postEditableIndex = Yii::$app->request->post('editableIndex');
            $postTimetable = Yii::$app->request->post('Timetable')[$postEditableIndex];
            $output = '';
            $message = '';
            if (is_array($postTimetable)) {
                foreach ($postTimetable as $key => $value) {
                    if (isset($model->$key)) {
                        $model->$key = $output = $value;
                        if ('week_day' === (string)$key) {
                            $output = Timetable::getWeekArray()[$value];
                        }
                        if ('group_id' === (string)$key) {
                            $output = Group::getGroupArray()[$value];
                        }
                        if (!$model->validate() || !$model->save(false)) {
                            echo Json::encode(['output' => $output, 'message' => $model->errors[$key]]);
                        } else {
                            echo Json::encode(['output' => $output, 'message' => '']);
                        }
                        break;
                    }
                }
            }
        }
    }

    /**
     * Deletes an existing Timetable model.
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
     * Delete multiple Timetable page.
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
     * Finds the Timetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Timetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($id)
    {
        if (($model = Timetable::find()->where(['id' => $id])->all()) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Cancel lessons on a date
     * @return string|void
     * @throws \yii\db\Exception
     */
    public function actionCancelLessons()
    {

        $model = new TimetableCancel;
        $parents = Yii::$app->request->post('depdrop_parents');

        if (is_array(Yii::$app->request->post('TimetableCancel')['ids']) &&
            $model->load(Yii::$app->request->post()) && $model->validate()
        ) {
            $date = Carbon::createFromFormat('d.m.Y', $model->date);
            $updatedCount = 0;

            $needUpdate = PaidEmployment::find()
                ->asArray()
                ->joinWith(['timetable', 'pay'])
                ->where(['date' => "{$date->toDateString()}", 'timetable_id' => $model->ids])
                ->all();
            foreach ($needUpdate as $needRow) {
                $maxPaidDateOnGroupForUser = PaidEmployment::find()
                    ->asArray()
                    ->select('MAX(date) as max_date')
                    ->joinWith(['timetable', 'pay'])
                    ->where(
                        [
                            'pay.user_id' => $needRow['pay']['user_id'],
                            'timetable_id' => $needRow['timetable_id'],
                        ]
                    )
                    ->one();
                if (!$maxPaidDateOnGroupForUser) {
                    continue;
                }
                $carbonMaxDate = Carbon::createFromFormat('Y-m-d', $maxPaidDateOnGroupForUser['max_date']);
                //Все элементы расписания для группы
                $allTimetableOnGroup = Timetable::find()
                    ->asArray()
                    ->where(['group_id' => $needRow['timetable']['group_id']])
                    ->all();
                $allTimetableWeekDaysOnGroup = ArrayHelper::getColumn(
                    $allTimetableOnGroup, function ($array) {
                    return ArrayHelper::getValue($array, 'week_day');
                }
                );

                $i = 0;
                while (7 >= $i) {
                    ++$i;
                    $currDate = clone $carbonMaxDate;
                    $carbonMaxDate->addDay();
                    if (!in_array($currDate->dayOfWeek, $allTimetableWeekDaysOnGroup)) {
                        continue;
                    }
                    $notPaidTimetables = Timetable::find()
                        ->asArray()
                        ->where(
                            [
                                'week_day' => $currDate->dayOfWeek,
                                'group_id' => $needRow['timetable']['group_id'],
                            ]
                        )
                        ->andWhere(
                            'NOT EXISTS '
                            . '( '
                            . 'SELECT * FROM paid_employment pe '
                            . 'LEFT JOIN pay p ON p.id = pe.pay_id '
                            . 'WHERE p.user_id = ' . $needRow['pay']['user_id'] . ' '
                            . 'AND `pe`.`date` = "' . $currDate->toDateString() . '" '
                            . 'AND timetable_id = timetable.id'
                            . ')'
                        )
                        ->orderBy('start, end')
                        ->all();
                    if ($notPaidTimetables) {
                        $command = Yii::$app->db->createCommand(
                            'UPDATE paid_employment SET `date` = "' . $currDate->toDateString() . '", '
                            . 'timetable_id = ' . $notPaidTimetables[0]['id'] . ' '
                            . 'WHERE id = ' . $needRow['id']
                        );
                        if (is_int($command->execute())) {
                            ++$updatedCount;
                            break;
                        }
                    } else {

                    }
                }
            }
            if (0 < $updatedCount) {
                Yii::$app->session->setFlash('success',
                    Module::t('timetable-admin', 'Moved training schedule clients: {sum}',
                        ['sum' => $updatedCount]));
            } else {
                Yii::$app->session->setFlash('danger', Module::t('timetable-admin',
                    Module::t('timetable-admin', 'No records found in the timetable of customers at that date.')));
            }

            return $this->render('cancellessons', ['model' => $model]);
        } elseif ($parents !== null) {
            $out = [];
            $date = Carbon::createFromFormat('d.m.Y', $parents[0]);
            $condition = 'week_day = :week_day';
            $params = [':week_day' => $date->dayOfWeek];

            $groups = Timetable::find()
                ->with('group')
                ->where($condition, $params)
                ->orderBy('start, end')
                ->all();

            foreach ($groups as $row) {
                $out[] = ['id' => $row->id, 'name' => $row->group->name . ' ' . $row->start . ' - ' . $row->end];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);

            return;
        } else {
            return $this->render('cancellessons', ['model' => $model]);
        }
    }

}
