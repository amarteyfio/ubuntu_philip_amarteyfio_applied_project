<?php

/**
 * This file contains the controller functions for the user class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/task_class.php');


/**
     * This controller is used to  add a task to the group
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_task_ctrl($group_id,$task_title,$task_desc,$deadline,$assigned_to)
    {
        $tasks = new task_class();

        return $tasks->add_task($group_id,$task_title,$task_desc,$deadline,$assigned_to);
    }


    /**
     * This controller is used to edit a task in the group
     * 
     * 
     * @author Philip Amarteyfio
     */
    function edit_task_ctrl($task_id,$task_title,$task_desc,$deadline,$assigned_to)
    {

        $tasks = new task_class();

        return $tasks->edit_task($task_id,$task_title,$task_desc,$deadline,$assigned_to);
        
    }

    /**
     * Controller to delete a task 
     *
     * 
     * @author Philip Amarteyfio
     */

     function delete_task_ctrl($task_id)
     {
        $tasks = new task_class();

        return $tasks->delete_task($task_id);
     }


     /**
      * Controller to set a task status 
      *
      * @author Philip 
      */

      function set_task_status_ctrl($task_id,$status)
      {
        $tasks = new task_class();

        return $tasks->set_task_status($task_id,$status);
      }

    
      /**
       * Function select all tasks for a given group
       * 
       * 
       * @author Philip Amarteyfio
       */
      function select_group_tasks_ctrl($group_id)
      {
        $tasks = new task_class();

        return $tasks->select_group_tasks($group_id);
      }


      /**
       * Function to check if task has already been completed.
       * 
       * 
       * @author Philip Amarteyfio
       */
      function is_completed_ctrl($task_id)
      {
         $tasks = new task_class();

         return $tasks->is_completed($task_id);
      }


      /**
       * Controller function to select a task
       * 
       * 
       */
      function select_task_ctrl($task_id)
      {
        $tasks = new task_class();

        return $tasks->select_task($task_id);
      }
?>