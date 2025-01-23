<?php

namespace app\components\logic;

use app\models\Task\Task;
use app\models\Task\TaskSearch;
use app\models\Task\TaskState\TaskState;
use app\models\Task\TaskState\TaskStateFinished;
use app\models\Task\TaskState\TaskStateInProcess;
use app\models\Task\TaskState\TaskStateNew;
use app\models\Task\TaskStatus;
use components\AbstractProvider;
use components\Database\DatabaseProvider;
use http\Exception\RuntimeException;
use yii\data\DataProviderInterface;

class TaskLogic
{
    /**
     * @param int $id
     * @return Task
     */
    public static function findModel(int $id): Task
    {
        $provider = TaskLogic::getProvider();

        $model = $provider->taskGetOne($id);
        if (!is_null($model)) {
            $taskState = TaskLogic::getTaskStateByStatusId($model->status);
            $model->changeStatus($taskState);

            return $model;
        }

        throw new RuntimeException("Не удалось найти задачу с идентификатором $id");
    }

    public static function save(Task &$task): bool
    {
        $provider = TaskLogic::getProvider();
        return $provider->save($task);
    }

    public static function delete(int $id): void
    {
        $provider = TaskLogic::getProvider();
        $task = TaskLogic::findModel($id);
        $provider->delete($task);
    }

    public static function changeStatus(int $id, int $newStatusId): void
    {
        $provider = TaskLogic::getProvider();
        $task = TaskLogic::findModel($id);

        if (!$task->canChangeStatus($newStatusId)) {
            throw new RuntimeException(
                "Нельзя изменить статус задачи ($id) на статус с идентификатором $newStatusId."
            );
        }

        $taskState = TaskLogic::getTaskStateByStatusId($newStatusId);

        $task->changeStatus($taskState);
        if (!$provider->save($task)) {
            throw new RuntimeException("Не удалось сохранить задачу с идентификатором $id.");
        };
    }

    /**
     * @param TaskSearch $taskSearch
     * @return DataProviderInterface
     */
    public static function search(TaskSearch $taskSearch): DataProviderInterface
    {
        $provider = TaskLogic::getProvider();
        return $provider->search($taskSearch);
    }

    /**
     * @param int $newStatusId
     * @return TaskState
     */
    private static function getTaskStateByStatusId(int $newStatusId): TaskState
    {
        return match ($newStatusId) {
            TaskStatus::New->value => new TaskStateNew(),
            TaskStatus::InProcess->value => new TaskStateInProcess(),
            TaskStatus::Finished->value => new TaskStateFinished(),
            default => throw new RuntimeException("Не удалось найти статус с идентификатором $newStatusId."),
        };
    }

    // TODO Избавиться от метода и зависимости от конкретной реализации, сделать через конфигурацию
    private static function getProvider(): AbstractProvider
    {
        return new DatabaseProvider();
    }
}