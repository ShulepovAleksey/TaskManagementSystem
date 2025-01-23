<?php

namespace app\models\Task\TaskState;

interface TaskState
{
    public function canChangeStatus(int $newStatusId): bool;

    public function getStatusValue(): int;
}