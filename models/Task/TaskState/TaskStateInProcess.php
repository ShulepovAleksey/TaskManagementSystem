<?php

namespace app\models\Task\TaskState;

use app\models\Task\Task;
use app\models\Task\TaskStatus;

class TaskStateInProcess implements TaskState
{
    public function canChangeStatus(int $newStatusId): bool
    {
        return $newStatusId === TaskStatus::Finished->value;
    }

    public function getStatusValue(): int
    {
        return TaskStatus::InProcess->value;
    }
}