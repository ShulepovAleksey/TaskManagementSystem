<?php

namespace tests\unit\models;

use app\components\logic\TaskLogic;
use app\models\Task\Task;
use app\models\Task\TaskState\TaskStateNew;
use Codeception\Test\Unit;
use RuntimeException;

class TaskLogicTest extends Unit
{
    // TODO Для теста используется БД разработки и запись с ID = 1 (она не меняется, т.к. все операции идут в транзакциях и откатываются)
    private int $taskId = 1;

    public function testCreateTask()
    {
        $taskState = new TaskStateNew();
        $task = new Task($taskState);
        $task->title = 'Задача для теста';
        $task->description = 'А тут описание задачи для теста';
        verify(TaskLogic::save($task));
        $this->taskId = $task->id;
    }

    public function testGetTask()
    {
        verify(TaskLogic::findModel($this->taskId));
    }

    public function testUpdateTask()
    {
        $task = TaskLogic::findModel($this->taskId);
        $task->title = 'Изменим наименование';
        $task->description = 'И еще изменим описание';
        verify(TaskLogic::save($task));
    }

    public function testDeleteTask()
    {
        TaskLogic::delete($this->taskId);
        try {
            $task = TaskLogic::findModel($this->taskId);
        } catch (RuntimeException) {
            $task = null;
        }
        $this->assertNull($task);
    }
}