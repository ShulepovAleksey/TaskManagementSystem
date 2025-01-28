<?php

use yii\db\Migration;

/**
 * Class m250128_082035_add_test_unit_task
 */
class m250128_082035_add_test_unit_task extends Migration // NOSONAR
{
    const TABLE_NAME = '{{%task}}';
    const ID = -1;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(self::TABLE_NAME, [
            'id' => self::ID,
            'title' => 'Задача для юнит-тестов',
            'description' => 'Не трогать!!!',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(self::TABLE_NAME, ['id' => self::ID]);
    }
}
