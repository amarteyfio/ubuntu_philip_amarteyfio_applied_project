<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing operations related to groups in the database.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */


 /*----------------------------------------------------/
                    TASK FUNCTIONS
 /----------------------------------------------------*/


 class task_class extends db_config
 {

    /**
     * This fucntion is used to  add a task to the group
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_task($group_id,$task_title,$task_desc,$deadline,$assigned_to)
    {
        $query = "INSERT INTO tasks (group_id,task_title,task_desc,deadline,assigned_to) VALUES (?,?,?,?,?)";

        $params = [$group_id,$task_title,$task_desc,$deadline,$assigned_to];

        $this->run_query($query,$params);

    }


    /**
     * This function is used to edit a task in the group
     * 
     * 
     * @author Philip Amarteyfio
     */
    function edit_task($task_id,$task_title,$task_desc,$deadline,$assigned_to)
    {
        $query = "UPDATE tasks SET task_title = ?,task_desc = ?,deadline = ?,assigned_to = ? WHERE task_id = ?";

        $params = [$task_title,$task_desc,$deadline,$assigned_to,$task_id];

        $this->run_query($query,$params);
    }

    /**
     * Function to delete a task 
     *
     * 
     * @author Philip Amarteyfio
     */

     function delete_task($task_id)
     {
        $query = "DELETE FROM tasks WHERE task_id = ?";

        $params = [$task_id];

        $this->run_query($query,$params);
     }


     /**
      * Function to set a task status 
      *
      * @author Philip 
      */

      function set_task_status($task_id,$status)
      {
         $query = "UPDATE tasks SET status = ? WHERE task_id = ?";

         $params = [$status,$task_id];

         $this->run_query($query,$params);
      }


      /**
       * Function select all tasks for a given group
       * 
       * 
       * @author Philip Amarteyfio
       */
      function select_group_tasks($group_id)
      {
         $query = "SELECT * FROM tasks WHERE group_id = ?";

         $param = [$group_id];

         return $this->db_fetch_all($query,$param);
      }


      /**
       * Function to check if task has already been completed.
       * 
       * 
       * @author Philip Amarteyfio
       */
      function is_completed($task_id)
      {
            $query = "SELECT * FROM tasks WHERE task_id = ? LIMIT 1";

            $param = [$task_id];

            $data = $this->db_fetch_one($query,$param);

            if($data['status'] == 1 || $data['status'] == 2)
            {
                return true;
            }
            else
            {
                return false;
            }
      }


      /**
       * Function to select a task
       * 
       * 
       * @author Philip Amarteyfio
       */
      function select_task($task_id)
      {
            $query = "SELECT * FROM tasks WHERE task_id = ?";

            $param = [$task_id];

            return $this->db_fetch_one($query,$param);
      }

      //FOR TESTING ONLY
      function get_recent_task()
      {
        $query = "SELECT * FROM tasks ORDER BY task_id DESC LIMIT 1";

        $params = [];

        $data = $this->db_fetch_one($query, $params);

        return $data['task_id'];
      }




 }