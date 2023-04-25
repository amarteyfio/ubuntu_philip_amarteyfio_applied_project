<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> Sign Up </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="../../css/style.css" rel="stylesheet">
    
</head>

<body class="h-100">
    
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

    



    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                
                                    <a class="text-center" href="index.html"> <h4>Ubuntu Teamwork</h4></a>
        
                                <form class="mt-5 mb-5 login-input" id="registration_form">
                                    <div class="form-group">
                                        <input type="text" class="form-control"  placeholder="First name" id = "f_name" name = "f_name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control"  placeholder="Last name" id = "l_name" name = "l_name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control"  placeholder="Email" id = "email" name = "email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Phone No. e.g.(+)(XXX)(XXXXXXXXXX)" id = "contact" name = "contact" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" id = "password" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" id="password_confirmation" required>
                                    </div>
                                    <button class="btn login-form__btn submit w-100" id="submit" name="submit">Sign Up</button>
                                </form>
                                    <p class="mt-5 login-form__footer">Already have an account? <a href="login.php" class="text-primary">Sign In Now! </a></p>
                                    </p>
                                    <br>
                                <div class="alert alert-success" style="display:none" id="success">
									
                                </div>
								<div class="alert alert-danger" style="display:none" id="error">
									
								</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="../../plugins/common/common.min.js"></script>
    <script src="../../js/custom.min.js"></script>
    <script src="../../js/settings.js"></script>
    <script src="../../js/gleek.js"></script>
    <script src="../../js/styleSwitcher.js"></script>

    <!--**********************************
        Sign Up Script
    ***********************************-->

    <script>
                $(document).ready(function() {
            $("#submit").click(function() {

                // using serialize function of jQuery to get all values of form
                var serializedData = $("#registration_form").serialize();

                // Variable to hold request
                var request;
                // Fire off the request to process_registration_form.php
                request = $.ajax({
                    url: "../auth/signup_process.php",
                    type: "post",
                    data: serializedData
                });

                //console.log(serializedData);

                // Callback handler that will be called on success
                request.done(function(jqXHR, textStatus, response) {
                    
                
                    //redirect if successful
                    if(response.responseText.trim() == "Success"){
                        document.getElementById('success').style.display = "block";
                        $("#success").html(response.responseText.trim());
                        window.location = "login.php";
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





