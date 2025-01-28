<?php

namespace components;

use Modules\Task\models\Task\Task;
use Modules\Task\models\Task\TaskSearch;
use yii\data\DataProviderInterface;

interface AbstractProvider
{
    public function taskGetOne(int $id): ?Task;
    public function save(Task &$task): bool;
    public function delete(Task $task);
    public function search(TaskSearch $taskSearch): DataProviderInterface;
}