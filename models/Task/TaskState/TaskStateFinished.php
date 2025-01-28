<?php

namespace app\models\Task\TaskState;

use app\models\Task\TaskStatus;

class TaskStateFinished implements TaskState
{
    public function canChangeStatus(int $newStatusId): bool
    {
        return false;
    }

    public function getStatusValue(): int
    {
        return TaskStatus::Finished->value;
    }
}