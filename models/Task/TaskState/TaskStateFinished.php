<?php

namespace models\Task\TaskState;

use models\Task\TaskStatus;

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