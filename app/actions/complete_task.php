<?php 
//include core
include "../../config/core.php";

//include task controller;
include "../controllers/task_controller.php";


if(!isset($_POST))
{
    echo "FATAL ERROR: INVALID POST";
    exit(1);
}

//error
$error = "";

//get data
$task_id = $_POST['id'];

//check if task has already been completed.
if(is_completed_ctrl($task_id) == true)
{
    $error = "Task has already completed!";   
}

//if no error

if(empty($error))
{
    $t_info = select_task_ctrl($task_id);

    //get task date
    $t_date = $t_info['deadline'];

// Convert the date to a Unix timestamp
$timestamp = strtotime($t_date);

// Get the current date in the same format as the database date
$currentDate = date('Y-m-d');

// Convert the current date to a Unix timestamp
$currentTimestamp = strtotime($currentDate);

// Check if the timestamp from the database is earlier than the current timestamp
if ($timestamp < $currentTimestamp)
 {
    set_task_status_ctrl($task_id, 2);
    echo "Success-Late";
 } 
else 
{
    set_task_status_ctrl($task_id,1);
    echo "Success";
}
}
else
{
    echo "Error :". $error;
}


?>