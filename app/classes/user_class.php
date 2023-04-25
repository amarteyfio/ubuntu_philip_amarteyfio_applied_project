<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing operations related to users in the database i.e login, registration etc.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */

 class user_class extends db_config
 {
    /**
     * This function is used to add a user to the database
     * 
     * @author Philip Amarteyfio
     * @param f_name,l_name,email,password,contact
     * @since Novemeber 2022
     * 
     * 
     */

     function register_user($f_name,$l_name,$email,$password,$contact){

        //sql query
        $sql = "INSERT INTO users (f_name,l_name,email,password,contact) VALUES (?,?,?,?,?)";

        //params
        $params = [$f_name,$l_name,$email,$password,$contact];

        //run query
        $this->run_query($sql,$params);
    }

    /**
     * This function selects a user from the database using the id
     * 
     * @author Philip Amarteyfio
     * @param id
     * @since November 2022
     */
    
    function select_user($id){
        //sql query
        $sql = "SELECT * FROM users WHERE id = ?";

        //params
        $params = [$id];
        //store data in variable
        $record = $this->db_fetch_one($sql,$params);
        //return data
        return $record;
    }

    /**
     * This function changes a users email
     * 
     * @author Philip Amarteyfio
     * @param email,id
     * @since November 2022
     */

    function update_email($email,$id){
        //sql query
        $sql = "UPDATE users SET email = ? WHERE id = ?";

        //params
        $params = [$email,$id];

        //run query
        return $this->run_query($sql,$params);
    }

    /**
     * This function is used to verify a user when logging in
     * 
     * @author Philip Amarteyfio
     * @param email
     * @since November 2022
     */

    function verify_user($email,$password)
    {
        //select user from database
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";

        //params_i
        $params = [$email];

        //store user record in database
        $record = $this->db_fetch_one($sql,$params);

        //check if record is invalid or valid
        if(empty($record))
        {
            return false;
        }
        
        //check if password matches
        if(password_verify($password, $record['password']))
        {
            return true;
        }
        else
        {
            return false;
        }
    
            
    }

    /**
     * This function is used to get the information of a user using their email address
     * 
     * @author Philip Amarteyfio
     * @param email
     * @since November 2022
     */

    function get_user_info($email)
    {
        //sql query
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";

        //params
        $params = [$email];

        //store data in variable
        $record = $this->db_fetch_one($sql,$params);
        //return data
        return $record;
    }

    /**
     * This function is used to check if an email address exists
     * 
     * @author Philip Amarteyfio
     * @param email
     * @since November 2022
     */

    function email_exists($email)
    {
        //sql query
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";

        //params
        $params = [$email];

        //store data in variable
        $record = $this->db_fetch_one($sql,$params);
        
        //check if record is empty
        if(!empty($record))
        {
            return true;
        }
        else
        {
            return false;
        }
    }   


    /**
     * This function is used to add a user invite
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_user_invite($user_id,$token)
    {
        //select invite using token
        $query = "SELECT * FROM invites WHERE token = ? LIMIT 1";

        $param = [$token];

        //fetch invite info
        $invite_info = $this->db_fetch_one($query,$param);

        //select invite id
        $invite_id = $invite_info['invite_id'];

        //add to user_invite table
        $query_b = "INSERT INTO user_invites (user_id,invite_id) VALUES (?,?)";

        $params_b = [$user_id,$invite_id];

        return $this->run_query($query_b,$params_b);
    }


    /***
     * Function to select user invites
     * 
     * 
     * @author Philip Amarteyfio     
     */
    function select_user_invites($user_id)
    {
        $query = "SELECT * FROM user_invites WHERE user_id = ?";

        $param = [$user_id];

        return $this->db_fetch_all($query,$param);
    }

    /**
     * Delete user invite
     * 
     * 
     * @author Philip Amarteyfio
     */
    function delete_user_invite($user_id,$invite_id)
    {
        $query = "DELETE FROM user_invites WHERE user_id = ? AND invite_id = ?";

        $params = [$user_id,$invite_id];

        return $this->run_query($query,$params);
    }

    //FOR TESTING
    function get_new_record()
    {
        $query = "SELECT * FROM users ORDER BY id DESC LIMIT 1";

        $param = [];

        $data = $this->db_fetch_one($query,$param);

        return $data['id'];
    }
 }

 ?>