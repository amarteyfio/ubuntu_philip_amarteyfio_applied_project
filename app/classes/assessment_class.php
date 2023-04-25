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
    function check_assessment_completion($group_id,$reviewee_id)
    {
        //get number of members in the group
        $query_i = "SELECT * FROM user_groups WHERE group_id = ?";

        $param_i = [$group_id];

        $count = $this->db_count($query_i,$param_i);

        //get assesment start and end date
        $query_ii = "SELECT * FROM assessment_date WHERE group_id = ? LIMIT 1";

        $assessment_info = $this->db_fetch_one($query_ii,$param_i);

        if(!empty($assessment_info)){
        $end_date = $assessment_info['assessment_end'];

        //get number of assesments made
        $query_iii = "SELECT * FROM assessment WHERE reviewee_id = ? AND group_id = ?";

        $param_ii = [$reviewee_id,$group_id];

        $rev_count = $this->db_count($query_iii,$param_ii);

        //check if assesment date is past
        $current_date = date("Y-m-d");
            

        if($current_date >= $end_date)
        {
            return true;
        }
        else
        {
            
            //check if all members have completed the evaluation
            if($rev_count == ($count - 1))
            {
                return true;
            }
            else
            {
                return false;
            }

        }
    }
    else
    {
        return false;
    }

    }




    /**
     * Function to check if assessment date has been submitted already
     * 
     * 
     * 
     */
    function check_assessment_date($group_id)
    {
        $query = "SELECT * FROM assessment_date WHERE group_id = ? LIMIT 1";

        $param = [$group_id];

        $data = $this->db_fetch_one($query,$param);

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
     * Function to check if assessment has begun for a group.
     * 
     * 
     * 
     */
    function has_assessment_begun($group_id)
    {
        $query = "SELECT * FROM assessment WHERE group_id = ? LIMIT 1";

        $param = [$group_id];

        $data = $this->db_fetch_one($query, $param);

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
     * Function to select assessment date record
     * 
     * 
     * 
     */
    function get_assessment_date($group_id)
    {
        $query = "SELECT * FROM assessment_date WHERE group_id = ? LIMIT 1";

        $param = [$group_id];

        return $this->db_fetch_one($query, $param);

    }


    /**
     * Function to get task completion score for a user
     * 
     * 
     */
    function get_task_completion($group_id,$user_id)
    {
        $query = "SELECT * FROM tasks WHERE group_id = ? AND assigned_to = ?";

        $params = [$group_id,$user_id];

        $data = $this->db_fetch_all($query, $params);

        //variables
        $tasks_assn = 0; //number of tasks assigned 
        $tts = 0; //total task score
        $task_comp = 0; //number of tasks completed
        $ttc = 0; //task completion score for a user
        $task_completion = 0; //task completion score
        $task_array = array(); //task array

        foreach($data as $row)
        {
            $tasks_assn++;
            $tts +=10;

            if($row['status'] == 1) //task is completed on time 10/10
            {
                $task_comp++;
                $ttc +=10;
            }
            elseif($row['status'] == 2) // tasks is completed late 5/10
            {
                $task_comp++;
                $ttc +=5;
            }  

        }

        //calculate the total task score
        $task_completion = round(($ttc/$tts)*100);

        //assign array values
        $task_array['task_assigned'] = $tasks_assn;
        $task_array['task_completed'] = $task_comp;
        $task_array['total_score'] = $task_completion;

        return $task_array;

    }


    /**
     * Function to get meeting attendance score
     * 
     * 
     * 
     */
    function get_attendance_score($group_id,$user_id)
    {
        $query = "SELECT * FROM meetings WHERE group_id = ?";

        $param = [$group_id];

        $m_count = $this->db_count($query,$param); //meeting count

        //Second Query
        $sql = "SElECT * FROM attendance WHERE group_id = ? AND attendee_id = ?";

        $params = [$group_id,$user_id];

        $attendance_records = $this->db_fetch_all($sql,$params);//attendance records

        //variables
        $attendance_count = 0;
        $attendance_score = 0;
        $attendance_array = array();

        foreach($attendance_records as $row)
        {
            if($row['status'] == 0)
            {
                $attendance_count++;
                $attendance_score+=10;
            }
            elseif($row['status'] == 1)
            {   
                $attendance_count++;
                $attendance_score+=5;
            }
        }

    $total_attendance = $m_count * 10;//attendance maximum score

    $total_attendance_score = round(($attendance_score/$total_attendance)*100); //attendance score

    //set array values
    $attendance_array['meeting_count'] = $m_count;
    $attendance_array['attendance_count'] = $attendance_count;
    $attendance_array['total_score'] = $total_attendance_score;


    return $attendance_array;
}

/**
 * Function to select the feedback for the user
 * 
 * 
 * 
 */
function get_feedback($group_id,$user_id)
{
    $query = "SELECT * FROM assessment WHERE group_id = ? AND reviewee_id = ?";

    $params = [$group_id,$user_id];

    $data = $this->db_fetch_all($query,$params);

    $feedback = array();
    $i = 0; //iterator

    foreach($data as $row)
    {
        if(!empty($row['feedback']))
        {
            $feedback[$i] = $row['feedback'];
            $i++; 
        }
    }

    return $feedback;
}


/**
 * Function to get evaluation score.
 * 
 * 
 * 
 */
function get_evaluation_score($group_id,$user_id)
{
    $query = "SELECT * FROM assessment WHERE group_id = ? AND reviewee_id = ?";

    $params = [$group_id,$user_id];

    $data = $this->db_fetch_all($query,$params);

    $i = 0; //iterator

    //variables
    $evaluation_scores = array();

    foreach($data as $row)
    {
        $evaluation_scores[$i] = intval($row['total']); 
        $i++; 
    }

    $total_score = round(array_sum($evaluation_scores)/count($evaluation_scores));

    return $total_score;

}

/**
 * Function to check if a user has alread y filled out an evaluation form
 * 
 * 
 * 
 */
function check_form($user_id,$group_id,$reviewee_id)
{
    $query = "SELECT * FROM assessment WHERE reviewer_id = ? AND group_id = ? AND reviewee_id = ? LIMIT 1";

    $params = [$user_id,$group_id,$reviewee_id];

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


}