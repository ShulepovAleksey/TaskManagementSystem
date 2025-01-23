<?php

namespace components\Database\models;

use yii\db\ActiveRecord;

class TaskDB extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task';
    }
}