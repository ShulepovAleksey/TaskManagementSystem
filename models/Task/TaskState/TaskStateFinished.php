<?php

namespace app\models\Task\TaskState;

use app\models\Task\Task;
use app\models\Task\TaskStatus;

class TaskStateFinished implements TaskState
{
    private Task $context;

    public function __construct(Task $context)
    {
        $this->context = $context;
    }

    public function canChangeStatus(int $newStatusId): bool
    {
        return false;
    }

    public function getStatusValue(): int
    {
        return TaskStatus::Finished->value;
    }
}