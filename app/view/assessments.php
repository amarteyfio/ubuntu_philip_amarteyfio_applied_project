<?php
//INCLUDE CORE
include "../../config/core.php";

//CHECK FOR LOGIN
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
}

//group controller
include "../controllers/group_controller.php";

//include user controller
include "../controllers/user_controller.php";

//include activity, events, meeting,task controllerrs
include "../controllers/activity_controller.php";
include "../controllers/events_controller.php";
include "../controllers/meetings_controller.php";
include "../controllers/task_controller.php";
include "../controllers/assessment_controller.php";

//calendar plugin
include "../resources/calendar.php";

/* SELECT GROUPS  */

$user_id = $_SESSION["id"];

//Checks
if(!isset($_GET))
{
    header("Location: ../error/404.php");    
}

//get group ID from URL
$group_id = url_decrypt($_GET['group']);

//check if user is a member of group
if(is_member($group_id,$user_id) == false)
{
    header("Location: ../error/404.php");
}

//get group info
$group = select_group_ctrl($group_id);

//reviewee_id
$reviewee_id = $user_id;

//invites
$invites = select_user_invites_ctrl($user_id);

//get invite count
$i_count = 0;

foreach ($invites as $invite)
{
    $i_count++;
}


/************** assessmentS  **************/

$members = all_members_ctrl($group_id);

$disabled = '';

$assessment_info = get_assessment_date_ctrl($group_id);//assessment_info
//var_dump($assessment_info);

$current_date = date("Y-m-d");//current date

if(!empty($assessment_info))
{
    //get assessment_start and assessment_end
    $assessment_start = $assessment_info['assessment_start'];

    $assessment_end = $assessment_info['assessment_end'];

    if($assessment_start > $current_date || $assessment_end < $current_date)
    {
    $disabled = "disabled";
    }
}
else
{
    $disabled = 'disabled';
}



//var_dump($assessment_info);



/************** ASSESSMENT   **************/
//assesment report button
$view_assessment_report = '';

if(!empty($assessment_info))
{
    //Check if assesments have been completed and show a button allowing the user to view his assessment report
    if(check_assessment_completion_ctrl($group_id,$reviewee_id) == true)
    {
        $view_assessment_report = '<a type="button" class="btn mb-1 btn-primary" href = "assessment_report.php?group='.url_encrypt($group_id).'">View Assessment Report</a>';
    }

    
}

//var_dump(check_assessment_completion_ctrl($group_id,$reviewee_id));

//DELETE LATER!!!!!!!!!!!!!!!!!!!!!!!!
//$view_assessment_report = '<a type="button" class="btn mb-1 btn-primary" href = "assessment_report.php?group='.url_encrypt($group_id).'">View Assessment Report</a>';

/************BUTTONS*******/



$set_date= '<button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#set_assessment_date">Set Assessment Date</button>';



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ubuntu</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../images/favicon.png">
    <!-- Custom Stylesheet -->
    <link href="../../css/style.css" rel="stylesheet">

    <!---GG----->
    <link href="../../css/calendar.css" rel="stylesheet">

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="../../index.php">
                    <b class="logo-abbr" style="color:white; font-size:25px; font-family:Arial, Helvetica, sans-serif ">U </b>
                    <span class="logo-compact" style="color:white; font-size:25px; font-family:Arial, Helvetica, sans-serif ">Ubuntu</span>
                    <span class="brand-title" style="color:white; font-size:25px; font-family:Arial, Helvetica, sans-serif; font-weight:bold ">
                        Ubuntu
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">    
            <div class="header-content clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
                <!--<div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3" id="basic-addon1"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Search Dashboard" aria-label="Search Dashboard">
                        <div class="drop-down   d-md-none">
							<form action="#">
								<input type="text" class="form-control" placeholder="Search">
							</form>
                        </div>
                    </div>
                </div>-->
                <div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3"><?php echo $group['group_name']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <ul class="clearfix">
                    <li class="icons dropdown"><a href="javascript:void(0)" data-toggle="dropdown">
                                <i class="mdi mdi-email-outline"></i>
                                <span class="badge badge-pill gradient-1"><?php if($i_count != 0){echo $i_count;} ?></span>
                            </a>
                            <div class="drop-down animated fadeIn dropdown-menu">
                                <div class="dropdown-content-heading d-flex justify-content-between">
                                    <span class=""><?php echo $i_count; ?> Invite(s)</span>  
                                    <a href="javascript:void()" class="d-inline-block">
                                        <span class="badge badge-pill gradient-1"><?php echo $i_count; ?></span>
                                    </a>
                                </div>
                                <div class="dropdown-content-body">
                                    <ul>
                                        <?php foreach($invites as $row):
                                             $inv = select_invite_ctrl($row['invite_id']); 
                                                
                                        ?>
                                        <li class="notification-unread">
                                            <a href="javascript:void()">
                                                <div class="notification-content">
                                                    <div class="notification-heading">You have been invited to join 
                                                    <?php
                                                        $g = select_group_ctrl($inv['group_id']);
                                                        echo $g['group_name'];
                                                    ?>
                                                    </div>
                                                    <br>
                                                    <!--<div class="notification-heading">You have been invited to join <?php //echo $g['group_name']; ?></div>-->
                                                    <div class="button-group">
                                                    <button class="btn sm-1 btn-rounded btn-outline-success btn-xs" id="accept" name="accept" data-token = <?php echo $inv['token']; ?>>Accept</button> 
                                                    <button class="btn sm-1 btn-rounded btn-outline-danger btn-xs" id="decline" name="decline" data-token = <?php echo $inv['token']; ?>>Decline</button></div>
                                                </div>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    
                                </div>
                            </div>
                        </li>
                        <li class="icons dropdown"><a href="javascript:void(0)" data-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="badge badge-pill gradient-2">3</span>
                            </a>
                            <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
                                <div class="dropdown-content-heading d-flex justify-content-between">
                                    <span class="">2 New Notifications</span>  
                                    <a href="javascript:void()" class="d-inline-block">
                                        <span class="badge badge-pill gradient-2">5</span>
                                    </a>
                                </div>
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-success-lighten-2"><i class="icon-present"></i></span>
                                                <div class="notification-content">
                                                    <h6 class="notification-heading">Events near you</h6>
                                                    <span class="notification-text">Within next 5 days</span> 
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-danger-lighten-2"><i class="icon-present"></i></span>
                                                <div class="notification-content">
                                                    <h6 class="notification-heading">Event Started</h6>
                                                    <span class="notification-text">One hour ago</span> 
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-success-lighten-2"><i class="icon-present"></i></span>
                                                <div class="notification-content">
                                                    <h6 class="notification-heading">Event Ended Successfully</h6>
                                                    <span class="notification-text">One hour ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <span class="mr-3 avatar-icon bg-danger-lighten-2"><i class="icon-present"></i></span>
                                                <div class="notification-content">
                                                    <h6 class="notification-heading">Events to Join</h6>
                                                    <span class="notification-text">After two days</span> 
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    
                                </div>
                            </div>
                        </li>
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                                <span class="activity active"></span>
                                <img src="images/user/1.png" height="40" width="40" alt=""> 
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <span>Hello <?php echo $_SESSION['user_name'];?></span>
                                        </li>
                                        <li>
                                            <a href="app-profile.html"><i class="icon-user"></i> <span>Profile</span></a>
                                        </li>
                                        <li><a href="../auth/logout.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label"><?php echo $group['group_name']; ?></li>
                    <li>
                        <a href="group.php?group=<?php echo url_encrypt($group['group_id']); ?>" aria-expanded="false">
                            <i class="icon-home menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-label">PAGES</li>
                    <li>
                        <a href="timeline.php?group=<?php echo url_encrypt($group['group_id']); ?>" aria-expanded="false">
                        <i class="icon-menu menu-icon"></i></i> <span class="nav-text">Timeline</span>
                        </a>
                        
                    </li>
                    <li>
                        <a href="tasks.php?group=<?php echo url_encrypt($group['group_id']); ?>" aria-expanded="false">
                        <i class="icon-note menu-icon"></i></i> <span class="nav-text">Tasks</span>
                        </a>
                        
                    </li>
                    <li>
                        <a href="meetings.php?group=<?php echo url_encrypt($group['group_id']); ?>" aria-expanded="false">
                        <i class="icon-clock menu-icon"></i></i> <span class="nav-text">Meetings</span>
                        </a>
                        
                    </li>
                    <li>
                        <a href="javascript:void()" aria-expanded="false">
                        <i class="icon-bubble menu-icon"></i></i> <span class="nav-text">Chat</span>
                        </a>
                        
                    </li>
                    <li>
                        <a href="javascript:void()" aria-expanded="false">
                        <i class="icon-folder menu-icon"></i></i> <span class="nav-text">Files</span>
                        </a>
                        
                    </li>
                    <li>
                    <a href="members.php?group=<?php echo url_encrypt($group_id) ?>"aria-expanded="false">
                    <i class="icon-user menu-icon"></i></i> <span class="nav-text">Members</span>
                    </a>
                    
                    </li>
                    <li class="nav-label">App</li>
                    <li>
                        <a href="javascript:void()" aria-expanded="false">
                        <i class="icon-speedometer menu-icon"></i><span class="nav-text">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void()" aria-expanded="false">
                            <i class="icon-question menu-icon"></i><span class="nav-text">Help</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="all_groups.php">Groups</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $group['group_name']; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">assessments</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <br>
                <!--- Buttons ---->
                <div class="row">
                <div class="col-lg-12">
                <div style="display: inline-block;">
                    <?php
                    //if admin
                        if(is_admin_ctrl($group_id,$user_id))
                        {
                            echo $set_date.'    ';
                        }
                        
                        echo  $view_assessment_report;
                    ?>
                </div>
                </div>
                <div class="modal fade" id="set_assessment_date">
                     <div class="modal-dialog modal-dialog-centered" role="document">
                       <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Group Assessment Date Form</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                            <h4 class="card-title">Set Assessment Date</h4>
                            <div class="basic-form">
                            <form id="set_date_form">
                            <div class="form-group">
                                <label>Set Group assessment Date:</label>
                            <input type="date" class="form-control input-default" name="assessment_date" id="assessment_date">
                            </div>
                            </form>
                            </div>
                            <div class="alert alert-success" style="display:none" id="success"></div>
                            <div class="alert alert-danger" style="display:none" id="error"></div>

                             </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="set_date" name="set_date">Save </button>
                            </div>
                        </div>
                        </div>
                </div>
                <!----------ADD GROUP MODAL--------->
                </div>
                <br>

                <!--- assessments --->
                <div class="row">
                <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Peer assessments</h4>
                <!-- ADD HEADING HERE --->
                <div class="basic-list-group" id="comp_assessment_btns">
                     <ul class="list-group">
                        <?php 
                        foreach($members as $member): 
                            if($member['user_id'] == $user_id || check_form_ctrl($user_id,$group_id,$member['user_id'])){
                                continue;
                            }

                            

                            $m_info = select_user_ctrl($member['user_id']);
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Complete Peer Evaluation for <?php echo $m_info['f_name'].' '. $m_info['l_name']; ?> <button type="button" class="btn mb-1 btn-outline-primary btn-sm" data-toggle="modal" data-target="#assessment" data-reviewee_id="<?php echo url_encrypt($member['user_id']); ?>" onclick="get_reviewee_id(this)" <?php echo $disabled; ?>>Complete</button>
                        </li>
                        <?php endforeach; ?>
                        </ul>
                        </div>
                </div>
                </div>
                </div>
                
            <!----- PEER ASSESSME MODAL ------->
            <div class="modal fade" id="assessment">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content modal-lg">
                            <div class="modal-header">
                                    <h5 class="modal-title">Peer assessment Form</h5>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                    <p class="text-muted m-b-15 f-s-12">Assess your group member using the provided questions below. Remember to be unbiased and honest. <span style="color:red">
                                    Honor Code Applies</span>
                                    .Scale<br> 1 = 'Strongly Disagree', 2 = 'Disagree', 3 = 'Somewhat Agree', 4 = 'Agree', 5 = 'Strongly Agree'
                                </p>
                                        <div class="basic-form">
                                            <form id="peer_assessment">
                                                <h4 class="form-title"> Section I: Objective assessment </h4>
                                                <p class="text-muted m-b-15 f-s-12">This Team member:</p>
                                                <div class="form-group">
                                                    <br>
                                                    <label style="color:black">Q1: Attended meetings regularly and on time </label>
                                                    <div>
                                                        <label><input type="radio" name="question1" value="1">1</label>
                                                        <label><input type="radio" name="question1" value="2">2</label>
                                                        <label><input type="radio" name="question1" value="3">3</label>
                                                        <label><input type="radio" name="question1" value="4">4</label>
                                                        <label><input type="radio" name="question1" value="5">5</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label style="color:black">Q2: Completed assignments/tasks on time </label>
                                                    <div>
                                                        <label><input type="radio" name="question2" value="1">1</label>
                                                        <label><input type="radio" name="question2" value="2">2</label>
                                                        <label><input type="radio" name="question2" value="3">3</label>
                                                        <label><input type="radio" name="question2" value="4">4</label>
                                                        <label><input type="radio" name="question2" value="5">5</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label style="color:black">Q3: Made meaningful contributions during group meetings/discussions </label>
                                                    <div>
                                                        <label><input type="radio" name="question3" value="1">1</label>
                                                        <label><input type="radio" name="question3" value="2">2</label>
                                                        <label><input type="radio" name="question3" value="3">3</label>
                                                        <label><input type="radio" name="question3" value="4">4</label>
                                                        <label><input type="radio" name="question3" value="5">5</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label style="color:black">Q4: Demonstrated a cooperative and supportive attitude </label>
                                                    <div>
                                                        <label><input type="radio" name="question4" value="1">1</label>
                                                        <label><input type="radio" name="question4" value="2">2</label>
                                                        <label><input type="radio" name="question4" value="3">3</label>
                                                        <label><input type="radio" name="question4" value="4">4</label>
                                                        <label><input type="radio" name="question4" value="5">5</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label style="color:black">Q5: Presented his/her work in an appropriate manner </label>
                                                    <div>
                                                        <label><input type="radio" name="question5" value="1">1</label>
                                                        <label><input type="radio" name="question5" value="2">2</label>
                                                        <label><input type="radio" name="question5" value="3">3</label>
                                                        <label><input type="radio" name="question5" value="4">4</label>
                                                        <label><input type="radio" name="question5" value="5">5</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label style="color:black">Q6: Contributed Significantly to the overall outcome of the project </label>
                                                    <div>
                                                        <label><input type="radio" name="question6" value="1">1</label>
                                                        <label><input type="radio" name="question6" value="2">2</label>
                                                        <label><input type="radio" name="question6" value="3">3</label>
                                                        <label><input type="radio" name="question6" value="4">4</label>
                                                        <label><input type="radio" name="question6" value="5">5</label>
                                                    </div>
                                                </div>
                                                <h4 class="form-title"> Section II: Subjective assessment(Team Dynamics) </h4>
                                                <div class="form-group">
                                                    <label>Q7: Were any behaviours of this member particularly valuable or detrimental to the team? Briefly explain.</label>
                                                    <textarea class="form-control h-150px" rows="6" id="question7" name="question7" placeholder="eg. Nii Noi's punctuality was great for the team."></textarea> 
                                                </div>
                                                <div class="form-group">
                                                    <label>Q8: Did you learn anything new from working with this member? Briefly explain.</label>
                                                    <textarea class="form-control h-150px" rows="6" id="question8" name="question8" placeholder="eg. Ama taught me how to think critically"></textarea> 
                                                </div>
                                                <h4 class="form-title"> Section III: Feedback </h4>
                                                <div class="form-group">
                                                    <label>Please provide constructive feedback for your Team member.<span style="color:red"> Harmful/Abusive Speech is Strictly Prohibited.</span></label>
                                                    <textarea class="form-control h-150px" rows="6" id="feedback" name="feedback" placeholder=""></textarea> 
                                                </div>
                                             </form>
                                                    </div>
                                                <div class="alert alert-success" style="display:none" id="done">

                                                </div>
                                                <div class="alert alert-danger" style="display:none" id="issue">
                                                    
                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" id="complete_assessment" name="complete_assessment">Complete assessment</button>
                                                </div>

                                            </div>
                                        </div>
            </div>
 

            <!----- PEER ASSESSME MODAL ------->
                
                <!---- ROW END---->
                </div>
                    




            </div>


        </div>
            <!--**********************************
          
          Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Applied Capstone Project 2023</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="../../plugins/common/common.min.js"></script>
    <script src="../../js/custom.min.js"></script>
    <script src="../../js/settings.js"></script>
    <script src="../../js/gleek.js"></script>
    <script src="../../js/styleSwitcher.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--- ACCEPT--->
<script>
  $("#accept").click(function() {
                var token = $(this).data('token');
        $.ajax({
                    type: "POST",
                    url: "../actions/accept_invite.php",
                    data: "token=" + token,
                    success: function(data) {
                    console.log(data);
                    if (data.trim() === "Success") {
                    window.location.replace("all_groups.php");
                    } else {
                    alert("Error: " + data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error: " + textStatus);
                }
    });
  });



</script>

<!--- DECLINE--->
<script>
  $("#decline").click(function() {
                var token= $(this).data('token');
        $.ajax({
                    type: "POST",
                    url: "../actions/decline_invite.php",
                    data: "token=" + token,
                    success: function(data) {
                    console.log(data);
                    if (data.trim() === "Success") {
                    window.location.reload();
                    } else {
                    alert("Error: " + data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error: " + textStatus);
                }
    });
  });
</script>

<!------ ASSESSMENTS ------->
<script>
    var reviewee_id = '';

    function get_reviewee_id(button)
    {
        reviewee_id = $(button).data("reviewee_id");
    }

    function validateForm() {
    // Get all the form elements
    const form = document.getElementById('peer_assessment');
    const question1 = form.elements['question1'];
    const question2 = form.elements['question2'];
    const question3 = form.elements['question3'];
    const question4 = form.elements['question4'];
    const question5 = form.elements['question5'];
    const question6 = form.elements['question6'];
    const question7 = form.elements['question7'];
    const question8 = form.elements['question8'];

    // Validate the objective assessment questions
    if (!question1.value || !question2.value || !question3.value || !question4.value || !question5.value || !question6.value) {
        alert('Please answer all the objective assessment questions.');
        return false;
    }

    // Validate the subjective assessment questions
    if (question7.value.length > 500) {
        alert('Please limit your answer to question 7 to 500 characters.');
        return false;
    }

    // Validate the feedback question
    if (question8.value.length > 500) {
        alert('Please limit your answer to question 7 to 500 characters.');
        return false;
    }

    // If all the validation passes, return true
    return true;
    }


     $(document).ready(function() {
            $("#complete_assessment").click(function() {
                const comp = document.getElementById("complete_assessment");
                comp.disabled = true;

                if(validateForm()){
                if(confirm("Are you sure you want to complete")){
                
                //reviewer_id
                //alert(reviewee_id);

                // using serialize function of jQuery to get all values of form
                var formData = $("#peer_assessment").serializeArray();
                formData.push({name: "reviewee_id", value: reviewee_id});
                var serializedData = $.param(formData);

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/complete_assessment.php?group=<?php echo url_encrypt($group_id);?>",
                    type: "post",
                    data: serializedData
                });

                //console.log(serializedData);

                // Callback handler that will be called on success
                request.done(function(jqXHR, textStatus, response) {
                    
                    console.log(response.responseText.trim());
                    //redirect if successful
                    if(response.responseText.trim() == "Success"){

                        $("#done").html(response.responseText.trim());

                        // Show the #success element and fade it out after 2 seconds
                        $("#done").fadeIn().delay(2000).fadeOut(function() {
                        // Close the modal after the #success element fades out
                        $("#assessment").hide();
                        window.location.reload();
                        });
                    }
                    else
                    {
                      document.getElementById('issue').style.display = "block";
                      $("#issue").html(response.responseText.trim());
                      comp.disabled = false;
                    }
                    
                });

                // Callback handler that will be called on failure
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    // Log the error to the console
                    // show error
                    $("#result").html('There is some error while submitting the data');
                    console.error(
                        "The following error occurred: " +
                        textStatus, errorThrown
                    );
                });

                return false;
            }

        }

            });
        });
        
</script>

<!---- ---->
<script>
$(document).ready(function() {
            $("#set_date").click(function() {

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#set_date_form").serialize();
                
                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/set_assessment_date.php?group=<?php echo url_encrypt($group_id);?>",
                    type: "post",
                    data: serializedData
                });

                //console.log(serializedData);

                // Callback handler that will be called on success
                request.done(function(jqXHR, textStatus, response) {
                    
                    console.log(response.responseText.trim());
                    //redirect if successful
                    if(response.responseText.trim() == "Success"){

                        $("#success").html(response.responseText.trim());

                        // Show the #success element and fade it out after 2 seconds
                        $("#success").fadeIn().delay(2000).fadeOut(function() {
                        // Close the modal after the #success element fades out
                        $("#assessment").hide();
                        window.location.reload();
                        });
                    }
                    else
                    {
                      document.getElementById('error').style.display = "block";
                      $("#error").html(response.responseText.trim());
                    }
                    
                });

                // Callback handler that will be called on failure
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    // Log the error to the console
                    // show error
                    $("#result").html('There is some error while submitting the data');
                    console.error(
                        "The following error occurred: " +
                        textStatus, errorThrown
                    );
                });

                return false;

            });
    });
        

</script>




</body>

</html>