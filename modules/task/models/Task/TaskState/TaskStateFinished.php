<?php

namespace modules\task\models\Task\TaskState;

use modules\task\models\Task\TaskStatus;

class TaskStateFinished implements TaskState
{
    public function canChangeStatus(int $newStatusId): bool
    {
        return false;
    }

    public function getStatus(): TaskStatus
    {
        return TaskStatus::Finished;
    }
}