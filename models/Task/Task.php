<?php

namespace models\Task;

use models\Task\TaskState\TaskState;
use yii\base\Model;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Task extends Model
{
    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    // TODO Этот статус лишний, достаточно $taskState. Убрать
    public ?string $status = null;
    private ?TaskState $taskState;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['status'], 'default', 'value' => TaskStatus::New->value],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Наименование',
            'description' => 'Описание',
            'status' => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

    public function __construct(TaskState $taskState = null, $config = [])
    {
        parent::__construct($config);
        $this->taskState = $taskState;
    }

    /**
     * Проверка на возможность изменения статуса
     *
     * @param int $newStatusId
     * @return bool
     */
    public function canChangeStatus(int $newStatusId): bool
    {
        return $this->taskState->canChangeStatus($newStatusId);
    }

    public function changeStatus(TaskState $taskState): void
    {
        $this->taskState = $taskState;
    }

    public function getTaskState(): ?TaskState
    {
        return $this->taskState;
    }
}
