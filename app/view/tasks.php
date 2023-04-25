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

//includes task controller
include "../controllers/task_controller.php";

//calendar class
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

///TASK MANAGEMENT //

$tasks = select_group_tasks_ctrl($group_id);


//USERS //

$g_members = all_members_ctrl($group_id);


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

    <link href="../../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="../../plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Date picker plugins css -->
    <link href="../../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- Daterange picker plugins css -->
    <link href="../../plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../../plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="../../plugins/toastr/css/toastr.min.css" rel="stylesheet">

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
                        <a href="javascript:void()" aria-expanded="false">
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Tasks</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <br>

                <!----BUTTTON GROUP---->
                <div class="row">
                    <div class="col-lg-12">
                <div style="display: inline-block;">
        
                <button type="button" class="btn mb-1 btn-primary btn-l dropdown-toggle" id="filter_button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filter By</button>
                <div class="dropdown-menu" aria-labelledby="filter_button">
                    <a class="dropdown-item" href="#" value="All">All</a>
                     <a class="dropdown-item" href="#" value = "<?php echo $_SESSION['user_name']; ?>">For You</a>
                     <a class="dropdown-item" href="#" value = "In Progress">Available</a> 
                     <a class="dropdown-item" href="#" value = "Completed">Completed</a>
                </div>
        
                    

                <button type="button" class="btn mb-1 btn-primary btn-l" data-toggle="modal" data-target="#add-task">Add Tasks</button> 

                
                </div>
                </div> 
                </div>
                <br>
            

                <!------ ADD TASK MODAL ---------->
                <div class="modal fade" id="add-task">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Task</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                 <h4 class="card-title"></h4>
                                    <p class="text-muted m-b-15 f-s-12">Task Title</p>
                            <div class="basic-form">
                                <form id="add_task_form">
                            <div class="form-group">
                                <input type="text" class="form-control input-default" placeholder="eg. Data Collection, Data analysis" name="task_name" id="task_name">
                            </div>
                            <div class="form-group">
                                <label>Task Description</label>
                                <textarea class="form-control h-150px" rows="6" id="t_desc" name="t_desc" placeholder="Limit - 150 Characters"></textarea>
                            </div>
                            <div class="example">
                            <label>Deadline</label>
                                <div class="input-group">
                                <input type="date" class="form-control" id="deadline" name="deadline" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
                            </div>
                            <br>
                            </div>                                
                                <div class="form-group">
                                <label class="mr-sm-2">Assigned To:</label>
                                <select class="custom-select mr-sm-2" id="assigned_to" name="assigned_to">
                                    <?php 
                                        foreach ($g_members as $member):

                                            $u_info = select_user_ctrl($member['user_id']);
                                    ?>
                                    <option value="<?php echo url_encrypt($member['user_id']) ?>"><?php echo $u_info['f_name']." ". $u_info['l_name']; ?></option>
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
                                <button type="button" class="btn btn-primary" id="add_task" name="add_task"> Add </button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-------- ADD TASK MODAL ---------->


                <div class="row">
                    <!--- CALENDAR -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <h4 class="card-title">Tasks</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered verticle-middle" id="task_table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Task</th>
                                                <th scope="col">Deadline</th>
                                                <th scope="col">Assigned To:</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            foreach ($tasks as $task): ?>
                                            <tr>
                                                <td><?php echo $task['task_title']; ?></td>
                                                <td><?php echo $task['deadline']; ?></td>
                                                <td>
                                                    <span>
                                                        <?php 
                                                        $assigned = select_user_ctrl($task['assigned_to']);
                                                        echo $assigned['f_name']." ". $assigned['l_name'];
                                                        ?>
                                                    </span>
                                                </td>
                                                <td>
                                                <div class="bootstrap-badge">
                                                    <?php
                                                    if($task['status'] == 0)
                                                    {
                                                        echo '<span class="badge badge-info">In Progress</span>';
                                                    }
                                                   elseif($task['status'] == 1)
                                                   {
                                                        echo '<span class="badge badge-success">Completed</span>';
                                                   }
                                                   elseif($task['status'] == 2)
                                                   {
                                                        echo '<span class="badge badge-warning">Late</span>';
                                                   }

                                                    ?>
                                                </div>
                                            </td>
                                                <td>
                                                <span>
                                                    <?php 
                                                        //actions
                                                        if (is_admin_ctrl($group_id,$user_id))
                                                        {
                                                            echo 
                                                            '<a href="#" data-toggle="tooltip" data-placement="top" title="Mark as Completed"><i class="fa fa-check color-success m-r-5" onclick="complete_task('.$task['task_id'].')"></i> </a>
                                                            <a href="#" data-toggle="tooltip" data-placement="top" title="Edit Task"><i class="fa fa-pencil color-muted m-r-5"></i> </a>
                                                            <a href="#" data-toggle="tooltip" data-placement="top" title="Delete Task"><i class="fa fa-close color-danger"></i></a>';
                                                        }
                                                        else
                                                        {
                                                            if($task['assigned_to'] == $user_id)
                                                            {
                                                                if($task['status'] == 0)
                                                                {
                                                                    echo '<a href="#" data-toggle="tooltip" data-placement="top" title="Mark as Completed"><i class="fa fa-check color-success m-r-5" onclick="complete_task('.$task['task_id'].')"></i> </a>';
                                                                }
                                                                elseif($task['status'] == 1 || $task['status'] == 2)
                                                                {
                                                                    echo 'Already Completed';
                                                                }

                                
                                                            }
                                                            else
                                                            {
                                                                echo 'Not Available';
                                                            }
                                                        }
                                                    
                                                    ?>
                                                </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--- CALENDAR END--->
                    
                    <!--- ASSESSMENT ------------------------------->
                    

                    <!--ASSESSMENT--->

                    <!--- PROGRESS-->
                
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
    <script src="../../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../../plugins/moment/moment.js"></script>
    <script src="../../plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <!-- Clock Plugin JavaScript -->
    <script src="../../plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <!-- Color Picker Plugin JavaScript -->
    <script src="../../plugins/jquery-asColorPicker-master/libs/jquery-asColor.js"></script>
    <script src="../../plugins/jquery-asColorPicker-master/libs/jquery-asGradient.js"></script>
    <script src="../../plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="../../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="../../plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

    <script src="../../js/plugins-init/form-pickers-init.js"></script>

    <!-- Toastr -->
    <script src="../../plugins/toastr/js/toastr.min.js"></script>
    <script src="../../plugins/toastr/js/toastr.init.js"></script>

   
    <!--- ACCEPT DECLINE SCRIPT--->
    <?php include "../resources/accept_decline.php"; ?>


    <!--- ADD A TASK --->
   
    <script>
        $(document).ready(function() {
            $("#add_task").click(function() {

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#add_task_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../actions/create_task.php?group=<?php echo url_encrypt($group_id);?>",
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
                        }, 1000);
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

    <!-------------- TABLE FILTER JS--------------------->
<script>
const filterButton = document.getElementById('filter_button');

filterButton.addEventListener('change', () => {
  const status = filterButton.value;
  filterTable(status);
});

function filterTable(status) {
  const table = document.getElementById('task_table');
  const rows = table.getElementsByTagName('tr');

  // Hide rows that don't match the selected status
  for (let i = 0; i < rows.length; i++) {
    const row = rows[i];
    const rowStatus = row.getAttribute('data-status');

    if (status === 'All' || rowStatus === status) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  }
}
</script>

<!--------------- TABLE -------------------------------->


<!-------------------COMPLETE TASK------------------------>
<script>
function complete_task(id) {
  // Show a confirmation dialog to the user
  if(confirm("Are you sure you want to complete this task?")) {
    $.ajax({
      url: '../actions/complete_task.php',
      type: 'POST',
      data: {id: id},
      success: function(response) {
        // Do something with the response
        if(response.trim() == 'Success')
        {
          toastr.success('Task Completed');
          setTimeout(function(){
            window.location.reload();
            }, 1000);
          
        }
        else if(response.trim() == 'Success-Late')
        {
           toastr.warning('Task Completed late');
           // Reload the page after the modal is hidden
            setTimeout(function(){
            window.location.reload();
            }, 1000);
                    
        }
        else
        {
          toastr.error(response);
        }
        console.log(response);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Handle errors here
        console.log('Error: ' + textStatus + ' - ' + errorThrown);
      }
    });
  }
}
</script>





</body>

</html>