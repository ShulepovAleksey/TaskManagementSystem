<?php

namespace app\models\Task;

enum TaskStatus: int
{
    case New = 0;
    case InProcess = 1;
    case Finished = 2;

    public function getStatusName(): string
    {
        return match ($this) {
            TaskStatus::New => 'Новая',
            TaskStatus::InProcess => 'В процессе',
            TaskStatus::Finished => 'Завершена',
        };
    }

    /**
     * Поиск статуса по идентификатору
     * @param int $id
     * @return TaskStatus
     */
    public static function getStatusById(int $id): TaskStatus // TODO Переделать бы, отказавшись от подобного метода
    {
        return match ($id) {
            TaskStatus::New->value => TaskStatus::New,
            TaskStatus::InProcess->value => TaskStatus::InProcess,
            TaskStatus::Finished->value => TaskStatus::Finished,
            default => throw new \RuntimeException("Не найден статус с идентификатором $id"),
        };
    }
}
