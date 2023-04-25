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

 //check if group name and description variable exist
 if(!isset($_POST['g_name']) || !isset($_POST['g_desc']))
 {
    header("Location: ../error/404.php");
 }

 //user_id
 $user_id = $_SESSION['id'];

 //error variables
 $g_name_err = "";
 $g_desc_err = "";

 //get group name and description
 $g_name = trim($_POST['g_name']);
 $g_desc = trim($_POST['g_desc']);

//validate group name
 if(empty($g_name))
 {
    $g_name_err = "Please Enter a Group Name";
 }
 else
 {
    if(group_exists_ctrl($g_name) == true)
    {
        $g_name_err = "Group name already exists";
    }
 }

 //validate group description
 if(empty($g_desc))
 {
    $g_desc_err = "Please enter a Group Description";
 }

 if(strlen($g_desc) > 150)
 {
    $g_desc_err = "Your Description must be at most 150 characters";
 }

 if(empty($g_name_err) && empty($g_desc_err))
 {
    //create group
    add_group_ctrl($g_name,$g_desc);

    //get the ID of the group
    $g_id = get_group_id_ctrl($g_name);

    //add to user groups
    add_user_group_ctrl($user_id,$g_id);

    //add creator as administrator
    add_group_admin_ctrl($user_id,$g_id);

    echo "Success";
 }
 else
 {
    echo "Error: <br> 1.". $g_name_err ."<br>". "2." . $g_desc_err;
 }






?>