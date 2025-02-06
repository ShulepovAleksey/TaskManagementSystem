<?php

namespace modules\task\models\Task\TaskState;

use modules\task\models\Task\TaskStatus;

class TaskStateInProcess implements TaskState
{
    public function canChangeStatus(int $newStatusId): bool
    {
        return $newStatusId === TaskStatus::Finished->value;
    }

    public function getStatus(): TaskStatus
    {
        return TaskStatus::InProcess;
    }
}