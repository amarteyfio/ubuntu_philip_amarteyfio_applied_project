<?php

/**
 * This file contains the controller functions for the user class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/user_class.php');


/* REGISTER USER CONTROLLER */

function register_user_ctrl($f_name,$l_name,$email,$password,$contact)
{
    
    // creates new instance of user class
    $user = new user_class();

    return $user->register_user($f_name,$l_name,$email,$password,$contact);
}



/* SELECT USER CONTROLLER */

function select_user_ctrl($id)
{

    // creates new instance of user class
    $user = new user_class();

    return $user->select_user($id);
}



/* UPDATE EMAIL CONTROLLER */

function update_email_ctrl($email,$id)
{

    // creates new instance of user class
    $user = new user_class();

    return $user->update_email($email,$id);
}


/* VERIFY USER CONTROLLER */
function verify_user_ctrl($email,$password)
{
    
    // creates new instance of user class
    $user = new user_class();

    return $user->verify_user($email,$password);
}



/* GET USER ID CONTROLLER */

function get_user_info_ctrl($email)
{
    //create new instance of user class
    $user = new user_class();

    return $user->get_user_info($email);
}



/* EMAIL EXISTS CONTROLLER */

function email_exists_ctrl($email)
{

    //creates new instance of user class
    $user = new user_class();

    return $user->email_exists($email);
}


    /**
     * This function is used to add a user invite
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_user_invite_ctrl($user_id,$token)
    {
        $user = new user_class();

        return $user->add_user_invite($user_id,$token);
    }


    /***
     * Function to select user invites
     * 
     * 
     * @author Philip Amarteyfio     
     */
    function select_user_invites_ctrl($user_id)
    {
        $user = new user_class();

        return $user->select_user_invites($user_id);
    }


    /**
     * Delete user invite
     * 
     * 
     * @author Philip Amarteyfio
     */
    function delete_user_invite_ctrl($user_id,$invite_id)
    {
        $user = new user_class();

        return $user->delete_user_invite($user_id,$invite_id);
    }
?>
