<?php

namespace modules\task\controllers;

use components\logic\TaskLogic;
use modules\task\models\Task\Task;
use modules\task\models\Task\TaskSearch;
use modules\task\models\Task\TaskState\TaskStateNew;
use RuntimeException;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Task models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $taskLogic = TaskLogic::getInstance();
        $dataProvider = $taskLogic->search($searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param int $id ID
     * @return string
     */
    public function actionView($id)
    {
        $taskLogic = TaskLogic::getInstance();
        return $this->render('view', [
            'model' => $taskLogic->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Task(new TaskStateNew());

        if ($this->request->isPost) {
            $taskLogic = TaskLogic::getInstance();
            if ($model->load($this->request->post()) && $taskLogic->save($model)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $taskLogic = TaskLogic::getInstance();
        $model = $taskLogic->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $taskLogic->save($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     */
    public function actionDelete($id)
    {
        $taskLogic = TaskLogic::getInstance();
        $taskLogic->delete($id);

        return $this->redirect(['index']);
    }

    /**
     * Изменение статуса
     *
     * @param int $id
     * @param int $newStatusId
     * @return Response
     * @throws RuntimeException
     */
    public function actionChangeStatus(int $id, int $newStatusId): Response
    {
        $taskLogic = TaskLogic::getInstance();
        $taskLogic->changeStatus($id, $newStatusId);

        return $this->redirect(['view', 'id' => $id]);
    }
}
