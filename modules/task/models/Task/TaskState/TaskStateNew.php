<?php

namespace Modules\Task\models\Task\TaskState;

use Modules\Task\models\Task\TaskStatus;

class TaskStateNew implements TaskState
{
    public function canChangeStatus(int $newStatusId): bool
    {
        return $newStatusId == TaskStatus::InProcess->value;
    }

    public function getStatus(): TaskStatus
    {
        return TaskStatus::New;
    }
}