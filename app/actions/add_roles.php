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

 $role_name = $_POST['role'];//role name

 if(role_exists_ctrl($role_name,$group_id))
 {
    $error = "Role already exists";
 }

 if(empty($role_name))
 {
    $error = "Role cannot be empty";
 }

 //if no errors
 if(empty($error))
 {
    //create role
    create_roles_ctrl($role_name,$group_id);
    echo "Success";
 }





 ?>