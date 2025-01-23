<?php

namespace app\controllers;

use app\models\Task\Task;
use app\models\Task\TaskSearch;
use app\models\Task\TaskState\TaskStateFinished;
use app\models\Task\TaskState\TaskStateInProcess;
use app\models\Task\TaskState\TaskStateNew;
use app\models\Task\TaskStatus;
use RuntimeException;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne(['id' => $id])) !== null) {
            $taskState = $this->getTaskStateByStatusId($model->status, $model);
            $model->changeStatus($taskState);

            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Изменение статуса
     *
     * @param int $id
     * @param int $newStatusId
     * @return Response
     * @throws RuntimeException
     * @throws Exception|NotFoundHttpException
     */
    public function actionChangeStatus(int $id, int $newStatusId): Response
    {
        $model = $this->findModel($id);

        if (!$model->canChangeStatus($newStatusId)) {
            throw new RuntimeException(
                "Нельзя изменить статус задачи ($id) на статус с идентификатором $newStatusId."
            );
        }

        $taskState = $this->getTaskStateByStatusId($newStatusId, $model);

        $model->changeStatus($taskState);
        if (!$model->save()) {
            throw new RuntimeException("Не удалось сохранить задачу с идентификатором $id.");
        };

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * @param int $newStatusId
     * @param Task $model
     * @return TaskStateFinished|TaskStateInProcess|TaskStateNew
     */
    private function getTaskStateByStatusId(int $newStatusId, Task $model): TaskStateNew|TaskStateFinished|TaskStateInProcess
    {
        return match ($newStatusId) {
            TaskStatus::New->value => new TaskStateNew($model),
            TaskStatus::InProcess->value => new TaskStateInProcess($model),
            TaskStatus::Finished->value => new TaskStateFinished($model),
            default => throw new RuntimeException("Не удалось найти статус с идентификатором $newStatusId."),
        };
    }
}
