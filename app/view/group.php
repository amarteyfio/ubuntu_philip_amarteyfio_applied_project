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


//invites
$invites = select_user_invites_ctrl($user_id);

//get invite count
$i_count = 0;

foreach ($invites as $invite)
{
    $i_count++;
}


/***** CALENDAR  *******/
$meetings = select_group_meetings_ctrl($group_id);//meetings

$events = select_group_events_ctrl($group_id);//events

$tasks = get_user_tasks_ctrl($group_id,$user_id); //tasks


/****** PROGESS BARS  *****/
$group_tasks = select_group_tasks_ctrl($group_id); //all tasks

if(!empty($group_tasks))
{
//tasks
$comp = 0; //completed tasks
$all_tasks = 0; //in progess tasks
$c_time = date('Y-m-d'); //current time

foreach ($group_tasks as $t)
{
    $all_tasks++; //increment task number
    if($t['status'] == 1 || $t['status'] == 2)
    {
        $comp++; //increment completed task number
    }
}

$task_progress = round(($comp/$all_tasks)*100);

}
else
{
    $task_progress = 0;
}
//timeline
$n = 0;
$timeline_progress = 0;
if(count($events) > 1)
{
    foreach($events as $e)
{
    $n++; //increase n
}

$e_event = $events[0]; //earliest event

$l_event = $events[$n-1]; //last event

$total_time = strtotime($l_event['event_date']) - strtotime($e_event['event_date']);

$time_now = time() - strtotime($e_event['event_date']);

$timeline_progress = round(($time_now / $total_time) * 100);

}
elseif(count($events) == 0)
{
    $timeline_progress = 'N/A';
}
else
{
    $s_event = $events[0]; //event



    $timeline_progress = round(((strtotime($s_event['event_date']) - time())/strtotime($s_event['event_date']))*100); //time percentage

}

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
                        
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <br>
                <div class="row">
                    <!--- CALENDAR -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="calendar">
                                    <div class="calendar_form">
                                    <div class="basic-form">
                                </div>
                                    </div>
                                <?php 
                                    $current_month = date('Y-m');
                                    $calendar = new Calendar();
                                    //meetings
                                    foreach($meetings as $meeting)
                                    {
                                        $mt = $meeting['begins_at'];

                                        $mt = date('Y-m-d',strtotime($mt));

                                        $calendar->add_event($meeting['meeting_title'],$mt,1,'blue');
                                    }

                                    
                                    //tasks
                                    foreach ($tasks as $task)
                                    {
                                        $calendar->add_event($task['task_title'], $task['deadline'],1,'red');
                                    }
                                    
                                    //events
                                    foreach($events as $event)
                                    {
                                        if(strpos($event['event_date'],$current_month) === 0)
                                        {
                                            $calendar->add_event($event['event_tit'],$event['event_date'],1,'yellow');
                                        }
                                    }

                                    echo $calendar;
                                    
                                    ?>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--- CALENDAR END--->
                    
                    <!--- ASSESSMENT ------------------------------->
                    

                    <!--ASSESSMENT--->

                    <!--- PROGRESS-->
                    <div class="col-md-3">
                           <div>
                            <a href="assessments.php?group=<?php echo url_encrypt($group_id) ?>" type="button" class="btn mb-1 btn-primary btn-lg">Assessments</a>
                           </div>
                           <br>
                           <br>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Progress</h4>
                                <h5 class="mt-3">Tasks <span class="float-right"><?php echo $task_progress ?>%</span></h5>
                                <div class="progress" style="height: 9px">
                                    <div class="progress-bar bg-danger wow  progress-" style="width: <?php echo $task_progress ?>%;" role="progressbar"><span class="sr-only"><?php echo $task_progress ?>% Complete</span>
                                    </div>
                                </div>
                                <h5 class="mt-3">Timeline <span class="float-right"><?php echo $timeline_progress ?>%</span></h5>
                                <div class="progress" style="height: 9px">
                                    <div class="progress-bar bg-info wow  progress-" style="width: <?php echo $timeline_progress ?>%;" role="progressbar"><span class="sr-only"><?php echo $timeline_progress ?>% Complete</span>
                                    </div>
                                </div>
                                <?php 
                                    $total_progress = 0;
                                    if($timeline_progress !== "N/A")
                                    {
                                        $total_progress = (($task_progress + $timeline_progress)/200)*100;
                                    }
                                    elseif ($timeline_progress === "N/A")
                                    {
                                        $total_progress = (($task_progress + 0)/200)*100;
                                    }
                                ?>
                                <h5 class="mt-3">Total Progress <span class="float-right"><?php echo round($total_progress); ?>%</span></h5>
                                <div class="progress" style="height: 9px">
                                    <div class="progress-bar bg-success wow  progress-" style="width: <?php echo round($total_progress) ?>%;" role="progressbar"><span class="sr-only"><?php echo $total_progress ?>% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PROGRESS BAR -->
                   
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

</body>

</html>