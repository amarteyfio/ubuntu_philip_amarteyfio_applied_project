<?php 
//include core
include "../../config/core.php";

//user controller
include "../controllers/assessment_controller.php";

date_default_timezone_set('UTC');

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
 $group_id = url_decrypt($_GET["group"]); //group id

 $assessment_date = trim($_POST['assessment_date']); //assessment date

 //validation
 if(empty($assessment_date))
 {
    $error = "Invalid assessment date";
 }

 $assessment_date = date("Y-m-d", strtotime($assessment_date)); //converrt to sql format

 $current_time  = date("Y-m-d");

 //check if current has already passed
 if($current_time > $assessment_date)
 {
    $error = "Assessment date has already passed";
 }

if(has_assessment_begun_ctrl($group_id))
{
    $error = "Cannot change date while assessment has begun";            
}

 if(empty($error))
 {
    $assessment_end = date("Y-m-d", strtotime("+1 week", strtotime($assessment_date))); //set assessment date to 1 week later
    
    //check if assesement date has already been set
    if(check_assessment_date_ctrl($group_id) == true)
    {
        
        set_assessment_date_ctrl($group_id,$assessment_date,$assessment_end);
        echo "Success";
    }
    else
    {
        add_assessment_date_ctrl($group_id, $assessment_date,$assessment_end);//set assessment date
        echo "Success";
    }
    

 }
 else
 {
    echo "Error: ".$error;
 }

 

?>