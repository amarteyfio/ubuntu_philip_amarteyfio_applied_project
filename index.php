<?php 
//INCLUDE CORE
include "config/core.php";

//CHECK FOR LOGIN
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: app/login/login.php");
    exit;
}

//group controller
include "app/controllers/group_controller.php";

//user controller
include "app/controllers/user_controller.php";

//general controller
include "app/controllers/general_controller.php";

//tasks controller
include "app/controllers/task_controller.php";

//meetings controller
include "app/controllers/meetings_controller.php";

//events controller
include "app/controllers/events_controller.php";

//activity controller
include "app/controllers/activity_controller.php";

//calendar
include "app/resources/calendar.php";

/* SELECT FROM DB  */
//user id
$user_id = $_SESSION["id"];

//groups
$groups = user_groups_ctrl($user_id);

//invites
$invites = select_user_invites_ctrl($user_id);

//get invite count
$i_count = 0;

foreach ($invites as $invite)
{
    $i_count++;
}


 /***** UPCOMING ********/
 $user_groups = user_groups_ctrl($user_id);

 $user_meetings = array(); //user meetings
 $user_events = array(); //user events
 $user_tasks = array(); //user tasks

 $i = 0; //iterator
 $k = 0; //iterator
 $j = 0; //iterator
 
 //meetings and events
 foreach($user_groups as $g)
 {
    $g_id = $g['group_id']; //group id
    $meetings = select_group_meetings_ctrl($g_id);

    $events = select_group_events_ctrl($g_id);

    $tasks = get_user_tasks_ctrl($g_id, $user_id);
    

    foreach($meetings as $m)
    {
        $user_meetings[$i] = $m['meeting_id'];
        $i++;

        if($i == 3)
        {
            break;
        }
    }

    $date = date('Y-m-d');
    foreach($events as $e)
    {
        if($e['event_date'] > $date)
        {
            $user_events[$j] = $e['event_id'];
            $j++;

           
        }

        if($j == 5)
        {
            break;
        }
        

    }


    foreach($tasks as $t)
    {
        $user_tasks[$k] = $t['task_id'];
        $k++;

        if($k == 5)
        {
            break;
        }
    }

 }




 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />
  
    <title>Ubuntu Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

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
                <a href="index.php">
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
                                        <li><a href="app/auth/logout.php"><i class="icon-key"></i> <span>Logout</span></a></li>
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
                    <li class="nav-label">Dashboard</li>
                    <li>
                        <a href="index.php" aria-expanded="false">
                            <i class="icon-home menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-label">GO TO</li>
                    <li>
                        <a href="javascript:void()" aria-expanded="false">
                        <i class="icon-notebook menu-icon"></i></i> <span class="nav-text">Calendar</span>
                        </a>
                        
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="icon-grid menu-icon"></i><span class="nav-text">Groups</span>
                        </a>
                        <ul aria-expanded="false">
                            <?php
                            $t_count = 0; 
                            foreach ($groups as $group):
                                $t_count++;
                                if($t_count == 4)
                                {
                                    break;
                                }
                                $info = select_group_ctrl($group['group_id']);

                            ?>
                            <li><a href="app/view/group.php?group=<?php echo url_encrypt($group['group_id']);?>"><?php echo $info['group_name'];?></a></li>
                            <?php endforeach; ?>
                            <li><a href="app/view/all_groups.php">All Groups</a></li>
                        </ul>
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

            <div class="container-fluid mt-3">
                <div class="row">
                    
                </div>

                <div class="row">
                    
                </div>

                

                <div class="row">
                        
                            
            
                        
                </div>
                
                <div class="row">
                    

                </div>

                <div class="row">
                    <div class="col-lg-9">
                    <div class="card">
                            <div class="card-body" style="font-size: 18px;">
                                <h4 style="align-content: center;">UPCOMING</h4>
                                <div id="activity">
                                    <!-------MEETINGS --->
                                    <?php 
                                    //meetings
                                    foreach($user_meetings as $m):
                                        $meet = select_meeting_ctrl($m);

                                        $grp = select_group_ctrl($meet['group_id']);
                                    
                                    ?>
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <div class="media-body">
                                            <h5>Meeting for <?php echo $grp['group_name']; ?></h5>
                                            <p class="mb-0"><?php echo $meet['meeting_loc'];?>
                                        </div><span class="text-muted "><?php echo date('jS F Y , H:i A',strtotime($meet['begins_at'])); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <!---- EVENTS ----->
                                    <?php 
                                    foreach($user_events as $row): 
                                        $eve = select_event_ctrl($row);
                                        
                                        $grp = select_group_ctrl($eve['group_id']);
                                    
                                    ?>
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <div class="media-body">
                                            <h5>Milestone for <?php echo $grp['group_name']; ?> </h5>
                                            <p class="mb-0"><?php echo $eve['event_tit']; ?></p>
                                        </div><span class="text-muted "><?php echo date('jS F Y', strtotime($eve['event_date'])); ?></span>
                                    </div>
                                    <?php endforeach; ?>

                                    <!---- TASKS ----->
                                    <?php 
                                    
                                    
                                    foreach($user_tasks as $ut):
                                         $tsk = select_task_ctrl($ut);
                                         $grp = select_group_ctrl($tsk['group_id']);
                                    ?>
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <div class="media-body">
                                            <h5>Task for <?php echo $grp['group_name']; ?></h5>
                                            <p class="mb-0"><?php echo $tsk['task_title']; ?></p>
                                        </div><span class="text-muted "><?php echo date('jS F Y', strtotime($tsk['deadline'])); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <!-----ACTIVITIES --------->
                    <div class="col-xl-3 col-lg-8 col-sm-6 col-xxl-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Activity</h4>
                                <div id="activity">
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <img width="35" src="./images/avatar/1.jpg" class="mr-3 rounded-circle">
                                        <div class="media-body">
                                            <h5>Task Completed!</h5>
                                            <p class="mb-0">Developing Home Page JS <br> Assigned to: Kwame</p>
                                        </div>
                                    </div>
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <img width="35" src="./images/avatar/2.jpg" class="mr-3 rounded-circle">
                                        <div class="media-body">
                                            <h5>New Task Created</h5>
                                            <p class="mb-0"> Design Entity Diagram <br> For: Group C</p>
                                        </div>
                                    </div>
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <img width="35" src="./images/avatar/2.jpg" class="mr-3 rounded-circle">
                                        <div class="media-body">
                                            <h5>3 Order Pending</h5>
                                            <p class="mb-0">I shared this on my fb wall a few months back,</p>
                                        </div>
                                    </div>
                                    <div class="media border-bottom-1 pt-3 pb-3">
                                        <img width="35" src="./images/avatar/2.jpg" class="mr-3 rounded-circle">
                                        <div class="media-body">
                                            <h5>Join new Manager</h5>
                                            <p class="mb-0">I shared this on my fb wall a few months back,</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                        
                </div>
            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        
        
        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright &copy; UI Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a> 2018</p>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
    <!-- Datamap -->
    <script src="./plugins/d3v3/index.js"></script>
    <script src="./plugins/topojson/topojson.min.js"></script>
    <script src="./plugins/datamaps/datamaps.world.min.js"></script>
    <!-- Morrisjs -->
    <script src="./plugins/raphael/raphael.min.js"></script>
    <script src="./plugins/morris/morris.min.js"></script>
    <!-- Pignose Calender -->
    <script src="./plugins/moment/moment.min.js"></script>
    <script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
    <!-- ChartistJS -->
    <script src="./plugins/chartist/js/chartist.min.js"></script>
    <script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>



    <script src="./js/dashboard/dashboard-1.js"></script>

<!--- ACCEPT--->
<script>
  $("#accept").click(function() {
                var token = $(this).data('token');
        $.ajax({
                    type: "POST",
                    url: "app/actions/accept_invite.php",
                    data: "token=" + token,
                    success: function(data) {
                    console.log(data);
                    if (data.trim() === "Success") {
                    window.location.replace("app/view/all_groups.php");
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
                    url: "app/actions/decline_invite.php",
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
