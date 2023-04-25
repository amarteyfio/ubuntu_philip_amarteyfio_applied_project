<?php

/**
 * This file contains the controller functions for the group class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/group_class.php');





/*----------------------------------------------------/
                GROUP FUNCTIONS CONTROLLER
 /----------------------------------------------------*/


 /**
  * This function acts as a controller for the add group function
  *
  * @author Philip Amarteyfio
  */

  function add_group_ctrl($group_name,$group_desc)
  {
    $group = new group_class();

    return $group->add_group($group_name,$group_desc);
  }


  /**
   * This function acts as a controller for the select user groups functions
   * 
   * @author Philip Amarteyfio
   */
  function user_groups_ctrl($user_id)
  {
    $group = new group_class();

    $records = $group->user_groups($user_id);

    return $records;
  }

  /**
   * This function acts as a controller for the select group function
   * 
   * @author Philip Amarteyfio
   * 
   */
   function select_group_ctrl($group_id)
   {
    $group = new group_class();

    return $group->select_group($group_id);
    
   }

   /**
    * This function acts as a controller for the count group members functions
    *
    *
    * @author Philip Amarteyfio
    *
    */
    function count_group_ctrl($group_id)
    {
      $group = new group_class();

      return $group->count_group($group_id);
    }

    /**
     * Function acts as  a controller to add user to user groups 
     * 
     * @author Philip Amarteyfio
     */
    function add_user_group_ctrl($user_id,$group_id)
    {
      $group = new group_class();

      return $group->add_user_group($user_id,$group_id);
    }
    
    /**
     * Acts as a controller for function to add group creator to group admins
     * 
     * @author Philip Amarteyfio
     */
    function add_group_admin_ctrl($user_id,$group_id)
    {
      $group = new group_class();

      return $group->add_group_admin($user_id,$group_id);
    }

    /**
     * Acts as a controller for function  to Check if a Group Name already Exists
     * 
     * 
     * @author Philip Amarteyfio
     */
    function group_exists_ctrl($group_name)
    {
      $group = new group_class();

      return $group->group_exists($group_name);
    }

    /**
     * Acts as a controller for function to get ID of Inserted Group
     * 
     * @author Philip Amarteyfio
     */

     function get_group_id_ctrl($group_name)
     {
       $group = new group_class();

       return $group->get_group_id($group_name);
     }


     /**
      *Function to check is a user is a member of a group 
      *
      * @author Philip Amarteyfio
      */
      function is_member($group_id,$user_id)
      {
        $group = new group_class();

        return $group->is_member($group_id,$user_id);
      }

      /**
     * Function to check if a user is a group admin or not
     * 
     * 
     * @author Philip Amarteyfio
     */

    function is_admin_ctrl($group_id,$user_id)
    {
       $group = new group_class();

       return $group->is_admin($group_id,$user_id);
    }


    /**
     * Function to select all members of a group
     * 
     * @author Philip Amarteyfio
     */

     function all_members_ctrl($group_id)
     {
       $group = new group_class();

       return $group->all_members($group_id);
     }


     /**
      * Function to create an invite to the group 
      *
      *
      * @author Philip Amarteyfio
      */
      function create_invite_ctrl($group_id,$email_to,$token,$expires_at)
      {
        $group = new group_class();

        return $group->create_invite($group_id,$email_to,$token,$expires_at);
      }

      /**
       * Function to select an invite by ID
       * 
       * 
       * @author Philip Amarteyfio
       */
      function select_invite_ctrl($invite_id)
      {
        $group = new group_class();

        return $group->select_invite($invite_id);
      }


      /**
       * Function to select an invite by token
       * 
       * 
       * @author Philip Amarteyfio
       */
      function select_invite_with_token_ctrl($token)
      {
         $group = new group_class();

         return $group->select_invite_with_token($token);
      }
      
    /**
     * Function to change invite status
     * 
     * 
     * @author Philip Amarteyfio
     */
    function change_invite_status_ctrl($invite_id,$status)
    {
      $group = new group_class();

      return $group->change_invite_status($invite_id,$status);
    }


    /**
     * Function Remove user from group
     * 
     * 
     * @author Philip Amarteyfio
     */
    function remove_user_ctrl($group_id,$user_id)
    {
       $group = new group_class();

       return $group->remove_user($group_id,$user_id);
    }


    /**
     * Function to create roles
     * 
     * 
     * 
     * @author Philip Amarteyfio
     */
    function create_roles_ctrl($role_name,$group_id)
    {
        $group = new group_class();

        return $group->create_roles($role_name,$group_id);
    }


    /**
     * Function to set roles
     * 
     * 
     * 
     * @author Philip Amarteyfio
     */
    function set_roles_ctrl($user_id,$role_id,$group_id)
    {
        $group = new group_class();

        return $group->set_roles($user_id,$role_id,$group_id);
    }


    /**
     * Function to check if a role exists
     * 
     * 
     * 
     */
    function role_exists_ctrl($role_name,$group_id)
    {
        $group = new group_class();

        return $group->role_exists($role_name,$group_id);
    }
    

    /**
     * Function to check if a role has already been assigned
     * 
     * 
     * 
     */
    function check_assignment_ctrl($role_id,$group_id,$user_id)
    {
        $group = new group_class();

        return $group->role_exists($role_id,$group_id);
    }


    
    /**
     * Function to select all group roles
     * 
     * 
     */
    function select_roles_ctrl($group_id)
    {
      $group = new group_class();

      return $group->select_roles($group_id);
    }
    

    /**
     * FUnction to select user role
     * 
     * 
     * 
     * 
     */
    function select_user_role_ctrl($user_id,$group_id)
    {
      $group = new group_class();

      return $group->select_user_role($user_id,$group_id);
    }
?>
