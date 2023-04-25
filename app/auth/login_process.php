<?php 
/**
 * This code logs a user in
 * 
 * @author Philip Amarteyfio
 */
 //include core
 include('../../config/core.php');

 //Require the user controller
 require_once('../controllers/user_controller.php');

 /* Check if the User is already logged in */
 if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
 {
     echo "You are already logged in";
     exit();
 }

 

 /* Error Variables */
 $email_err = "";
 $password_err = "";

 /* Get the data */

 $email = $_POST['email']; //email
 $password = $_POST['password']; //password

 /* Verify the Data */

 $email = trim($email); //remove whitespace

 //if email is empty after trim check
 if(empty($email))
 {
    $email_err = "Please enter a valid email";
 }

 //verify email format
 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
 {
    $email_err = "Invalid Email";

 }

 /* If there are no errors */
 if(empty($email_err) && empty($password_err)){

  /* Verify user credentials */
  if(verify_user_ctrl($email, $password) == TRUE) 
  { 
    //get user information using email
    $data = get_user_info_ctrl($email);

    //set session variables
    $_SESSION['loggedin'] = true; //logged in variable
    $_SESSION['id'] = $data['id']; //user id
    $_SESSION['user_name'] = $data['f_name'].' '.$data['l_name']; //user name
    $_SESSION['user_role'] = $data['role']; //user role

    //success message
    echo "Success";
    
  }
  else
    {
      echo "Invalid credentials";

    }
  
  }
  else
      {
        echo "Invalid Credentials";
      }


 

 
?>