<?php 
/**
 * This code validates and creates a group 
 * 
 * @author Philip Amarteyfio
 */

 //require vendor
 require_once '../resources/vadersentiment.php';

 //include core
 include('../../config/core.php');

 //Require the assessment controller
 require_once('../controllers/assessment_controller.php');

 //check if post request has been made
 if(!isset($_POST))
 {
    echo "Fatal Error";
    exit(1);
 }


  //check if post request has been made
  if(!isset($_GET))
  {
     echo "Fatal Error";
     exit(1);
  }


  //sentiment analyzer from vader.
 $sentimenter = new SentimentIntensityAnalyzer();


 //error variables
 $errors = "";

 //for response cleaning
 $pattern = '/[^a-zA-Z0-9\s.,!()\[\]\{\}\?\'"\/\\<>:;-]/';

 $replacement = '';



 //get data
 $group_id = url_decrypt($_GET['group']); //group id

 $reviewer_id = $_SESSION['id']; //reviewer id

$question1 = intval($_POST['question1']);//q1
$question2 = intval($_POST['question2']);//q2
$question3 = intval($_POST['question3']);//q3
$question4 = intval($_POST['question4']);//q4
$question5 = intval($_POST['question5']);//q5
$question6 = intval($_POST['question6']);//q6
$question7 = $_POST['question7']; //question7
$question8 = $_POST['question8']; //question8
$feedback = $_POST['feedback']; //feedback
$reviewee_id = url_decrypt($_POST['reviewee_id']); //reviewee_id

//clean question 7, 8 and Feedback
$question7 = preg_replace($pattern, $replacement, $question7);
$question8 = preg_replace($pattern, $replacement, $question8);
$feedback = preg_replace($pattern, $replacement, $feedback);


//TOTAL ASSESSMENT SCORE
$total_assessment_score = 0;

//validate data
// Validate question1
if (!isset($_POST['question1']) || !in_array($_POST['question1'], range(1,5))) {
    $errors = "Please provide a valid answer for question 1.";
}

// Validate question2
if (!isset($_POST['question2']) || !in_array($_POST['question2'], range(1,5))) {
    $errors = "Please provide a valid answer for question 2.";
}

// Validate question3
if (!isset($_POST['question3']) || !in_array($_POST['question3'], range(1,5))) {
    $errors = "Please provide a valid answer for question 3.";
}

// Validate question4
if (!isset($_POST['question4']) || !in_array($_POST['question4'], range(1,5))) {
    $errors = "Please provide a valid answer for question 4.";
}

// Validate question5
if (!isset($_POST['question5']) || !in_array($_POST['question5'], range(1,5))) {
    $errors = "Please provide a valid answer for question 5.";
}

// Validate question6
if (!isset($_POST['question6']) || !in_array($_POST['question6'], range(1,5))) {
    $errors = "Please provide a valid answer for question 6.";
}

//**** AFTER VALIDATION *****//
if(empty($errors))
{
    // get the total score //
    $objective_total = 30;
    $objective_score = $question1 + $question2 + $question3 + $question4 + $question5 + $question6;

   

    //**** SUBJECTIVR PORTION ****//
    $subjective_total = 0;
    $subjective_score = 0;
    $q7;
    $q8;

    //if question 7 is not empty
    if(!empty($question7))
    {
       $subjective_total += 10; //increase subjective total

       $q7_results = $sentimenter->getSentiment($question7);

       $score = $q7_results['compound'];

       $q7_score = round(($score + 1) * 5);

       $q7 = true;
    }
    else
    {
        $q7 = false;
    }


    //if question 8 is not empty
    if(!empty($question8))
    {
       $subjective_total += 10; //increase subjective total

       $q8_results = $sentimenter->getSentiment($question8);

       $score = $q8_results['compound'];

       $q8_score = round(($score + 1) * 5);

       $q8 = true;
    }
    else
    {
        $q8 = false;
    }

    //question 7 and 8 checks
    if($q7 == true && $q8 == true)
    {
        $subjective_score = $q7_score + $q8_score;
    }
    elseif($q7 == true && $q8 == false)
    {
        $subjective_score = $q7_score;
    }
    elseif($q7 == false && $q8 == true)
    {
        $subjective_score = $q8_score;
    }
    elseif( $q7 == false &&  $q8 == false)
    {
        $subjective_score = 0;
    }

    //Total Score 
    $total_assessment_score = round((($objective_score + $subjective_score)/($objective_total + $subjective_total))*100);

    if(!empty($total_assessment_score))
    {
        //add assessment record to das
        add_assessment_ctrl($group_id,$reviewer_id,$reviewee_id,$question1,$question2,$question3,$question4,$question5,$question6,$question7,$question8,$feedback,$total_assessment_score);
        //Success
        echo "Success";
    }
    
    


    
}


 



?>