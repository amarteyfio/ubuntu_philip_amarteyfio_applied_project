<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing operations related to activity in the database.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */


 /*----------------------------------------------------/
                    EVENT FUNCTIONS
 /----------------------------------------------------*/


 class activity_class extends db_config
 {

    /**
     * Function to select all user tasks
     * 
     * 
     * @author Philip Amarteyfio
     */
    function get_user_tasks($group_id, $user_id)
    {
        $query = "SELECT * FROM tasks WHERE group_id = ? AND assigned_to = ? AND status = 0" ;

        $params = [$group_id,$user_id];

        return $this->db_fetch_all($query,$params);
    }

 }


 ?>