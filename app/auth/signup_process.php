<?php
/**
 * This code registers a user into the database
 * 
 * @author Philip Amarteyfio
 */
 
 //require user controller
 require('../controllers/user_controller.php');

 /* Error Control Variables */
 $name_err = "";
 $email_err = ""; 
 $password_err = "";
 $contact_err = "";


 /* Get the Data */
 
 $f_name = trim($_POST['f_name']); //first name
 $l_name = trim($_POST['l_name']); //first name
 $email = trim($_POST['email']); //email
 $contact = trim($_POST['contact']); //contact
 $password = trim($_POST['password']); //password
 $password_confirmation = trim($_POST['password_confirmation']); //password_confirmation
 
 


 /* Validate Name */

//if first name is empty
 if(empty($f_name))
 {
    $name_err = "Please enter a first name";
 }

 //Validate last name
 if(preg_match("/^([A-Za-z]+[-\s]?[A-Za-z]+)+$/",$f_name) == 0)
 {
    $name_err = "enter a valid first name";
 }

 //if first name is empty
 if(empty($l_name))
 {
    $name_err = "Please enter a last name";
 }

 //Validate first name
 if(preg_match("/^([A-Za-z]+[-\s]?[A-Za-z]+)+$/",$l_name) == 0)
 {
    $name_err = "enter a valid last name";
 }
 

 /* Validate Email */

 //if email is empty
 if (empty(trim($email)))
 {
    $email_err = "Please enter an email";
 }
 else
{

 //check email format
 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
 {
    $email_err = "Invalid email format";
 }
 else
 {
   //check if email already exists
   if(email_exists_ctrl($email) == true)
      {
         $email_err = "This email is already registered";
      }
 }

}

/* Validate Phone Number */
if(empty($contact))
{
   $contact_err = "Please enter a valid phone number";
}
else
{
   $pattern = '/^[+]?[0-9]{1,3}[-\s.]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,4}[-\s.]?[0-9]{1,4}[-\s.]?[0-9]{1,4}$/';

   if (preg_match($pattern, $contact)) {
      
  } else {
      $contact_err = ' is not a valid phone number. Enter in enter in the format (+)(XXX)(XXXXXXXXXX)' . PHP_EOL;
  }
}



 

 
 /* Validate Password */

 //if password is empty
 if (empty(trim($password)))
  {
    $password_err  = "Please enter a password";
    
  }

  //Check if passwords match
  if(empty($password_err) && ($password != $password_confirmation))
  {
    $password_err = "Password did not match.";
    
  }

  //Check that there are no errors
  if(empty($name_err) && empty($email_err) && empty($password_err) && empty($contact_err))
  {  
    //hash password
    $password = password_hash($password,PASSWORD_DEFAULT);


    //register user
    register_user_ctrl($f_name,$l_name,$email,$password,$contact);

    //send success message
    print "Success";
  }
  else
  {
      print "Error: <br>".$email_err."<br>".$name_err."<br>".$password_err."<br>".$contact_err;
  }

 


 

?>