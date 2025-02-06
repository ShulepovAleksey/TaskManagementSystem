<?php

namespace modules\task\models\Task\TaskState;

use modules\task\models\Task\TaskStatus;

interface TaskState
{
    public function canChangeStatus(int $newStatusId): bool;

    public function getStatus(): TaskStatus;
}