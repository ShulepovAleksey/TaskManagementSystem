<?php

namespace components;

use modules\task\models\Task\Task;
use modules\task\models\Task\TaskSearch;
use yii\data\DataProviderInterface;

interface AbstractProvider
{
    public function taskGetOne(int $id): ?Task;
    public function save(Task &$task): bool;
    public function delete(Task $task);
    public function search(TaskSearch $taskSearch): DataProviderInterface;
}