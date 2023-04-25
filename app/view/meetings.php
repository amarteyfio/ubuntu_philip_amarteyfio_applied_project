<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//INCLUDE CORE
include "../../config/core.php";

//CHECK FOR LOGIN
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
}
date_default_timezone_set('UTC');

//group controller
include "../controllers/group_controller.php";

//include user controller
include "../controllers/user_controller.php";

//include meeting controller
include "../controllers/meetings_controller.php";

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


//invites
$invites = select_user_invites_ctrl($user_id);

//get invite count
$i_count = 0;

foreach ($invites as $invite)
{
    $i_count++;
}


//get meetings for group
$meetings = select_group_meetings_ctrl($group_id);

//attendance record
$attendance = select_attendance_ctrl($user_id,$group_id);


//check attendance log
attend_log_ctrl($group_id);

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
                        <a href="javascript:void()" aria-expanded="false">
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Meetings</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <br>
                <div class="row">
                <div class="col-lg-12">
                
                    <?php 
                    if(is_admin_ctrl($group_id,$user_id))
                    {
                        echo 
                        '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-meeting">New Meeting</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="">Edit Meetings</button>
                        <a type="button" class="btn btn-primary" href = "attendance_records.php?group='.url_encrypt($group_id).'">Attendance Records</a>';
                        

                    }
                    
                    
                    ?>

                    <!---- MODAL ------------->
                    <div class="modal fade" id="add-meeting">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">New Meeting</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                 <h4 class="card-title"></h4>
                                    <p class="text-muted m-b-15 f-s-12">Meeting Title</p>
                            <div class="basic-form">
                                <form id="add_meeting_form">
                            <div class="form-group">
                                <input type="text" class="form-control input-default" placeholder="eg. Group Meeting 1" name="m_title" id="m_title">
                            </div>
                            <div class="form-group">
                                <label>Meeting Description</label>
                                <textarea class="form-control h-150px" rows="6" id="m_desc" name="m_desc" placeholder="Limit - 150 Characters"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="mr-sm-2">Assigned To:</label>
                                <select class="custom-select mr-sm-2" id="m_type" name="m_type">
                                    <option value="Online">Online</option>
                                    <option value="In-Person">In-Person</option>
                                </select>
                                        </div>
                            <div class="form-group">
                            <label>Meeting Location/Link</label>
                                <input type="text" class="form-control input-default" placeholder="eg. Norton Mutolsky" name="m_loc" id="m_loc">
                            </div>
                            <div class="example">
                            <label>Begins At:</label>
                                <div class="input-group">
                                <input type="datetime-local" class="form-control" id="m_start" name="m_start" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
                            </div>
                            <br>
                            </div>
                            <div class="example">
                            <label>Ends At:</label>
                                <div class="input-group">
                                <input type="datetime-local" class="form-control" id="m_end" name="m_end" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
                            </div>
                            <br>
                            </div>                                    
                            </form>
                            </div>
                            <div class="alert alert-success" style="display:none" id="success">

                            </div>
                            <div class="alert alert-danger" style="display:none" id="error">
                                                    
                            </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="meeting_add" name="meeting_add"> Add Meeting  </button>
                            </div>

                        </div>
                    </div>
                    </div>
                                        
                </div>
                </div>
                <br>
                <div class="row">
                    <!--- CALENDAR -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="meetings">
                                <h4 class="card-title">Meetings</h4>
                                <!-- Nav tabs -->
                                <div class="default-tab">
                                    <ul class="nav nav-tabs mb-4" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#upcoming_meetings">Upcoming Meetings</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#past_meetings">Past Meetings</a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content">
                                        <!----- UPCOMING MEETINGS------------>
                                        <div class="tab-pane fade show active" id="upcoming_meetings" role="tabpanel">
                                            <div class="p-t-15">
                                            <!---- Tab Content goes here ------>
                                            <div class="basic-list-group">
                                            <ul class="list-group">
                                                <!--- UPCOMING MEETINGS---->
                                                <?php
                                                $c_time = date('Y-m-d H:i:s');
                                                
                                                foreach($meetings as $meeting):

                                                    
                                                    $button = '<button class="btn mb-1 btn-primary btn-sm" data-meeting-id="'. url_encrypt($meeting['meeting_id']) .'" onclick="record_attendance(this)">Attend</button>';


                                                    //format datetime for Display
                                                    $meet_obj = new DateTime($meeting['begins_at']); //meeting start object

                                                    $end_obj = new DateTime($meeting['ends_at']); //meeting end object

                                                    // Format the datetime in the desired format
                                                    $start_time = $meet_obj->format('d/m/Y, h:i A');


                                                    //get duration
                                                    $duration = $end_obj->diff($meet_obj)->i;
                                                   
                                                    //if attendance has already been recorded

                                                    if(check_attendance_ctrl($user_id,$meeting['meeting_id']) === true)
                                                    {
                                                        $button = '<button class="btn mb-1 btn-primary btn-sm" disabled alt = "Attendance not Available">Attend</button>';
                                                    }

                                                    
                                                    //if meeting has not yet begun
                                                    if($meeting['begins_at'] > $c_time)
                                                    {
                                                        $button = '<button class="btn mb-1 btn-primary btn-sm" disabled alt = "Attendance not Available">Attend</button>';
                                                    }

                                                    //if it is 15 minutes past the meeting
                                                    $now = new DateTime();
                                                    
                                                    $late = $now->diff($meet_obj)->i;

                                                    if($late >= 15)
                                                    {
                                                        $button = '<button class="btn mb-1 btn-primary btn-sm" disabled alt = "Attendance not Available">Attend</button>';
                                                    }


                                                
                                                
                                                
                                                ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo $start_time ?> at 
                                                <?php 
                                                if($meeting['meeting_type'] === 'In-Person')
                                                {
                                                    echo $meeting['meeting_loc'];
                                                }
                                                elseif($meeting['meeting_type'] === 'Online')
                                                {
                                                    echo $meeting['meeting_loc'];
                                                    //echo '<a href = "'. $meeting['meeting_loc'] .'">'. $meeting['meeting_loc'] .'</a>';
                                                }
                                                
                                                ?>
                                                     <div>
                                                     <a tabindex="0" class="btn btn-primary btn mb-1 btn-sm" role="button" data-toggle="popover" data-trigger="focus" title="Description" data-content="<?php echo $meeting['meeting_desc'] ?>">View</a> 
                                                     <?php echo $button; ?>
                                                    </div>  
                                                </li>
                                                <?php endforeach ?>
                                                
                                            </ul>
                                            </div>
                                            

                                            </div>
                                        </div>
                                        <!----- UPCOMING MEETINGS------------>




                                        <!--------- PAST MEETINGS --------------->
                                        <div class="tab-pane fade" id="past_meetings">
                                            <div class="p-t-15">
                                            <div class="basic-list-group">
                                            <ul class="list-group">
                                                <?php foreach($attendance as $row):
                                                    
                                                        $meet = select_meeting_ctrl($row['meeting_id']);
                                                        
                                                        //time object
                                                        $t = new DateTime($meet['begins_at']);

                                                        $m_time = $t->format('d/m/Y, h:i A');


                                                ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo $m_time; ?> at <?php echo $meet['meeting_loc'];?> 
                                                
                                                <?php

                                                if($row['status'] == 0)
                                                {
                                            
                                                    echo '<span class="badge badge-success badge-pill">Present</span>';
                                                }
                                                elseif($row['status'] == 1)
                                                {
                                                    echo '<span class="badge badge-warning badge-pill">Late</span>';
                                                }
                                                elseif($row['status'] == 2)
                                                {
                                                    echo '<span class="badge badge-danger badge-pill">Absent</span>';
                                                }
                                                


                                                ?>


                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            </div>
                                            </div>
                                        </div>

                                        <!--------- PAST MEETINGS --------------->
                                    </div>
                                </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--- CALENDAR END--->
                    
                    <!--- ASSESSMENT ------------------------------->
                    

                    <!--ASSESSMENT--->
                    
                   
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

<!--- ADD A TASK --->
   
<script>
        $(document).ready(function() {
            $("#meeting_add").click(function() {

                const button = document.getElementById("meeting_add");

                button.disabled = true;

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#add_meeting_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/add_meeting.php?group=<?php echo url_encrypt($group_id);?>",
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
                        $("#success").fadeIn().delay(2000).fadeOut( function() {
                        // Close the modal after the #success element fades out
                        $("#add-task").hide();

                        // Reload the page after the modal is hidden
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                        });
                        
                    }
                    else
                    {
                      document.getElementById('error').style.display = "block";
                      $("#error").html(response.responseText.trim());
                      button.disabled = false;
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


     <!--------- ATTEND ---------------->
     <script>
        function record_attendance(button) {
        var meeting_id = $(button).data("meeting-id");
        if (confirm("Confirm Attendance")) {
            // If the user confirms the deletion, send an AJAX request to remove_member.php
            $.ajax({
            url: "../actions/record_attendance.php?group=<?php echo url_encrypt($group_id)?>",
            method: "POST",
            data: { meeting_id: meeting_id}
            })
            .done(function(response) {
            console.log(response);
            if(response.trim() == "Success")
            {
            window.location.reload();
            }
            
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
            // There was an error with the AJAX request
            console.log(errorThrown);
            
            });
        }
        }
</script>

</body>

</html>