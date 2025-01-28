<?php

namespace models\Task\TaskState;

use models\Task\TaskStatus;

interface TaskState
{
    public function canChangeStatus(int $newStatusId): bool;

    public function getStatus(): TaskStatus;
}