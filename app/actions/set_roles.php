<?php 
//include core
include "../../config/core.php";

//include group controller
include "../controllers/group_controller.php";

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
 $group_id = url_decrypt($_GET['group']);//group id

 $user_id = $_SESSION['id'];//user_id

 $role_id = $_POST['set'];//role name

 $for = url_decrypt($_POST['member_id']);

 if(check_assignment_ctrl($role_id,$group_id,$for))
 {
    $error = "Role already assigned";
 }

 //echo $role_id;
 if(empty($role_id))
 {
    $error = "Role cannot be empty";
 }

 //if no errors
 if(empty($error))
 {

    //create role
    set_roles_ctrl($for,$role_id,$group_id);
    echo "Success";
 }
 else
 {
    echo "Error". $error;
 }





 ?>