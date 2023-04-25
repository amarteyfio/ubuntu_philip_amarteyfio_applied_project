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

 include ('../controllers/group_controller.php');

 include ('../controllers/user_controller.php');

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


 //get data
 $group_id = url_decrypt($_GET['group']); //group id

 
$m_title = trim($_POST['m_title']); //meeting titles

$m_desc = trim($_POST['m_desc']); //meeting description

$m_type = trim($_POST['m_type']); //meeting type

$m_loc = trim($_POST['m_loc']); //meeting location

$members = all_members_ctrl($group_id);
//meeting start
$m_start = trim($_POST['m_start']); 
$m_str = strtotime($m_start);
$meet_start = date('Y-m-d H:i:s', $m_str);


//meeting end
$m_end = trim($_POST['m_end']);
$m_end_unix = strtotime($m_end);
$meet_end = date('Y-m-d H:i:s', $m_end_unix);


//validation

//title
if(empty($m_title))
{
    $error = "Please Enter a Meeting Title";
}

//desc
if(empty($m_desc))
{
    $error = "Please Enter a Meeting Description";
}
else
{
    if(strlen($m_desc) > 150)
    {
        $error = "Description can be a Maximum of 150 Characters";
    }
}


//type
if($m_type !== "In-Person" && $m_type !== "Online")
{
    $error = "Meeting Type Unrecognized";
}


//location
if(empty($m_loc))
{
    $error = "Provide a meeting location";
}


//meeting starting date
// Check if the meeting start and end dates are not empty
if (!empty($meet_start) && !empty($meet_end)) {
  
    // Get the current datetime
    $currentDateTime = date('Y-m-d H:i:s');
  
    // Check if the meeting start date is in the future
    if ($meet_start < $currentDateTime) {
      $error = 'The meeting start date has already passed.';
    }
  
    // Check if the meeting end date is not ahead of the start date
    if ($meet_end < $meet_start) {
      $error = 'The meeting end date cannot be before of the start date.';
      
    }
  
  } else {
    $error = 'Please provide both the meeting start and end dates.';
  }


  //if no errors
  if(empty($error))
  {
    //add meeting
    add_meeting_ctrl($group_id,$m_title,$m_desc,$m_type,$m_loc,$meet_start,$meet_end);

    //send meeting mail
    foreach($members as $m)
    {
      $info = select_user_ctrl($m['user_id']);

      $email = $info['email'];

      send_meeting_invitation_ctrl($email,$m_title,$m_desc,$meet_start,$meet_end,$m_loc);
    }

    //message
    echo "Success";

  }

  else
  {
    echo "Error:" . $error;
  }




 ?>