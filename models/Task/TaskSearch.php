<?php

namespace app\models\Task;

/**
 * TaskSearch represents the model behind the search form of `app\models\Task\Task`.
 */
class TaskSearch extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at'], 'safe'],
        ];
    }
}
