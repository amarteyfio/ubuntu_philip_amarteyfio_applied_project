<?php
// Require Database Credentials
require('db_cred.php');

/**
 * Class for connecting to and executing queries on a MySQL database using mysqli with prepared statements
 * 
 * @author Philip Amarteyfio
 * @version 0.2
 * 
 */
class db_config
{
    /**
     * @var mysqli The mysqli connection object
     */
    private $db;

    /**
     * @var mysqli_stmt The mysqli statement object
     */
    private $stmt;

    /**
     * @var mixed The result set from a query execution
     */
    public $results;

    /**
     * Connects to the database using the provided credentials
     * 
     * @return boolean True on successful connection, False otherwise
     */
    public function db_connect()
    {
        // Connect to the database using the credentials
        $this->db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Check the connection
        if ($this->db->connect_errno) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Executes a given query on the database
     * 
     * @param string $query The query to be executed
     * @param array $params The parameters to bind to the query
     * 
     * @return boolean True on successful query execution, False otherwise
     */
    public function run_query($query, $params = [])
{
    // Check the connection
    if (!$this->db_connect()) {
        return false;
    }

    // Prepare the statement
    $this->stmt = $this->db->prepare($query);

    // Bind the parameters dynamically based on their types
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
        }
        $bind_params = array_merge([$types], $params);
        $this->stmt->bind_param(...$bind_params);
    }

    // Execute the statement
    $this->stmt->execute();

    // Get the result set
    $this->results = $this->stmt->get_result();

    // Check the query execution
    if ($this->results === false) {
        return false;
    } else {
        return true;
    }
}


    /**
     * Fetches a single row of data from the result set of a query
     * 
     * @param string $query The query to be executed
     * @param array $params The parameters to bind to the query
     * 
     * @return array|boolean The fetched row as an associative array on success, False otherwise
     */
    public function db_fetch_one($query, $params = [])
    {
        // Check the query
        if (!$this->run_query($query, $params)) {
            return false;
        }

        return $this->results->fetch_assoc();
    }

    /**
     * Fetches all rows of data from the result set of a query
     * 
     * @param string $query The query to be executed
     * @param array $params The parameters to bind to the query
     * 
     * @return array|boolean The fetched rows as an associative array on success, False otherwise
     */
    public function db_fetch_all($query, $params = [])
    {
        // Check the query
        if (!$this->run_query($query, $params)) {
            return false;
        }

        return $this->results->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Gets the count of rows from the result set of a query
     * 
     * @param string $query The query to be executed
     */

     function db_count($query, $params = [])
     {
        // Check the query
        if (!$this->run_query($query, $params)) {
            return false;
        }

        $rows = $this->results->num_rows;

        return $rows;
    }

}