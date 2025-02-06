<?php

namespace tests\unit\models;

use Codeception\Test\Unit;
use components\logic\TaskLogic;
use modules\task\models\Task\Task;
use modules\task\models\Task\TaskSearch;
use modules\task\models\Task\TaskState\TaskStateNew;
use modules\task\models\Task\TaskStatus;
use RuntimeException;

class TaskLogicTest extends Unit
{
    // TODO Для теста используется БД разработки и запись с ID = -1 (она не меняется, т.к. все операции идут в транзакциях и откатываются)
    CONST ID = -1;

    public function testCreateTask()
    {
        $taskLogic = TaskLogic::getInstance();
        $taskState = new TaskStateNew();
        $task = new Task($taskState);
        $task->title = 'Задача для теста';
        $task->description = 'А тут описание задачи для теста';
        verify($taskLogic->save($task));
    }

    public function testGetTask()
    {
        $taskLogic = TaskLogic::getInstance();
        verify($taskLogic->findModel(self::ID));
    }

    public function testUpdateTask()
    {
        $taskLogic = TaskLogic::getInstance();
        $task = $taskLogic->findModel(self::ID);
        $task->title = 'Изменим наименование';
        $task->description = 'И еще изменим описание';
        verify($taskLogic->save($task));
    }

    public function testDeleteTask()
    {
        $taskLogic = TaskLogic::getInstance();
        $taskLogic->delete(self::ID);
        try {
            $task = $taskLogic->findModel(self::ID);
        } catch (RuntimeException) {
            $task = null;
        }
        $this->assertNull($task);
    }

    public function testChangeStatusTask()
    {
        $taskLogic = TaskLogic::getInstance();
        $taskLogic->changeStatus(self::ID, TaskStatus::InProcess->value);
        $task = $taskLogic->findModel(self::ID);
        $this->assertEquals(TaskStatus::InProcess->value, $task->getTaskState()->getStatus()->value);
    }

    public function testSearch()
    {
        $taskLogic = TaskLogic::getInstance();
        $taskSearch = new TaskSearch();
        $provider = $taskLogic->search($taskSearch);
        $task = null;
        if (count($provider->getModels()) > 0) {
            $task = $provider->getModels()[0];
        }
        $this->assertNotNull($task);
    }
}