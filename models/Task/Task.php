<?php

namespace app\models\Task;

use app\models\Task\TaskState\TaskState;
use yii\db\ActiveRecord;

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
class Task extends ActiveRecord
{
    private ?TaskState $taskState = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

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
        $this->status = $this->taskState->getStatusValue();
    }
}
