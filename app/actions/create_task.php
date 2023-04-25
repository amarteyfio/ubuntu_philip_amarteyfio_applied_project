<?php
/**
 * This code validates and creates a group 
 * 
 * @author Philip Amarteyfio
 */

 //include core
 include('../../config/core.php');

 //Require the group controller
 require_once('../controllers/group_controller.php');

 //Require the task controller
 require_once('../controllers/task_controller.php');

 //check if post request has been made
 if(!isset($_POST))
 {
    echo "Fatal Error";
    exit(1);
 }


  //check if post request has been made
  if(!isset($_GET))
  {
     echo "Fatal Error";
     exit(1);
  }

 //error variables
 $error = "";


 //get data
 $group_id = url_decrypt($_GET['group']); //group id
 

 $task_title = trim($_POST['task_name']); //task title

 $task_description = trim($_POST['t_desc']); //task description

 $deadline = $_POST['deadline']; //deadline 

 $assigned_to = url_decrypt($_POST['assigned_to']); //assigned to
 


 //Validate data
 if(empty($task_title))
 {
    $error = "Please enter a task title/name";
 }

 if(empty($task_description))
 {
    $error = "A task description must be provided";
 }

//deadline 
/*$date = DateTime::createFromFormat('mm/dd/yy', $deadline); // create DateTime object from deadline

if ($date !== false && !array_sum($date::getLastErrors()))
 {
  // valid date in mm/dd/yy format
  
 } 
else
 {
  // invalid date in mm/dd/yy format
  $error = "Deadline is not in the correct format." . $date;
}
*/

//check if deadline -s valid date
$sql_deadline = date('Y-m-d', strtotime($deadline)); // convert to SQL date format

$current_date = date('Y-m-d');

if($sql_deadline < $current_date)
{
    $error = "Deadline has already passed";
}

//check if user is a member of group
if(is_member($group_id,$assigned_to) == false)
{
   $error = "Task must be assigned to a group member";
}


//if no error
if(empty($error))
{
    add_task_ctrl($group_id,$task_title,$task_description,$sql_deadline,$assigned_to);
    echo "Success";
}
else
{
    echo "Error: " . $error;

    

    
}











?>