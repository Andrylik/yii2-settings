<?php

namespace andrylik\settings\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use andrylik\settings\models\SettingModel;


/**
 * Class SettingController
 *
 * @package andrylik\settings\controllers
 */
class DefaultController extends Controller
{
    /**
     * @var string path to index view file, which is used in admin panel
     */
    public $indexView = '@vendor/andrylik/yii2-settings/views/settings/index';

    /**
     * @var string path to create view file, which is used in admin panel
     */
    public $createView = '@vendor/andrylik/yii2-settings/views/settings/create';

    /**
     * @var string path to update view file, which is used in admin panel
     */
    public $updateView = '@vendor/andrylik/yii2-settings/views/settings/update';

    /**
     * @var string search class name for searching
     */
    public $searchClass = 'andrylik\settings\models\SettingSearch';

    /**
     * @var string model class name for CRUD operations
     */
    public $modelClass = 'andrylik\settings\models\SettingModel';

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Settings.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject($this->searchClass);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($this->indexView, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Setting.
     *
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::createObject($this->modelClass);

        if(Yii::$app->request->post('SettingTranslation')){          
            foreach (Yii::$app->request->post('SettingTranslation', []) as $language => $data) {
                foreach ($data as $attribute => $translation) {
                    $model->translate($language)->$attribute = $translation;
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('andrylik.settings', 'Setting has been created.'));
            return $this->redirect(['index']);
        } else {
            return $this->render($this->createView, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting.
     *
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);        

        if(Yii::$app->request->post('SettingTranslation')){          
            foreach (Yii::$app->request->post('SettingTranslation', []) as $language => $data) {
                foreach ($data as $attribute => $translation) {
                    $model->translate($language)->$attribute = $translation;
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('andrylik.settings', 'Setting has been updated.'));
            return $this->redirect(['index']);
        } else {
            return $this->render($this->updateView, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Setting.
     *
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('andrylik.settings', 'Setting has been deleted.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds a Setting model based on its primary key value.
     *
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return SettingModel the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        $settingModelClass = $this->modelClass;

        if (($model = $settingModelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('andrylik.settings', 'The requested page does not exist.'));
        }
    }
}
