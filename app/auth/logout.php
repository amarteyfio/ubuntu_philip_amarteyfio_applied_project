<?php
/**
 * This code logs out a user.
 * 
 * @author Philip Amarteyfio
 */

 //start the session
 session_start();

 //Unset session variables
 $_SESSION = array();

 //destroy session
 session_destroy();

 //redirect to homepage
 header("Location: ../../index.php");
 exit;



?>