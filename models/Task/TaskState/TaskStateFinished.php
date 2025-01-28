<?php

namespace models\Task\TaskState;

use models\Task\TaskStatus;

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