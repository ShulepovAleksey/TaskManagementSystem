<?php

namespace components\Database;

use app\models\Task\Task;
use app\models\Task\TaskSearch;
use components\AbstractProvider;
use components\Database\models\TaskDB;
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
        $taskDB->status = $task->getTaskState()->getStatusValue();
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
                'status' => $taskSearch->getTaskState()->getStatusValue(),
                'created_at' => $taskSearch->created_at,
                'updated_at' => $taskSearch->updated_at,
            ]);

            $query->andFilterWhere(['ilike', 'title', $taskSearch->title])
                ->andFilterWhere(['ilike', 'description', $taskSearch->description]);
        }

        $models = [];
        foreach ($dataProvider->getModels() as $taskDB) {
            $models[] = new Task(null, $taskDB);
        }

        return new ArrayDataProvider(['allModels' => $models]);
    }
}