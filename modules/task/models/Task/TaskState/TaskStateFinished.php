<?php

namespace Modules\Task\models\Task\TaskState;

use Modules\Task\models\Task\TaskStatus;

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