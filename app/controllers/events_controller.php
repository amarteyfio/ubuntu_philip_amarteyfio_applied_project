<?php

/**
 * This file contains the controller functions for the events class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/events_class.php');


/**
     * Controller function to add an event to the database
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_event_ctrl($group_id,$event_tit,$event_desc,$event_date)
    {
        $events = new events_class();

        return $events->add_event($group_id,$event_tit,$event_desc,$event_date);
    }

    /**
     * Function to edit an event
     * 
     * @author Philip Amarteyfio
     */
    function edit_event_ctrl($event_id,$event_tit,$event_desc,$event_date)
    {
        $events = new events_class();

        return $events->edit_event($event_id,$event_tit,$event_desc,$event_date);
    }

    /**
     * Function to delete an event from the database
     * 
     * 
     * @author Philip Amarteyfio
     */
    function delete_event($event_id)
    {
        $events = new events_class();

        return $events->delete_event($event_id);
    }


    /**
     * Function to select all events for a group 
     * 
     * 
     * 
     */
    function select_group_events_ctrl($group_id)
    {
        $events = new events_class();

        return $events->select_group_events($group_id);
    }

    /**
     * Function to select event
     * 
     * 
     * @author Philip Amarteyfio
     */
    function select_event_ctrl($event_id)
    {
        $events = new events_class();

        return $events->select_event($event_id);
    }



?>