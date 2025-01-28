<?php

namespace Modules\Task\models\Task\TaskState;

use Modules\Task\models\Task\TaskStatus;

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