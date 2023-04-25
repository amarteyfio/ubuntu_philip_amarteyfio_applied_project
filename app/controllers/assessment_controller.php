<?php

/**
 * This file contains the controller functions for the events class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/assessment_class.php');


    /**
     * Function to add a new assessment
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_assessment_ctrl($group_id,$reviewer_id,$reviewee_id,$q1_score,$q2_score,$q3_score,$q4_score,$q5_score,$q6_score,$q7_response,$q8_response,$feedback,$total)
    {
        $assessment = new assessment_class();

        return $assessment->add_assessment($group_id,$reviewer_id,$reviewee_id,$q1_score,$q2_score,$q3_score,$q4_score,$q5_score,$q6_score,$q7_response,$q8_response,$feedback,$total);
    }


    /**
 * Function to check if a reviewee has already been reviewed by the current user
 *
 * @param int $group_id
 * @param int $reviewer_id
 * @param int $reviewee_id
 * @return bool
 */
function check_review_ctrl($group_id, $reviewer_id, $reviewee_id)
{
    $assessment = new assessment_class();
    return $assessment->check_review($group_id, $reviewer_id, $reviewee_id);

}

/**
 * Function to add assessment date
 *
 * @param int $group_id
 * @param string $assessment_date
 * @param string $assessment_end
 * @return bool
 */
function add_assessment_date_ctrl($group_id, $assessment_date, $assessment_end)
{
    $assessment = new assessment_class();
    return $assessment->add_assessment_date($group_id, $assessment_date, $assessment_end);
}

/**
 * Function to set assessment dates
 *
 * @param int $group_id
 * @param string $assessment_start
 * @param string $assessment_end
 * @return bool
 */
function set_assessment_date_ctrl($group_id, $assessment_start, $assessment_end)
{
    $assessment = new assessment_class();
    return $assessment->set_assessment_date($group_id, $assessment_start, $assessment_end);
}

/**
 * Function to check if all the assessments are completed
 *
 * @param int $group_id
 * @return bool
 */
function check_assessment_completion_ctrl($group_id,$reviewee_id)
{
    $assessment = new assessment_class();
    return $assessment->check_assessment_completion($group_id,$reviewee_id);
}


/**
     * Function to check if assessment date has been submitted already
     * 
     * 
     * 
     */
    function check_assessment_date_ctrl($group_id)
    {
        $assessment = new assessment_class();

        return $assessment->check_assessment_date($group_id);
    }


     /**
     * Function to check if assessment has begun for a group.
     * 
     * 
     * 
     */
    function has_assessment_begun_ctrl($group_id)
    {
        $assessment = new assessment_class();

        return $assessment->has_assessment_begun($group_id);
    }

     /**
     * Function to select assessment date record
     * 
     * 
     * 
     */
    function get_assessment_date_ctrl($group_id)
    {
        $assessment = new assessment_class();

        return $assessment->get_assessment_date($group_id);
    }

    
    /**
     * Function to get attendance score
     * 
     * 
     * 
     */
    function get_attendance_score_ctrl($group_id,$user_id)
    {
        $assessment = new assessment_class();

        return $assessment->get_attendance_score($group_id,$user_id);
    }


    /**
     * Function to get task completion score
     * 
     * 
     * 
     */
    function get_task_completion_ctrl($group_id,$user_id)
    {
        $assessment = new assessment_class();

        return $assessment->get_task_completion($group_id,$user_id);
    }


    /**
     * Function to get feedback for user
     * 
     * 
     * 
     */
    function get_feedback_ctrl($group_id,$user_id)
    {
        $assessment = new assessment_class();

        return $assessment->get_feedback($group_id,$user_id);

    }

    /**
     * Function to get average evaluation score
     * 
     * 
     * 
     */
    function get_evaluation_score_ctrl($group_id,$user_id)
    {
        $assessment = new assessment_class();

        return $assessment->get_evaluation_score($group_id,$user_id);
    }


    /**
     * Function to check if a user has alread y filled out an evaluation form
     * 
     * 
     * 
     */
    function check_form_ctrl($user_id,$group_id,$reviewee_id)
    {
        $assessment = new assessment_class();

        return $assessment->check_form($user_id,$group_id,$reviewee_id);
    }