<?php
//require db_configuration file
//set_include_path(dirname(__FILE__)."/../");
include_once __DIR__. '/../database/db_config.php';

//require vendor
require_once __DIR__ . "/../../vendor/autoload.php";

//require user_class
include __DIR__ . "/../classes/task_class.php";

use PHPUnit\Framework\TestCase;


class TaskClassTest extends TestCase
{
    private $task_class;

    protected function setUp(): void
    {
        $this->task_class = new task_class();
    }

    protected function tearDown(): void
    {
        $this->task_class = null;
    }


    public function testAddTask() : void
    {
        $group_id = 11;
        $task_title = "Task 1";
        $task_desc = "This is task 1";
        $deadline = "2023-04-01";
        $assigned_to = 32;
        
        $result = $this->task_class->add_task($group_id, $task_title, $task_desc, $deadline, $assigned_to);
        
        $this->assertTrue($result !== false);
    }

    public function testEditTask() : void
    {
        $task_id = $this->task_class->get_recent_task();
        $task_title = "Updated Task 1";
        $task_desc = "This is the updated task 1";
        $deadline = "2023-04-02";
        $assigned_to = 32;
        
        $result = $this->task_class->edit_task($task_id, $task_title, $task_desc, $deadline, $assigned_to);
        
        $this->assertTrue($result !== false);
    }
    
    public function testSetTaskStatus() : void
    {
        $task_id = $this->task_class->get_recent_task();
        $status = 1; // 1 for in completed, 
        
        $result = $this->task_class->set_task_status($task_id, $status);
        
        $this->assertTrue($result !== false);
    }

    public function testSelectGroupTasks() : void
    {
        $group_id = 11;
        
        $result = $this->task_class->select_group_tasks($group_id);
        
        $this->assertIsArray($result);
    }

    public function testIsCompleted() : void
    {
        $task_id = $this->task_class->get_recent_task();
        
        $result = $this->task_class->is_completed($task_id);
        
        $this->assertTrue($result !== false);
    }

    public function testSelectTask() : void
    {
        $task_id = $this->task_class->get_recent_task();
        
        $result = $this->task_class->select_task($task_id);
        
        $this->assertIsArray($result);
    }
    public function testDeleteTask() : void
    {
        $task_id = $this->task_class->get_recent_task();
        
        $result = $this->task_class->delete_task($task_id);
        
        $this->assertTrue($result !== false);
    }
}
