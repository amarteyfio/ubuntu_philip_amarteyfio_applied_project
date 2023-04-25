<?php
/**
 * This code validates and creates a group 
 * 
 * @author Philip Amarteyfio
 */

 //include core
 include('../../config/core.php');

 //Require the meeting controller
 require_once('../controllers/meetings_controller.php');

 date_default_timezone_set('UTC');

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
 
  //user_id 
  $user_id = $_SESSION["id"];

  //group_id
  $group_id = url_decrypt($_GET["group"]);


  //meeting_id
  $meeting_id = url_decrypt($_POST['meeting_id']); 

  //get meeting_details
  $meeting = select_meeting_ctrl($meeting_id);

  $meet_end = $meeting['ends_at'];

  $meet_start = $meeting['begins_at'];

  $current_time = date('Y-m-d H:i:s');  

  if($meet_end < $current_time)
  {
    $error = "Meeting Ended Already";
  }

  


  //Check if its more than 10 minutes from meting date
  
  $now = strtotime($current_time);
  $meeting_start_time = strtotime($meet_start);
  $meeting_end_time = strtotime($meet_end);

  if($now - $meeting_start_time > 900)
  {
    $error = "You are late";
  }

  if(empty($error))
  {
    $time_diff = $now - $meeting_start_time;
    if($time_diff >= 0 && $time_diff <= 600)
    {
        add_attendance_ctrl($meeting_id,$user_id,$group_id,0);
        echo "Success";
    }
    elseif($time_diff >= 600 && $time_diff <= 900)
    {
        add_attendance_ctrl($meeting_id,$user_id,$group_id,1);
        echo "Success";
    }
    
  }
  else
  {
    echo "Error: ". $error;
  }

?>