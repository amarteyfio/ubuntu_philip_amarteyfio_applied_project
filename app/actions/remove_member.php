<?php 
//include core
include ("../../config/core.php"); 

//include user class
require ("../controllers/user_controller.php");

//include group controller
require ("../controllers/group_controller.php");

//get the user_id and group_id

//member_id 
$member_id = url_decrypt(trim($_POST["member_id"]));

//group_id
$group_id = url_decrypt(trim($_GET["group"]));

//delete the user from the group
remove_user_ctrl($group_id,$member_id);

echo "success";



?>