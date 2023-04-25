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

//get user id
$user_id = $_SESSION['id'];



//get invite information from token
$invite = select_invite_with_token_ctrl($token);



//check if invite/token is valid
if(!empty($invite))
{
    //get group id from invite
    $group_id = $invite['group_id'];

    //check if invite is valid
    $expiry = $invite['expires_at'];
    $current_datetime = date('Y-m-d H:i:s');

    if($expiry < $current_datetime)
    {

        echo "Invite has expired";
    }
    else{
    //check if already a member of group
    if(is_member($group_id,$user_id))
    {
        echo "You are already a member of this group";

        //change invite status
        change_invite_status_ctrl($invite['invite_id'],1);

        //delete user invite
        delete_user_invite_ctrl($user_id,$invite['invite_id']);


    }
    else
    {
        //add user to group
        add_user_group_ctrl($user_id,$group_id);

        //change invite status
        change_invite_status_ctrl($invite['invite_id'],1);

        //delete user invite
        delete_user_invite_ctrl($user_id,$invite['invite_id']);

        echo "Success";
    }
}


}
else
{
    echo "Error: No Invite found.";
}
?>