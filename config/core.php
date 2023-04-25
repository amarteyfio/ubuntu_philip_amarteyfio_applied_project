<?php
//start session
session_start(); 

//include key
include "key.php";

//for header redirection
ob_start();

//funtion to check for login
function log_check()
{
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("Location: ../auth/login.php");
        exit;
    }
    

}

//function to get user ID
function get_id(){
    $user_id = $_SESSION["id"];
    return $user_id;
}


//function to check for role (admin, customer, etc)
function get_role(){
    $user_role = $_SESSION["role"];
    return $user_role;
}

//get encryption key 
function get_encryption_key() {
    $key = ENCODING_KEY;
    return $key; 
}


// encryption function 
function url_encrypt($number) {
    $cipher = "AES-256-CBC";
    $key = ENCODING_KEY;
    $encrypted = openssl_encrypt($number, $cipher, $key, $options=0, IV);
    return base64_encode($encrypted);
}

// decryption function
function url_decrypt($encrypted) {
    $cipher = "AES-256-CBC";
    $key = ENCODING_KEY;
    $decrypted = openssl_decrypt(base64_decode($encrypted), $cipher, $key, $options=0, IV);
    return intval($decrypted);
}


//**********TOKEN GENERATION ************************//
function generate_token()
 {
    //length
    $length = 40;

    // define a string of possible characters for the token
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  
    // generate a random string using the characters
    $token = '';
    for ($i = 0; $i < $length; $i++) {
      $token .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $token;
 }

?>