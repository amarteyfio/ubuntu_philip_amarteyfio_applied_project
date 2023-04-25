<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing operations related to events in the database.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */


 /*----------------------------------------------------/
                    EVENT FUNCTIONS
 /----------------------------------------------------*/


 class events_class extends db_config
 {
    /**
     * Function to add an event to the database
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_event($group_id,$event_tit,$event_desc,$event_date)
    {
        $query = "INSERT INTO events (group_id,event_tit,event_desc,event_date) VALUES (?,?,?,?)";

        $params = [$group_id,$event_tit,$event_desc,$event_date];

        return $this->run_query($query,$params);
    }

    /**
     * Function to edit an event
     * 
     * @author Philip Amarteyfio
     */
    function edit_event($event_id,$event_tit,$event_desc,$event_date)
    {
        $query = "UPDATE events SET event_tit = ?, event_desc = ?, event_date = ? WHERE event_id = ? AND event_id = ?";

        $params = [$event_tit,$event_desc,$event_date,$event_id];

        return $this->run_query($query,$params);
    }

    /**
     * Function to delete an event from the database
     * 
     * 
     * @author Philip Amarteyfio
     */
    function delete_event($event_id)
    {
        $query = "DELETE FROM events WHERE event_id = ?";

        $param = [$event_id];

        return $this->run_query($query,$param);
    }


    /**
     * Function to select all events for a group 
     * 
     * 
     * 
     */
    function select_group_events($group_id)
    {
        $query = "SELECT * FROM events WHERE group_id = ? ORDER BY event_date ASC";

        $param = [$group_id];

        return $this->db_fetch_all($query,$param);
    }

    /**
     * Function to select single events
     * 
     * 
     * @author Philip Amarteyfio
     */
    function select_event($event_id)
    {
        $query = "SELECT * FROM events WHERE event_id = ?";

        $param = [$event_id];

        return $this->db_fetch_one($query, $param);
    }

 }


?>