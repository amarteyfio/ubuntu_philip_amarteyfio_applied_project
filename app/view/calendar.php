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
//user controller
include "../controllers/user_controller.php";
//tasks controller
include "../controllers/task_controller.php";

//meetings controller
include "../controllers/meetings_controller.php";

//events controller
include "../controllers/events_controller.php";

include "../resources/calendar.php";

/* SELECT ALL USER GROUPS  */

$user_id = $_SESSION["id"];

$groups = user_groups_ctrl($user_id);

//invites
$invites = select_user_invites_ctrl($user_id);

//get invite count
$i_count = 0;

foreach ($invites as $invite)
{
    $i_count++;
}


/**** UPCOMING ******/

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
                    <li class="nav-label">Dashboard</li>
                    <li>
                        <a href="../../index.php" aria-expanded="false">
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
                            $count = 0; 
                            foreach ($groups as $group):
                                $count++;
                                if($count == 4)
                                {
                                    break;
                                }
                                $info = select_group_ctrl($group['group_id']);

                            ?>
                            <li><a href="group.php?group=<?php echo url_encrypt($group['group_id']);?>"><?php echo $info['group_name'];?></a></li>
                            <?php endforeach; ?>
                            <li><a href="all_groups.php">All Groups</a></li>
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

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Groups</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                <div style="display: inline-block;" >
                <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#add_group_modal">Create Group</button> 
                <button type="button" class="btn mb-1 btn-primary">Edit Groups</button> 
                </div>
                </div>
                </div>
                <br>

                <!----------ADD GROUP MODAL--------->
                <div class="modal fade" id="add_group_modal">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add New Group</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                <h4 class="card-title">Create New Group</h4>
                                                    <p class="text-muted m-b-15 f-s-12">Enter the Group Name</p>
                                                        <div class="basic-form">
                                                    <form id="add_group_form">
                                                        <div class="form-group">
                                                             <input type="text" class="form-control input-default" placeholder="eg. COA TEAM 3" name="g_name" id="g_name">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Group Description</label>
                                                        <textarea class="form-control h-150px" rows="6" id="g_desc" name="g_desc" placeholder="Limit - 150 Characters"></textarea>
                                                        </div>
                                                    </form>
                                                        </div>
                                                <div class="alert alert-success" style="display:none" id="success">

                                                </div>
                                                <div class="alert alert-danger" style="display:none" id="error">
                                                    
                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" id="add_grp" name="add_grp">Save </button>
                                                </div>

                                            </div>
                                        </div>
                                    
                </div>
                <!----------ADD GROUP MODAL--------->
            <div class="row">
                <?php 
                //For each group that current user is a member of list the group
                foreach ($groups as $group):
                    $info = select_group_ctrl($group['group_id']);
                ?>
                <!--- Card Start-->
                <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-header">Group</div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $info['group_name']; ?></h5>
                                        <p class="card-text"><span><?php echo $info['group_desc']; ?></span><br><br>
                                        Members:<br>
                                         This team currently has <?php echo count_group_ctrl($info['group_id']); ?> Member(s)</p><a href="group.php?group=<?php echo url_encrypt($info['group_id']); ?>" class="btn btn-primary">Go To</a>
                                    </div>
                                </div>
                </div>
                <!---- Card End-->
                <?php 
                //end for each loop
                endforeach;
                ?>
                    
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
                <!--<p>Applied Capstone Project 2023</p>-->
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

    <!------ FOR ADDING GROUPS --------->
    <script>
        $(document).ready(function() {
            $("#add_grp").click(function() {

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#add_group_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/create_group.php",
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
                        $("#add_group_modal").hide();
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