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
}


//get token
$token = trim($_POST['token']);

//user id
$user_id = $_SESSION['id'];




//get invite information from token
$invite = select_invite_with_token_ctrl($token);



//check if invite/token is valid
if(!empty($invite))
{
    //change invite status
    change_invite_status_ctrl($invite['invite_id'],2);

    //delete user invite
    delete_user_invite_ctrl($user_id,$invite['invite_id']);

    echo "Success";
}
else
{
    echo "Error: No Invite found.";
}
?>