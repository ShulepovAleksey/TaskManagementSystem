<?php

namespace app\models\Task\TaskState;

use app\models\Task\Task;
use app\models\Task\TaskStatus;

class TaskStateNew implements TaskState
{
    public function canChangeStatus(int $newStatusId): bool
    {
        return $newStatusId == TaskStatus::InProcess->value;
    }

    public function getStatusValue(): int
    {
        return TaskStatus::New->value;
    }
}