<?php

namespace components\Database\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

class TaskDB extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['timestampBehavior'] = [
            'class' => TimestampBehavior::class,
            'attributes' => [
                BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
            'value' => new Expression('NOW()'),
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task';
    }
}