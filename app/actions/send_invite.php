<?php 
//include core
include ("../../config/core.php"); 

//include user class
require ("../controllers/user_controller.php");

//include group controller
require ("../controllers/group_controller.php");


//get group id 
$group_id = $_GET['group'];

//decrypt
$group_id = url_decrypt($group_id);

//get email address
$email = trim($_POST['email']);

//token
$token = generate_token();



//error
$error = "";

//current date
$current_date = new DateTime();

//expires a week later
$expiry = $current_date->add(new DateInterval('P1W'));

//format for database insertion
$expires_at = $expiry->format('Y-m-d H:i:s');


//VALIDATION////

//validate email address
if(empty($email))
{
    $error = "Enter an Email Address";
}

    //check if email is valid
    if(email_exists_ctrl($email) == false)
    {
    $error = "Email Address does not exist on Ubuntu Platform";
    }

//check if email format is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
    $error = "Invalid email format";
    }

    //***********CHECK IF INVITEE IS ALREADY IN GROUP ****************//

    //get id 
    $invitee_id = get_user_info_ctrl($email);

    if(is_member($group_id, $invitee_id) == true)
    {
        $error = "ALREADY A MEMBER OF THIS GROUP";
    }
    
//send invite
if(empty($error))
{
    //create invite
    create_invite_ctrl($group_id,$email,$token,$expires_at);

    //get recepient id
    $rep_info = get_user_info_ctrl($email);

    //recepient id
    $rep_id = $rep_info['id'];

    //add invite to user_invite table
    add_user_invite_ctrl($rep_id,$token);

    //indicate completion
    echo "Success";



}
else
{
    echo "Error: " . $error;
}











?>