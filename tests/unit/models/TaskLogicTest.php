<?php

namespace tests\unit\models;

use app\components\logic\TaskLogic;
use app\models\Task\Task;
use app\models\Task\TaskSearch;
use app\models\Task\TaskState\TaskStateNew;
use app\models\Task\TaskStatus;
use Codeception\Test\Unit;
use RuntimeException;

class TaskLogicTest extends Unit
{
    // TODO Для теста используется БД разработки и запись с ID = -1 (она не меняется, т.к. все операции идут в транзакциях и откатываются)
    CONST ID = -1;

    public function testCreateTask()
    {
        $taskState = new TaskStateNew();
        $task = new Task($taskState);
        $task->title = 'Задача для теста';
        $task->description = 'А тут описание задачи для теста';
        verify(TaskLogic::save($task));
    }

    public function testGetTask()
    {
        verify(TaskLogic::findModel(self::ID));
    }

    public function testUpdateTask()
    {
        $task = TaskLogic::findModel(self::ID);
        $task->title = 'Изменим наименование';
        $task->description = 'И еще изменим описание';
        verify(TaskLogic::save($task));
    }

    public function testDeleteTask()
    {
        TaskLogic::delete(self::ID);
        try {
            $task = TaskLogic::findModel(self::ID);
        } catch (RuntimeException) {
            $task = null;
        }
        $this->assertNull($task);
    }

    public function testChangeStatusTask()
    {
        TaskLogic::changeStatus(self::ID, TaskStatus::InProcess->value);
        $task = TaskLogic::findModel(self::ID);
        $this->assertEquals(TaskStatus::InProcess->value, $task->getTaskState()->getStatusValue());
    }

    public function testSearch()
    {
        $taskSearch = new TaskSearch();
        $provider = TaskLogic::search($taskSearch);
        $task = null;
        if (count($provider->getModels()) > 0) {
            $task = $provider->getModels()[0];
        }
        $this->assertNotNull($task);
    }
}