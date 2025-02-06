<?php

namespace components\logic;

use components\AbstractProvider;
use modules\task\models\Task\Task;
use modules\task\models\Task\TaskSearch;
use modules\task\models\Task\TaskState\TaskState;
use modules\task\models\Task\TaskState\TaskStateFinished;
use modules\task\models\Task\TaskState\TaskStateInProcess;
use modules\task\models\Task\TaskState\TaskStateNew;
use modules\task\models\Task\TaskStatus;
use RuntimeException;
use Yii;
use yii\data\DataProviderInterface;

class TaskLogic
{
    private static TaskLogic $instance;
    private AbstractProvider $taskProvider;
    
    /**
     * @param int $id
     * @return Task
     */
    public function findModel(int $id): Task
    {
        $model = $this->taskProvider->taskGetOne($id);
        if (!is_null($model)) {
            $taskState = $this->getTaskStateByStatusId($model->status);
            $model->changeStatus($taskState);

            return $model;
        }

        throw new RuntimeException("Не удалось найти задачу с идентификатором $id");
    }

    public function save(Task &$task): bool
    {
        return $this->taskProvider->save($task);
    }

    public function delete(int $id): void
    {
        $task = $this->findModel($id);
        $this->taskProvider->delete($task);
    }

    public function changeStatus(int $id, int $newStatusId): void
    {
        $task = $this->findModel($id);

        if (!$task->canChangeStatus($newStatusId)) {
            throw new RuntimeException(
                "Нельзя изменить статус задачи ($id) на статус с идентификатором $newStatusId."
            );
        }

        $taskState = $this->getTaskStateByStatusId($newStatusId);

        $task->changeStatus($taskState);
        if (!$this->taskProvider->save($task)) {
            throw new RuntimeException("Не удалось сохранить задачу с идентификатором $id.");
        }
    }

    /**
     * @param TaskSearch $taskSearch
     * @return DataProviderInterface
     */
    public function search(TaskSearch $taskSearch): DataProviderInterface
    {
        return $this->taskProvider->search($taskSearch);
    }

    /**
     * @param int $newStatusId
     * @return TaskState
     */
    private function getTaskStateByStatusId(int $newStatusId): TaskState
    {
        return match ($newStatusId) {
            TaskStatus::New->value => new TaskStateNew(),
            TaskStatus::InProcess->value => new TaskStateInProcess(),
            TaskStatus::Finished->value => new TaskStateFinished(),
            default => throw new RuntimeException("Не удалось найти статус с идентификатором $newStatusId."),
        };
    }

    /**
     * @return TaskLogic
     */
    public static function getInstance(): TaskLogic
    {
        if (!isset($instance)) {
            TaskLogic::$instance = new TaskLogic(Yii::$app->DataProvider);
        }
        
        return TaskLogic::$instance;
    }

    /**
     * @param AbstractProvider $taskProvider
     */
    private function __construct(AbstractProvider $taskProvider)
    {
        $this->taskProvider = $taskProvider;
    }
}