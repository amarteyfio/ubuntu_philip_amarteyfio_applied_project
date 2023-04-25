<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');


/**
 * Class for performing general operations in the database i.e login, registration etc.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */

 class general_class extends db_config
 {
        /**
         * This function is used to select all rows of a table in the database
         * 
         * @author Philip Amarteyfio
         * @version 0.1
         * 
         */

        function select_all_rows($table)
        {
            //sql query
            $query = "SELECT * FROM ?";

            //param
            $param = [$table];
            
            //return all rows
            return $this->db_fetch_all($query,$param);
        }


        /**
         * This function is used to count all rows of a table in the database
         * 
         * 
         * @author Philip Amarteyfio
         * @version 0.1
         */

         function count_table_rows($table)
         {
            //sql query
            $query = "SELECT * FROM ?";

            $param = [$table];

            //return all rows
            return $this->db_count($query,$param);
         }
 }

 ?>