<?php 
//include core
include "../../config/core.php";

//include group controller
include "../controllers/events_controller.php";

//user controller
include "../controllers/user_controller.php";

//make sure data has been sent
if(!isset($_POST))
{
    echo "FATAL ERROR";
    exit(1);
}


  //check if get request has been made
  if(!isset($_GET))
  {
     echo "Fatal Error";
     exit(1);
  }

 //error variables
 $error = "";


 //get data
 $group_id = url_decrypt($_GET['group']); //group id


 //get form data
 $event_title = trim($_POST['e_title']);// event title

 $event_desc = trim($_POST['e_desc']);//event description

 $event_date = trim($_POST['e_date']);//event date


 //Validation //
 if(empty($event_title))
 {
    $error = "Please enter an Event title/name";
 }

 if(empty($event_desc))
 {
    $error = "An event description must be provided";
 }

 if(empty($event_date))
 {
    $error = "An event date must be provided";
 }


 //validate date
 $sql_date = date('Y-m-d', strtotime($event_date)); // convert to SQL date format

 $current_date = date('Y-m-d');
 
 if($sql_date < $current_date)
 {
     $error = "Deadline has already passed";
 }


 if(empty($error))
 {
    //add event
    add_event_ctrl($group_id, $event_title, $event_desc, $event_date);
    echo 'Success';
    
 }
 else
 {
    echo "Error: " . $error;
 }





?>
