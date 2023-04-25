<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing operations related to groups in the database.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */


 /*----------------------------------------------------/
                    GROUP FUNCTIONS
 /----------------------------------------------------*/


 class group_class extends db_config
 {
    /**
     * Function for creating a new group 
     * 
     * @author Philip Amarteyfio
     * 
     */


    function add_group($group_name,$group_desc)
    {
        $query = "INSERT INTO groups (group_name,group_desc) VALUES (?,?)";

        $param = [$group_name,$group_desc];

        $this->run_query($query,$param);
    }

    /**
     * Function to retieve all groups for a specific user  
     * 
     * @author Philip Amarteyfio
     * 
     */

    function user_groups($user_id)
    {   
        $query = "SELECT * FROM user_groups WHERE user_id = ? ORDER BY group_id DESC";

        $param = [$user_id];

        return $this->db_fetch_all($query,$param);
    }

    /**
     * Function to select a group using the ID
     * 
     * @author Philip Amarteyfio
     * 
     */
    function select_group($group_id)
    {
        $query = "SELECT * FROM groups WHERE group_id = ?";

        $param = [$group_id];

        return $this->db_fetch_one($query,$param);
    }
    
    /**
     * Function to count number of group members
     * 
     * @author Philip Amarteyfio
     * 
     */
    function count_group($group_id)
    {
        $query = "SELECT * FROM user_groups WHERE group_id = ?";

        $param = [$group_id];

        return $this->db_count($query,$param);
    }

    /**
     * Function to Check if a Group Name already Exists
     * 
     * 
     * @author Philip Amarteyfio
     */
    function group_exists($group_name)
    {
        $query = "SELECT * FROM groups WHERE group_name = ?";

        $param = [$group_name];

        $data = $this->db_fetch_one($query, $param);

        //check if empty
        if(!empty($data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Function to add user to user groups 
     * 
     * @author Philip Amarteyfio
     */
    function add_user_group($user_id,$group_id)
    {
        $query = "INSERT INTO user_groups (user_id, group_id) VALUES (?,?)";

        $params = [$user_id,$group_id];

        $this->run_query($query, $params);
    }    

    /**
     * Function to add group creator to group admins
     * 
     * @author Philip Amarteyfio
     */
    function add_group_admin($user_id,$group_id)
    {
        $query = "INSERT INTO group_admin (group_id,user_id) VALUES (?,?)";

        $params = [$group_id,$user_id];

        $this->run_query($query, $params);
    }

    /**
     * Function to get ID of Inserted Group
     * 
     * @author Philip Amarteyfio
     */

     function get_group_id($group_name)
     {
        $query = "SELECT * FROM groups WHERE group_name = ?";

        $params = [$group_name];

        $data = $this->db_fetch_one($query, $params);

        return $data['group_id'];
     }


     /**
      *Function to check is a user is a member of a group 
      *
      * @author Philip Amarteyfio
      */
      function is_member($group_id,$user_id)
      {
        $query = "SELECT * FROM user_groups WHERE user_id = ? AND group_id = ?";

        $params = [$user_id,$group_id];

        $data = $this->db_fetch_one($query,$params);

        if(!empty($data))
        {
            return true;
        }

        else
        {
            return false;
        }
      }


    /**
     * Function to check if a user is a group admin or not
     * 
     * 
     * @author Philip Amarteyfio
     */

    function is_admin($group_id,$user_id)
    {
        $query = "SELECT * FROM group_admin WHERE group_id = ? AND user_id = ?";

        $params = [$group_id,$user_id];

        $data = $this->db_fetch_one($query,$params);

        if(!empty($data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Function to select all members of a group
     * 
     * @author Philip Amarteyfio
     */

     function all_members($group_id)
     {
        $query = "SELECT * FROM user_groups WHERE group_id = ?";

        $params = [$group_id];

        $data = $this->db_fetch_all($query, $params);

        return $data;
     }



     /**
      * Function to create an invite to the group 
      *
      *
      * @author Philip Amarteyfio
      */
      function create_invite($group_id,$email_to,$token,$expires_at)
      {
            $query = "INSERT INTO invites (group_id, email_to, token, expires_at) VALUES (?,?,?,?)";

            $params = [$group_id,$email_to,$token,$expires_at];

            return $this->run_query($query,$params);


      }

      /**
       * Function to select an invite by ID
       * 
       * 
       * @author Philip Amarteyfio
       */
        function select_invite($invite_id)
        {
            $query = "SELECT * FROM invites WHERE invite_id = ?";

            $param = [$invite_id];

            $data = $this->db_fetch_one($query,$param);

            return $data;
        }


    /**
       * Function to select an invite by token
       * 
       * 
       * @author Philip Amarteyfio
       */
      function select_invite_with_token($token)
      {
         $query = "SELECT * FROM invites WHERE token = ?";

         $param = [$token];

         $data = $this->db_fetch_one($query,$param);

         return $data;
      }


    
    /**
     * Function to change invite status
     * 
     * 
     * @author Philip Amarteyfio
     */
    function change_invite_status($invite_id,$status)
    {
        $query = "UPDATE invites SET status = ? WHERE invite_id = ?";

        $params = [$status,$invite_id];

        return $this->run_query($query,$params);
    }


    /**
     * Function Remove user from group
     * 
     * 
     * @author Philip Amarteyfio
     */
    function remove_user($group_id,$user_id)
    {
        $query = "DELETE FROM user_groups WHERE group_id = ? AND user_id = ?";

        $params = [$group_id,$user_id];

        return $this->run_query($query,$params);
    }
    
    
    /**
     * Function to create roles
     * 
     * 
     * 
     * @author Philip Amarteyfio
     */
    function create_roles($role_name,$group_id)
    {
        $query = "INSERT INTO roles (role_name, group_id) VALUES (?, ?)";

        $params = [$role_name,$group_id];

        return $this->run_query($query,$params);
    }


    /**
     * Function to set roles
     * 
     * 
     * 
     */
    function set_roles($user_id,$role_id,$group_id)
    {
        $query = "INSERT INTO user_roles (user_id,role_id,group_id) VALUES (?,?,?)";

        $params = [$user_id,$role_id,$group_id];

        return $this->run_query($query, $params);
    }
      

    /**
     * Function to check if a role exists
     * 
     * 
     * 
     */
    function role_exists($role_name,$group_id)
    {
        $query = "SELECT * FROM roles WHERE role_name = ? AND group_id = ? LIMIT 1";

        $params = [$role_name,$group_id];

        $data = $this->db_fetch_one($query,$params);

        if(!empty($data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    

    /**
     * Function to check if a role has already been assigned
     * 
     * 
     * 
     */
    function check_assignment($role_id,$group_id,$user_id)
    {
        $query = "SELECT * FROM user_roles WHERE role_id = ? AND group_id = ? LIMIT 1";

        $params = [$role_id,$group_id];

        $data = $this->db_fetch_one($query,$params);

        if(!empty($data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Function to select all group roles
     * 
     * 
     */
    function select_roles($group_id)
    {
        $query = "SELECT * FROM roles WHERE group_id = ?";

        $param = [$group_id];

        return $this->db_fetch_all($query,$param);
    }


    /**
     * FUnction to select user role
     * 
     * 
     * 
     * 
     */
    function select_user_role($user_id,$group_id)
    {
        $query = "SELECT * FROM user_roles WHERE user_id = ? AND group_id = ? LIMIT 1";

        $params = [$user_id,$group_id];

        $data = $this->db_fetch_one($query,$params);

        if(!empty($data))
        {
            $role_id = $data['role_id'];

            $query_b = "SELECT * FROM roles WHERE role_id = ? LIMIT 1";
    
            $param = [$role_id];
    
            $info = $this->db_fetch_one($query_b,$param);
    
            return $info['role_name'];
        }
        else
        {
            return "No role Assigned";
        }

       
    
    }


    

}
