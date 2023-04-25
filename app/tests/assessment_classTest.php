<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing operations related to activity in the database.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */


 /*----------------------------------------------------/
                    ASSESSMENT FUNCTIONS
 /----------------------------------------------------*/


 class assessment_class extends db_config
 {
    /**
     * Function to add a new assessment
     * 
     * 
     * 
     */
    function add_assessment($group_id,$reviewer_id,$reviewee_id,$q1_score,$q2_score,$q3_score,$q4_score,$q5_score,$q6_score,$q7_response,$q8_response,$feedback,$total)
    {
        $query = "INSERT INTO assessment (group_id,reviewer_id,reviewee_id,q1_score,q2_score,q3_score,q4_score,q5_score,q6_score,q7_response,q8_response,feedback,total) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"; 

        $params = [$group_id,$reviewer_id,$reviewee_id,$q1_score,$q2_score,$q3_score,$q4_score,$q5_score,$q6_score,$q7_response,$q8_response,$feedback,$total];

        return $this->run_query($query,$params);

    }


    /**
     * Function to set check if a reviewee has already been reviewed by the current user(if already reviewed return true)
     * 
     * 
     * 
     */
    function check_review($group_id,$revieweer_id,$reviewee_id)
    {
        $query = "SELECT * FROM assessment WHERE group_id = ?, reviewer_id = ?,reviewee_id = ?";

        $params = [$group_id,$revieweer_id,$reviewee_id];

        $data = $this->db_fetch_all($query,$params);
    }


   /**
    * Function to set assessment date
    * 
    * 
    * 
    * @author Philip Amarteyfio
    */
    function add_assessment_date($group_id, $assessment_date, $assessment_end)
    {
        $query = "INSERT INTO assessment_date (group_id, assessment_start, assessment_end) VALUES (?,?,?)";

        $params = [$group_id, $assessment_date, $assessment_end];

        return $this->run_query($query, $params);
    }


    /**
     * Function to set assessment dates
     * 
     * 
     * @author Philip Amarteyfio
     */
    function set_assessment_date($group_id, $assessment_start,$assessment_end)
    {
        $query = "UPDATE assessment_date SET assessment_start = ?, assessment_end = ? WHERE group_id = ?";

        $params = [$group_id, $assessment_start,$assessment_end];

        return $this->run_query($query,$params);
    }

    
    /**
     * Function to check if the all the assessments are completed(return true if completed)
     * 
     * 
     * @author Philip Amarteyfio
     */
    function check_assessment_completion($group_id)
    {
        $query = "SELECT * FROM assessment_date WHERE group_id = ?";

        $param = [$group_id];

        $data = $this->db_fetch_one($query, $param);
    }
 }