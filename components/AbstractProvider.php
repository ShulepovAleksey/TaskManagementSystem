<?php

namespace components;

use app\models\Task\Task;
use app\models\Task\TaskSearch;
use yii\data\DataProviderInterface;

interface AbstractProvider
{
    public function taskGetOne(int $id): ?Task;
    public function save(Task &$task): bool;
    public function delete(Task $task);
    public function search(TaskSearch $taskSearch): DataProviderInterface;
}