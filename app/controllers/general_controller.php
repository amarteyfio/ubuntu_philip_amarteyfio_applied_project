 <?php
/**
 * This file contains the controller functions for the general class
 * 
 */

//Require general class
set_include_path(dirname(__FILE__)."/../");
require('classes/general_class.php');


/**
 * This function is a controller for the select all rows function
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * @param string $table
 */

 function select_all_rows_ctrl($table)
 {
    //create new instance of general class
    $general = new general_class();

    //select all rows
    return $general->select_all_rows($table);
 }

 
 /**
 * This function is a controller for the count table rows function
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * @param string $table
 */

 function count_table_rows_ctrl($table)
 {
    //create new instance of general class
    $general = new general_class();

    //select all rows
    return $general->count_table_rows($table);
 }


?>