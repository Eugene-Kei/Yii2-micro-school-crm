<?php

namespace backend\modules\user\controllers;

use backend\modules\user\models\User;
use backend\modules\user\models\UserSearch;
use common\models\Profile;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for User model.
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
                'affiliate' => ['post'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
                'batch-delete' => ['post', 'delete']
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => Profile::AVATAR_UPLOAD_TEMP_PATH,
            ]
        ];
    }

    /**
     * Debtors list page.
     */
    function actionDebtor() {
        $query = Profile::find()->where('balance < 0');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('debtor', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $statusArray = User::getStatusArrayStatic();

        return $this->render('index', compact(
            'searchModel',
            'dataProvider',
            'statusArray'
        ));
    }

    /**
     * Users list json.
     */
    function actionAffiliate($id = null)
    {
        $search = Yii::$app->request->post('search')['term'];
        $out = ['more' => false];
        if (!is_null($search)) {
            $query = new Query();
            $query->select(['user_id as id, CONCAT_WS(" ", surname, name, middle_name) AS text'])
                ->from('{{%profile}}')
                ->where('CONCAT_WS(" ", surname, name, middle_name) LIKE :search')
                ->params([':search' => '%' . $search . '%'])
                ->orderBy(['surname' => 'SORT_ASC', 'name' => 'SORT_ASC', 'middle_name' => 'SORT_ASC'])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Profile::findOne(['user_id' => $id])->getFullName()];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'Никого не найдено'];
        }
        echo Json::encode($out);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User();
        $profile = new Profile();


        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {

            if ($user->validate() && $profile->validate()) {
                $user->populateRelation('profile', $profile);
                if(!$this->createUser($user, $profile, false)){
                    Yii::$app->session->setFlash('danger', Yii::t('app', 'Could not create user'));
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'User created successfully'));
                }

                return $this->redirect(['view', 'id' => $user->id]);
            }

        }
            return $this->render('create', compact('user', 'profile'));
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $profile = Profile::findOne($id);

        if (!isset($user, $profile)) {
            throw new NotFoundHttpException("The user was not found.");
        }

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {

            if (!$user->validate()) {
                $errorValidation = 1;
            }
            if (!$profile->validate()) {
                $errorValidation = 1;
            }

            if (!$errorValidation) {
                $user->populateRelation('profile', $profile);
                if(!$user->save(false) || !$profile->save(false)){
                    Yii::$app->session->setFlash('danger', Yii::t('app', 'Could not update user'));
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'User updated successfully'));
                }

                return $this->redirect(['view', 'id' => $user->id]);
            }

        }

        return $this->render('update', compact('user', 'profile'));
    }

    /**
     * Deletes an existing User model.
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
     * Delete multiple User.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($id)
    {
        if (($model = User::find()->where(['id' => $id])->all()) !== null) {
            return $model;
        } else {
            throw new HttpException(404);
        }
    }

    /**
     * Create new user with transaction
     * @param User $user
     * @param Profile $profile
     * @param bool $validate
     * @return bool
     */
    public function createUser(User $user, Profile $profile, $validate = true){
        $transaction = Yii::$app->db->beginTransaction();
        if(!$user->save($validate)){
            $transaction->rollback();
            return false;
        }

        $profile->user_id = $user->id;
        if(!$profile->save($validate)){
            $transaction->rollback();
            return false;
        }
        $transaction->commit();
        return true;
    }
}
