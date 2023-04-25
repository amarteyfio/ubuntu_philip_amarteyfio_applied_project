<?php

/**
 * This file contains the controller functions for the events class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/meetings_class.php');


/**
     * Function to add a meeting
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_meeting_ctrl($group_id,$meeting_title,$meeting_desc,$meeting_type,$meeting_loc,$begins_at,$ends_at)
    {
        $meeting = new meetings_class();

        return $meeting->add_meeting($group_id,$meeting_title,$meeting_desc,$meeting_type,$meeting_loc,$begins_at,$ends_at);
    }


    /**
     * Function to select meetings
     * 
     * 
     * @author Philip Amarteyfio
     */
    function select_group_meetings_ctrl($group_id)
    {
        $meeting = new meetings_class();


        return $meeting->select_group_meetings($group_id); 
    }


    /**
     * Function to add attendance record
     * 
     * 
     * @author Philip Amarteyfio
     */

     function add_attendance_ctrl($meeting_id,$attendee_id,$group_id,$stat)
     {
         $meeting = new meetings_class(); 
 
         return $meeting->add_attendance($meeting_id,$attendee_id,$group_id,$stat);
     }
 
 
     /**
      * Controller function set attendance
      * 
      * 
      * @author Philip Amarteyfio
      */
     function set_attendance_ctrl($meeting_id,$attendee_id,$stat)
     {
         $meeting = new meetings_class();

         return $meeting->set_attendance($meeting_id,$attendee_id,$stat);
     }


     /**
     * Function to select user attendance record
     * 
     * @author Philip Amarteyfio
     * 
     */
    function select_attendance_ctrl($user_id,$group_id)
    {
        $meeting = new meetings_class();

        return $meeting->select_attendance($user_id,$group_id);
    }

     /***
     * Function to select a meeting
     * 
     * 
     * 
     */
    function select_meeting_ctrl($meeting_id)
    {
        $meeting = new meetings_class();

        return $meeting->select_meeting($meeting_id);
    }
     

    /**
     * Function to check a users attendance
     * 
     * 
     * @author Philip Amarteyfio
     */
    function check_attendance_ctrl($attendee_id,$meeting_id)
    {
        $meeting = new meetings_class();

        return $meeting->check_attendance($attendee_id,$meeting_id);
    }

    /**
     * Function to update meeting log
     * 
     * 
     * @author Philip Amarteyfio
     */
    function attend_log_ctrl($group_id)
    {
        $meeting = new meetings_class();

        return $meeting->attend_log($group_id);
    }


    /**
     * Function to send meeting email
     * 
     * 
     */
    function send_meeting_invitation_ctrl($to, $subject, $description, $startDateTime, $endDateTime, $location)
    {
        $meeting = new meetings_class();

        return $meeting->send_meeting_invitation($to, $subject, $description, $startDateTime, $endDateTime, $location);
    }
?>