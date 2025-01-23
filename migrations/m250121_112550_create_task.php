<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m250121_112550_create_task
 */
class m250121_112550_create_task extends Migration
{
    const TABLE_NAME = '{{%task}}';
    const STATUS_DEFAULT = 0;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'title' => $this->string(200)->notNull(),
            'description' => $this->text(),
            'status' => $this->integer()->notNull()->defaultValue(self::STATUS_DEFAULT),
            'created_at' => $this->dateTime()->notNull()->defaultValue(new Expression('NOW()')),
            'updated_at' => $this->dateTime()->notNull()->defaultValue(new Expression('NOW()')),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
