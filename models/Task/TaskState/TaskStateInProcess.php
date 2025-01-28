<?php

namespace models\Task\TaskState;

use models\Task\TaskStatus;

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