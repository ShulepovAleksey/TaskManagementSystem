<?php

namespace tests\unit\models;

use Codeception\Test\Unit;
use Modules\Task\models\Task\Task;

class TaskTest extends Unit
{
    public function testTitleRequired()
    {
        $model = new Task();
        $model->title = '';
        $this->assertFalse($model->validate(['title']));
    }

    public function testTitleMaxLength()
    {
        $model = new Task();
        $model->title = '';
        $i = 0;
        while ($i < 201) {
            $model->title .= 'a';
            $i++;
        }
        $this->assertFalse($model->validate(['title']));
    }

}