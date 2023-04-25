<?php

/**
 * This file contains the controller functions for the events class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/activity_class.php');


/**
 * Function to get user tasks information
 * 
 * 
 * @author Philip Amarteyfio
 */
function get_user_tasks_ctrl($group_id,$user_id)
{
    $activity = new activity_class();

    return $activity->get_user_tasks($group_id,$user_id);
}

?>

