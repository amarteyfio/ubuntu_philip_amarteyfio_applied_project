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

//get group members
$group_members = all_members_ctrl($group_id);


//invites
$invites = select_user_invites_ctrl($user_id);

//get invite count
$i_count = 0;

foreach ($invites as $invite)
{
    $i_count++;
}

//roles
$roles = select_roles_ctrl($group_id);
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
                    <a href="javascript:void()"aria-expanded="false">
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Members</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                
                <div class="row">
                <div class="col-lg-12">
                <div style="display: inline-block;">
                <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#invite">Invite Members</button> 
                <button type="button" class="btn mb-1 btn-primary" data-toggle="modal" data-target="#create_role">Create Roles</button> 
                
                </div> 
                   
                </div>
                </div>

                <!---- ADD MEMBER MODAL----->
                <div class="modal fade" id="invite">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                    <h5 class="modal-title">Add Member</h5>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <h4 class="card-title">ADD GROUP MEMBERS</h4>
                                    <p class="text-muted m-b-15 f-s-12">Enter Platform Email Below:</p>
                                        <div class="basic-form">
                                            <form id="invite_members_form">
                                                <div class="form-group">
                                                    <input type="email" class="form-control input-default" placeholder="eg. x.xxx@ashesi.edu.gh" name="email" id="email">
                                                </div>
                                             </form>
                                                    </div>
                                                <div class="alert alert-success" style="display:none" id="success">

                                                </div>
                                                <div class="alert alert-danger" style="display:none" id="error">
                                                    
                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" id="send_invite" name="send_invite">Send </button>
                                                </div>

                                            </div>
                                        </div>
                </div>
 


                <!---- ADD MEMBER MODAL---->

                <!---- CREATE ROLES----->
                <div class="modal fade" id="create_role">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                    <!--<h5 class="modal-title">Add Member</h5>--><h4 class="card-title">CREATE ROLES</h4>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                    <p class="text-muted m-b-15 f-s-12">Enter Role Below:</p>
                                        <div class="basic-form">
                                            <form id="create_role_form">
                                                <div class="form-group">
                                                    <input type="email" class="form-control input-default" placeholder="eg. Frontend Engineer" name="role" id="role">
                                                </div>
                                             </form>
                                                    </div>
                                                <div class="alert alert-success" style="display:none" id="success">

                                                </div>
                                                <div class="alert alert-danger" style="display:none" id="error">
                                                    
                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" id="add_role" name="add_role">Send </button>
                                                </div>

                                            </div>
                                        </div>
                </div>
 


                <!----CREATE ROLES---->

                <!---- SET ROLES----->
                <div class="modal fade" id="set_role_modal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                    <!--<h5 class="modal-title">Add Member</h5>--><h4 class="card-title">SET ROLES</h4>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                        <div class="basic-form">
                                            <form id="set_role_form">
                                                <div class="form-group">
                                                <label class="mr-sm-2">Assign Role:</label>
                                                <select class="custom-select mr-sm-2" id="set" name="set">
                                                    <?php 
                                                        foreach ($roles as $r):

                                                            
                                                    ?>
                                                    <option value="<?php echo ($r['role_id']) ?>"><?php echo $r['role_name']; ?></option>
                                                    <?php 
                                                    endforeach;
                                                    ?>
                                                    
                                                </select>
                                                </div>
                                             </form>
                                                    </div>
                                                <div class="alert alert-success" style="display:none" id="success">

                                                </div>
                                                <div class="alert alert-danger" style="display:none" id="error">
                                                    
                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" id="set_role" name="set_role">Assign </button>
                                                </div>

                                            </div>
                                        </div>
                </div>
 


                <!----SET ROLES---->
            <br>
            <div class="row">
                    
                <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <h4>Team Members</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>E-mail</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $n = 1;
                                            foreach($group_members as $group_member):
                                                    //user details
                                                    $details = select_user_ctrl($group_member['user_id']);

                                                    //user_id
                                                    

                                                    //remove buttons
                                                    $remove = '<button class="btn mb-1 btn-outline-primary btn-sm" data-member-id="'. url_encrypt($details['id']) .'" onclick="deleteRecord(this)">Remove</button>';
                                                    $roles = '<button class="btn mb-1 btn-outline-primary btn-sm" data-toggle="modal" data-target="#set_role_modal" data-mem_id="'.url_encrypt($details['id']).'" onclick="get_member_id(this)">Set Role</button>';
                                                    //admin
                                                    $admin = false;

                                                    //check if admin
                                                    if(is_admin_ctrl($group_member['group_id'],$group_member['user_id']))
                                                    {
                                                        $admin = true;
                                                        $remove = '';
                                                    
                                                    }
                                            ?>
                                            <tr>
                                                <th><?php echo $n; ?></th>
                                                <td><?php echo $details['f_name']. " ". $details['l_name']; ?></td>
                                                <td><span><?php echo $details['email'];?></span> </td>
                                                <td>
                                                    <?php 
                                                        if($admin == true)
                                                        {
                                                            echo "Administrator/". select_user_role_ctrl($details['id'],$group_id);
                                                        }
                                                        else
                                                        {
                                                            echo "Member/". select_user_role_ctrl($details['id'],$group_id);
                                                        }
                                                    ?>


                                                </td>
                                                <td class>
                                                    <div style="display:inline-block">
                                                    <?php
                                                     if(is_admin_ctrl($group_id,$user_id) == true)
                                                        {
                                                            echo $remove;
                                                            echo "  ";
                                                            echo $roles;
                                                            
                                                        }
                                                        else
                                                        {
                                                            echo "ADMIN ONLY";
                                                        } 

                                                        
                                                    ?>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
                                                $n++;
                                            endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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


<!------ INVITES --------->
    <script>
        $(document).ready(function() {
            $("#send_invite").click(function() {

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#invite_members_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/send_invite.php?group=<?php echo url_encrypt($group_id);?>",
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
                        $("#create_roles").hide();
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
<!------ INVITES ---------->

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
<!------- ACCEPT-------->

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
<!-------DECLINE-------------------->

<!---------SCRIPT TO DELETE MEMBER----------->
<script>
function deleteRecord(button) {
  var memberId = $(button).data("member-id");
  if (confirm("Are you sure you want to delete this record?")) {
    // If the user confirms the deletion, send an AJAX request to remove_member.php
    $.ajax({
      url: "../actions/remove_member.php?group=<?php echo url_encrypt($group_id)?>",
      method: "POST",
      data: { member_id: memberId}
    })
    .done(function(response) {
       console.log(response);
       window.location.reload();
      
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      // There was an error with the AJAX request
      console.log(errorThrown);
      
    });
  }
}
</script>

<!------------------- CREATE ROLE ---------------------------------->
<script>
    $(document).ready(function() {
            $("#add_role").click(function() {

                const btn = document.getElementById("add_role");

                btn.disabled = true;

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#create_role_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/add_roles.php?group=<?php echo url_encrypt($group_id);?>",
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
                        $("#create_role").hide();
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

<!------------SET ROLE-------------------->
<script>
 var member_id = '';

function get_member_id(button)
{
    member_id = $(button).data("mem_id");

}

    $(document).ready(function() {
            $("#set_role").click(function() {
                
                
                // using serialize function of jQuery to get all values of form
                 // using serialize function of jQuery to get all values of form
                 var formData = $("#set_role_form").serializeArray();
                formData.push({name: "member_id", value: member_id});
                var serializedData = $.param(formData);
                //var serializedData = $("#set_role_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/set_roles.php?group=<?php echo url_encrypt($group_id);?>",
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
                        $("#create_role").hide();
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