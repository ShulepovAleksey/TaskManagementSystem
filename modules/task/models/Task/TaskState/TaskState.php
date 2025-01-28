<?php

namespace Modules\Task\models\Task\TaskState;

use Modules\Task\models\Task\TaskStatus;

interface TaskState
{
    public function canChangeStatus(int $newStatusId): bool;

    public function getStatus(): TaskStatus;
}