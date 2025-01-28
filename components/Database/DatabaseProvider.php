<?php

namespace components\Database;

use models\Task\Task;
use models\Task\TaskSearch;
use components\AbstractProvider;
use components\Database\models\TaskDB;
use models\Task\TaskState\TaskState;
use models\Task\TaskState\TaskStateFinished;
use models\Task\TaskState\TaskStateInProcess;
use models\Task\TaskState\TaskStateNew;
use models\Task\TaskStatus;
use RuntimeException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;
use yii\db\Exception;
use yii\db\Expression;

class DatabaseProvider implements AbstractProvider
{
    public function taskGetOne(int $id): ?Task
    {
        $taskDB = TaskDB::findOne($id);
        if (!is_null($taskDB)) {
            return new Task(null, $taskDB);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function save(Task &$task): bool
    {
        $taskDB = new TaskDB();
        if (!is_null($task->id)) {
            $taskDB->id = $task->id;
            $taskDB->isNewRecord = false;
        } else {
            $taskDB->created_at = new Expression('NOW()');
        }
        $taskDB->title = $task->title;
        $taskDB->description = $task->description;
        $taskDB->status = $task->getTaskState()?->getStatus()->value;
        $taskDB->updated_at = new Expression('NOW()');

        $result = $taskDB->save();
        if ($result) {
            $task->id = $taskDB->id;
        }
        return $result;
    }

    public function delete(Task $task)
    {
        $taskDB = new TaskDB();
        $taskDB->setOldAttribute('id', $task->id);
        $taskDB->delete();
    }

    public function search(TaskSearch $taskSearch): DataProviderInterface
    {
        $query = TaskDB::find()->orderBy(['updated_at' => SORT_DESC]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($taskSearch->validate()) {
            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $taskSearch->id,
                'status' => $taskSearch->getTaskState()?->getStatus()->value,
                'created_at' => $taskSearch->created_at,
                'updated_at' => $taskSearch->updated_at,
            ]);

            $query->andFilterWhere(['ilike', 'title', $taskSearch->title])
                ->andFilterWhere(['ilike', 'description', $taskSearch->description]);
        }

        $models = [];
        foreach ($dataProvider->getModels() as $taskDB) {
            $taskState = DatabaseProvider::getTaskStateByStatusId($taskDB->status);
            $models[] = new Task($taskState, $taskDB);
        }

        return new ArrayDataProvider(['allModels' => $models]);
    }

    /**
     * @param int $statusId
     * @return TaskState
     */
    private static function getTaskStateByStatusId(int $statusId): TaskState
    {
        return match ($statusId) {
            TaskStatus::New->value => new TaskStateNew(),
            TaskStatus::InProcess->value => new TaskStateInProcess(),
            TaskStatus::Finished->value => new TaskStateFinished(),
            default => throw new RuntimeException("Не удалось найти статус с идентификатором $statusId."),
        };
    }
}