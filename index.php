<?php
require 'Users.php';
require 'Grievance.php';
require 'Otp.php';

header('Cache-Control: no cache');
session_cache_limiter('private_no_expire');

$isotpboxsignal = false;
$issigninotpsignal = false;

if(!isset($_SESSION['sid'])){
    session_start();
    $_SESSION['sid']=session_id();
}
//header("Location:login.php");
if(isset($_REQUEST['signup'])){
    $isExist = isExisted($_REQUEST['email']);
    if(empty($isExist)){
        $loginType = $_REQUEST['loginType'];
        if($loginType == 'student'){
            addUsers($loginType,
                $_REQUEST['enrollno'],
                $_REQUEST['department'],
                $_REQUEST['sem'],
                $_REQUEST['fname'],
                $_REQUEST['lname'],
                $_REQUEST['email'],
                sha1($_REQUEST['pass']),
                $_REQUEST['phone'],
                $_REQUEST['birth'],
                $_REQUEST['gender'],
                0);
        } elseif ($loginType == 'faculty') {
            addUsers($loginType,
                null,
                $_REQUEST['department'],
                null,
                $_REQUEST['fname'],
                $_REQUEST['lname'],
                $_REQUEST['email'],
                sha1($_REQUEST['pass']),
                $_REQUEST['phone'],
                $_REQUEST['birth'],
                $_REQUEST['gender'],
                0);
        } else {
            addUsers($loginType,
                null,
                null,
                null,
                $_REQUEST['fname'],
                $_REQUEST['lname'],
                $_REQUEST['email'],
                sha1($_REQUEST['pass']),
                $_REQUEST['phone'],
                $_REQUEST['birth'],
                $_REQUEST['gender'],
                0);
        }
        sendOtptoUser($_REQUEST['email'],$_REQUEST['fname'],$_REQUEST['lname']);
        echo "<script>alert(\"One Time Password is Sent to Your Email For Verification!\");</script>";
    } else {
        echo "<script>alert(\"This EMail is Already Exists!\");</script>";
    }
    unset($_REQUEST['signup']);
}

if(isset($_REQUEST['signin'])){
    $isExist = isExisted($_REQUEST['email']);
    $user = getUser($_REQUEST['email'],sha1($_REQUEST['pass']));
    if(empty($isExist)){
        echo "<script>alert(\"This Account is Not Exist!\");</script>";
    } else if(empty($user)){
        echo "<script>alert(\"Password Does Not Match!\");</script>";
    } else {
        $isVerified = isVerified($_REQUEST['email']);
        if($isVerified[0]['Verify'] > 0){
            $result = getUser($_REQUEST['email'],sha1($_REQUEST['pass']));
            $_SESSION['uid'] = $result[0]["Id"];
            $_SESSION['uname'] = $_REQUEST['email'];
            $_SESSION['pass'] = sha1($_REQUEST['pass']);
        } else {
            $_SESSION['email'] = $_REQUEST['email'];
            $_SESSION['pass'] = sha1($_REQUEST['pass']);
            $issigninotpsignal = true;
        }
    }
    unset($_REQUEST['signin']);
}

if(isset($_REQUEST['query'])){
    if(isset($_SESSION['uid']) && isset($_SESSION['uname'])) {
        $result = getUser($_SESSION['uname'],$_SESSION['pass']);
        addGrievance($result, $_SESSION['uname'], $_REQUEST['qdept'], $_REQUEST['qstatement']);
        echo "<script>alert(\"Your Grievance Submited Successfully!\");</script>";
    } else {
        echo "<script>alert(\"Error in Grievance Submission!\");</script>";
    }
    unset($_REQUEST['query']);
}

if(isset($_REQUEST['otpbox'])){
    $isVerified = isVerified($_SESSION['email']);
    if($isVerified[0]['Verify'] > 0){
        echo '<script>alert("You Are Already Verified!");</script>';
    } else {
        $reqotp = $_REQUEST['otp'];
        $otp = getOtp($_SESSION['email'])[0]['Otp'];
        if($otp == $reqotp){
            $result = getUser($_SESSION['email'],$_SESSION['pass']);
            VerifyMail(true,$_SESSION['email']);
            SendVerified($_SESSION['email'],$result[0]["FName"],$result[0]["LName"]);
            $isotpboxsignal = true;
        } else {
            echo '<script>alert("Your Entered OTP is Wrong, Try again!");</script>';
        }
    }
    unset($_SESSION['email']);
    unset($_REQUEST['otpbox']);
}

if(isset($_REQUEST['signout']) && isset($_SESSION['uid'])){
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html class="full-height" lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grievance Redressal System </title>
    <meta name="description" content="NRICell Portal"/>
    <link rel="icon" href="img/FINAL_RNGPIT_4_12_18.png" sizes="16x16">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="css/swc.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mdb.min.js"></script>
</head>

<body id="top">
<header id="home">
    <!-- Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar" id="navbar">
        <div class="container" >
                <span class="blinking">
            <img src="img/FINAL_RNGPIT_4_12_18.png" style="margin-left:-120px; height:155px; width: 140px;"/></span>
            <a class="navbar-brand" href="#">Sardar Vallabhbhai Patel Education Society Managed<br><strong style="font-size: 25px;">R. N. G. Patel Institute of Technology - RNGPIT<br>Faculty of Engineering Technology & Research </strong><br>[Affiliated with G.T.U. & approved by A.I.C.T.E.] </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent"
                    aria-controls="navbarContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link active" href="#home" style="font-size: 25px;" >Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#news" style="font-size: 25px;">News</a></li>
                    <!--<li class="nav-item"><a class="nav-link" href="#blogs">Blogs</a></li>-->
                    <li class="nav-item"><a class="nav-link" href="#contact" style="font-size: 25px;">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!--<div id="boxes">
        <div style="top: 50%; left: 50%; display: none;" id="dialog" class="window">
            <div id="san">
                <a href="#" class="close agree"><img src="img/close-icon.png" width="25" style="float:right; margin-right: -25px; margin-top: -20px;"></a>
                <img src="img/invitation.jpg" width="550">
            </div>
        </div>
        <div style="width: 2478px; font-size: 32pt; color:white; height: 1202px; display: none; opacity: 0.4;" id="mask"></div>
    </div>-->
    <!-- Signup Form-->
    <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="registration-form">
                <div class="modal-footer d-flex justify-content-center">
                    <div class="fb-text form-group field-type" style="alignment: center">
                        <label class="fb-text-label">Login Type</label><br>
                        <select id="Type" name="loginType" title="Type" style="width: 150px;">
                            <option value="student" selected>Student</option>
                            <option value="parents">Parents</option>
                            <option value="faculty">Faculty</option>
                        </select>
                    </div>
                </div>
                <div class="modal-body mx-3">
                    <div class="rendered-form">
                        <div id="field-pen" class="fb-text form-group field-pen">
                            <label for="enrollno" class="fb-text-label">Enrollment Number</label>
                            <input type="text" class="form-control" name="enrollno" id="enrollno" data-validation="custom" data-validation-regexp="[0-9]{12}$" data-validation-help="Student Enrollment Number" required="required" aria-required="true">
                        </div>
                        <div id="field-dept" class="fb-text form-group field-dept">
                            <label class="fb-text-label">Department</label><br>
                            <select name="department" title="Department" style="width: 150px;">
                                <option value="be-computer" selected>BE Computer</option>
                                <option value="be-mechanical">BE Mechanical</option>
                                <option value="be-civil">BE Civil</option>
                                <option value="be-chemical">BE Chemical</option>
                                <option value="be-electrical">BE Electrical</option>
                                <option value="bvoc-software-development">B.Voc. Software Development </option>
                                <option value="bvoc-refrigeration">B.Voc. Refrigeration</option>
                                <option value="bvoc-production-technology">B.Voc. Production Technology</option>
                            </select>
                        </div>
                        <div id="field-sem" class="fb-text form-group field-sem">
                            <label for="sem" class="fb-text-label">Semester</label>
                            <input type="text" class="form-control" name="sem" id="sem" data-validation="custom" data-validation-regexp="[1-8]$" data-validation-help="any semester from 1 to 8" required="required" aria-required="true">
                        </div>
                        <div class="fb-text form-group field-email">
                            <label for="emailSignup" class="fb-text-label">Email</label>
                            <input type="text" class="form-control" name="email" data-validation="email" id="emailSignup" required="required" aria-required="true">
                        </div>
                        <div class="fb-text form-group field-user">
                            <label for="fname" class="fb-text-label">First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" required="required" aria-required="true">
                        </div>
                        <div class="fb-text form-group field-user">
                            <label for="lname" class="fb-text-label">Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" required="required" aria-required="true">
                        </div>
                        <div class="fb-text form-group field-pass">
                            <label for="pass_confirmation" class="fb-text-label">Password</label>
                            <input type="password" class="form-control" name="pass_confirmation"
                                   data-validation="strength"
                                   data-validation-strength="2" maxlength="16" id="pass_confirmation" required="required" aria-required="true">
                        </div>
                        <div class="fb-text form-group field-pass-confirm">
                            <label for="pass" class="fb-text-label">Repeat password</label>
                            <input type="password" class="form-control" name="pass" data-validation="confirmation" id="pass" required="required" aria-required="true">
                        </div>
                        <div class="fb-date form-group field-birth">
                            <label for="birth" class="fb-date-label">Date of Birth</label>
                            <input class="form-control" name="birth"
                                   data-validation="birthdate"
                                   data-validation-format="dd/mm/yyyy"
                                   data-validation-help="dd/mm/yyyy" id="birth" required="required" aria-required="true">
                        </div>
                        <div class="fb-radio-group form-group field-gender">
                            <label for="gender" class="fb-radio-group-label">Gender<span class="fb-required">*</span></label>
                            <div class="radio-group">
                                <div class="fb-radio">
                                    <input name="gender" id="gender-0" required="required" aria-required="true" value="male" type="radio">
                                    <label for="gender-0">Male</label>
                                </div>
                                <div class="fb-radio">
                                    <input name="gender" id="gender-1" required="required" aria-required="true" value="female" type="radio">
                                    <label for="gender-1">Female</label>
                                </div>
                                <div class="fb-radio">
                                    <input name="gender" id="gender-2" required="required" aria-required="true" value="other" type="radio">
                                    <label for="gender-2">Other</label>
                                </div>
                            </div>
                        </div>
                        <div class="fb-text form-group field-country">
                            <label for="phone" class="fb-text-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="phone" data-validation="custom" data-validation-regexp="^[+][0-9]{10,14}$" data-validation-help="+{CountryCode}{Number}" required="required" aria-required="true">
                        </div>
                        <div class="fb-checkbox-group form-group field-checkbox">
                            <input id="tos" type="checkbox" data-validation="required" data-validation-error-msg="You have to agree to our terms">
                            <label for="tos" class="fb-text-label">I agreed to the <a href="http://fetr.ac.in/about-us/" target="_blank">terms of service</a></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-indigo btn-default" type="submit" name="signup" id="signup">Sign up</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Login Form-->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--<div class="modal-footer d-flex justify-content-center">
                    <div class="g-signin2" data-onsuccess="onSignIn"></div>
                </div>
                <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Sign in
                    with:</p>-->
                <form method="post" id="login-form">
                <div class="modal-body mx-3">
                    <div class="rendered-form">
                        <div class="fb-text form-group field-email">
                            <label for="emailSignin" class="fb-text-label">Email</label>
                            <input type="text" class="form-control" name="email" data-validation="email" id="emailSignin" required="required" aria-required="true">
                        </div>
                        <div class="fb-text form-group field-pass">
                            <label for="passSignin" class="fb-text-label">Password</label>
                            <input type="password" class="form-control" name="pass"
                                   data-validation="strength"
                                   data-validation-strength="2" maxlength="16" id="passSignin" required="required" aria-required="true">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-indigo btn-default" type="submit" name="signin" id="signin">Login</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Query Section-->
    <div class="modal fade" id="modalQueryForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Raise Concern</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"></p>
                <form method="post" id="query-form">
                <div class="modal-body mx-3">
                    <div class="fb-radio-group form-group field-gender">
                        <label for="qdept" class="fb-radio-group-label">Please indicate the type of grievance<span class="fb-required">*</span></label>
                        <div class="radio-group">
                            <div class="fb-radio">
                                <input name="qdept" id="qdept-0" required="required" aria-required="true" value="nonacademic" type="radio">
                                <label for="qdept-0">Non-academic</label>
                            </div>
                            <div class="fb-radio">
                                <input name="qdept" id="qdept-1" required="required" aria-required="true" value="academic" type="radio">
                                <label for="qdept-1">Academic</label>
                            </div>
                            <div class="fb-radio">
                                <input name="qdept" id="qdept-2" required="required" aria-required="true" value="discrimination" type="radio">
                                <label for="qdept-2">Discrimination</label>
                            </div>
                        </div>
                    </div>
                    <div class="md-form mb-4">
                        <!-- <input type="text" name="qstatement" id="qstatement" class="form-control validate"> -->
                        <label data-error="wrong" data-success="right" for="qstatement">State your Grievance</label><br><br>
                        <textarea form ="query-form" name="qstatement" id="qstatement"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-indigo btn-default" type="submit" name="query" id="query">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- OTP Section-->
    <div class="modal fade" id="modalOtpBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Account Verification</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"></p>
                <form method="post" id="otp-form">
                    <div class="modal-body mx-3">
                        <p style="text-align: center;"><b>Otp Sent to Your EMail to Verify Your Account</b></p><br>
                        <div class="md-form mb-4">
                            <input type="text" name="otp" id="otp" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="otp">Enter OTP</label>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-indigo btn-default" type="submit" name="otpbox" id="otpbox">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Developer Section-->
    <div class="modal fade" id="modalDeveloper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Developers</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="md-form mb-6">
                        <h3 class="h5 mt-3"><b>Dhiren Jummani</b></h3>
                        <p class="font-bold">+91 95747 08867<br> dhiren.jummani.jhd@gmail.com</p>
                    </div>
                    <div class="md-form mb-4">
                        <h3 class="h5 mt-3"><b>Kuldip Patel</b></h3>
                        <p class="font-bold">+91 99988 97742<br> patel.kuldip91@gmail.com</p>
                    </div>
                    <div class="md-form mb-4">
                        <h3 class="h5 mt-3"><b>Meet Patel</b></h3>
                        <p class="font-bold">+91 97230 83820<br> meetcpatel906@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Intro Section-->
    <section class="view hm-gradient" id="intro">
        <div class="full-bg-img d-flex align-items-center">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-10 col-lg-6 text-center text-md-left margins">
                        <div class="white-text">
                            <div class="wow fadeInLeft" data-wow-delay="0.3s">
                                 <span class="blinking">
                                    <h2 class="font-bold mt-sm-5" style="font-size: 36.5px;">GRIEVANCE REDRESSAL SYSTEM</h2>
                                </span>
                            </div>
                            <br>
                            <div class="wow fadeInLeft" data-wow-delay="0.3s">
                                <?php
                                if(!isset($_SESSION['uname'])){
                                    echo "<a id=\"signinbtn\" class=\"btn btn-outline-white\" data-toggle=\"modal\" data-target=\"#modalLoginForm\"
                                            href=\"\">Sign In</a>                                   
                                        <a id=\"signupbtn\" class=\"btn btn-outline-white\" data-toggle=\"modal\" data-target=\"#modalRegisterForm\"
                                            href=\"\">Sign Up</a>";
                                } else {
                                    echo "<a class=\"btn btn-outline-white\" data-toggle=\"modal\" data-target=\"#modalQueryForm\" href=\"\">Raise Concern</a>
                                        <form method=\"post\" id=\"signout-form\">
                                            <button id=\"signoutbtn\" class=\"btn btn-outline-white\" type=\"submit\" name=\"signout\">Sign Out</button>
                                        </form>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</header>
<div id="content">
    <section class="text-center py-5 grey lighten-4" id="news">
        <div class="container">
            <div class="wow fadeIn">
                <h2 class="h1 pt-5 pb-3">NEWS</h2>
            </div>
            <div class="row">
                <!-- <div class="col-md-3 mb-r wow fadeInUp" data-wow-delay=".3s">
                    <img src="img/invitation1.jpg" height="150" width="130"/>
                    <h3 class="h4 mt-3">WELCOME TO SAMVOTSAV 2019</h3>
                    <a class="font-bold" href="news1.php">Read More</a>
                </div>
                <div class="col-md-3 mb-r wow fadeInUp" data-wow-delay=".4s">
                    <img src="img/logo.png" width="160" height="160">
                    <h3 class="h4 mt-3">INDIA NRI CELL LAUNCH EVENT</h3>
                    <a class="font-bold" href="news2.php">Read More</a>
                </div>
                <div class="col-md-3 mb-r wow fadeInUp" data-wow-delay=".5s">
                    <img src="img/SGPCsurat.jpg" width="160" height="160">
                    <h3 class="h4 mt-3">SGPC-SURAT</h3>
                    <a class="font-bold" href="news3.php">Read More</a>
                </div> -->
                <div class="col-md-3 mb-r wow fadeInUp" data-wow-delay=".6s">
                    <img src="img/FINAL_RNGPIT_4_12_18.png" width="160" height="160">
                    <h3 class="h4 mt-3">R. N. G. Patel Institute of Technology, Bardoli</h3>
                    <a class="font-bold" href="news4.php">Read More</a>
                </div>
            </div>
        </div>
    </section>
    <!--<section class="py-5" id="blogs">
        <div class="container">
            <div class="wow fadeIn">
                <h2 class="text-center h1 my-4">BLOGS</h2>

            </div>
            <div class="row wow fadeInLeft" data-wow-delay=".3s">
                <div class="col-lg-6 col-xl-5 pr-lg-5 pb-5"><img class="img-fluid rounded z-depth-2"
                                                                 src="img/kuala-lumpur.jpg"
                                                                 alt="project image"/></div>
                <div class="col-lg-6 col-xl-7 pl-lg-5 pb-4">
                    <div class="row mb-3">
                        <div class="col-1 mr-1"><i class="fa fa-book fa-2x cyan-text"></i></div>
                        <div class="col-10">
                            <h5 class="font-bold">Education</h5>
                            <a class="font-bold" href="blog.php">Read More</a>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-1 mr-1"><i class="fa fa-code fa-2x red-text"></i></div>
                        <div class="col-10">
                            <h5 class="font-bold">Technology</h5>
                            <a class="font-bold" href="blog.php">Read More</a>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-1 mr-1"><i class="fa fa-money fa-2x deep-purple-text"></i></div>
                        <div class="col-10">
                            <h5 class="font-bold">Finance</h5>
                            <a class="font-bold" href="blog.php">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row pt-5 wow fadeInRight" data-wow-delay=".3s">
                <div class="col-lg-6 col-xl-7 mb-3">
                    <div class="row mb-3">
                        <div class="col-1 mr-1"><i class="fa fa-bar-chart fa-2x indigo-text"></i></div>
                        <div class="col-10">
                            <h5 class="font-bold">Marketing</h5>
                            <a class="font-bold" href="blog.php">Read More</a>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-1 mr-1"><i class="fa fa-music fa-2x pink-text"></i></div>
                        <div class="col-10">
                            <h5 class="font-bold">Entertainment</h5>
                            <a class="font-bold" href="blog.php">Read More</a>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-1 mr-1"><i class="fa fa-smile-o fa-2x blue-text"></i></div>
                        <div class="col-10">
                            <h5 class="font-bold">Communication</h5>
                            <a class="font-bold" href="blog.php">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5 mb-3"><img class="img-fluid rounded z-depth-2" src="img/san-francisco.jpg"
                                                         alt="project image"/></div>
            </div>
        </div>
    </section>-->
    <!-- Conatct us Section-->
    <section id="contact" style="background-image:url('img/panorama-3094696_1920.jpg');">
        <div class="rgba-black-strong py-5">
            <div class="container">
                <div class="wow fadeIn">
                    <h2 class="h1 text-white pt-5 pb-3 text-center">CONTACT US</h2>
                </div>
                <div class="card mb-5 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card-body p-5">
                        <section class="text-center py-5 grey lighten-4" id="news">
                            <div class="container">
                                <div class="wow fadeIn">
                                    <div class="wow fadeIn">
                                        <h2 class="h2 pt-5 pb-3" style="margin-top: -50px; margin-left: -20px;">COMMITTEE MEMBERS</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".4s">
                                        <h3 class="h4 mt-3"><b>PRINCIPAL</b></h3>
                                        <p class="font-bold">Dr. Latesh B. Chaudhari<br> +91 98986 73451<br> lbc.fetr@gmail.com</p>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".3s">
                                        <h3 class="h4 mt-3"><b>MECHANICAL ENGINEERING</b></h3>
                                        <p class="font-bold">Dr. K. B. Rathod<br> +91 99240 44142 <br> kbr.fetr@gmail.com</p>
                                        <p class="font-bold"></p>
                                    </div> 
                                    <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".4s">
                                        <h3 class="h4 mt-3"><b>CHEMICAL ENGINEERING</b></h3>
                                        <p class="font-bold">Dr. J. M. Barad<br> +91 98241 34132<br> jmb.fetr@gmail.com</p>
                                    </div>
                                   <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".5s">
                                        <h3 class="h4 mt-3"><b>COMPUTER SCIENCE & ENGINEERING</b></h3>
                                        <p class="font-bold">Ms. B. R. Bhatt<br> +91 98796 79953<br> brb.fetr@gmail.com</p>
                                    </div> 
                                </div>
                                <div class="row">
                                     <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".3s">
                                        <h3 class="h4 mt-3"><b>ELECTRICAL ENGINEERING</b></h3>
                                        <p class="font-bold">Mr. C. D. Desai<br> +91 99096 01202 <br> cdd.fetr@gmail.com</p>
                                        <p class="font-bold"></p>
                                    </div> 
                                    <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".4s">
                                        <h3 class="h4 mt-3"><b>CIVIL ENGINEERING</b></h3>
                                        <p class="font-bold">Mr. S. J. Patel<br> +91 98259 44054<br> sjp.fetr@gmail.com</p>
                                    </div>
                                   <div class="col-md-4 mb-r wow fadeInUp" data-wow-delay=".5s">
                                        <h3 class="h4 mt-3"><b>SCIENCE & HUMANITY</b></h3>
                                        <p class="font-bold">Mr. D. A. Dadhania<br> +91 99251 58466<br> dad.fetr@gmail.com</p>
                                    </div> 
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
    </section>
</div>
<footer class="page-footer indigo darken-2 center-on-small-only pt-0 mt-0">
    <!--<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-5 flex-center">
                    <a class="px-3"><i class="fa fa-facebook fa-lg white-text"></i></a>
                    <a class="px-3"><i class="fa fa-twitter fa-lg white-text"></i></a>
                    <a class="px-3"><i class="fa fa-google-plus fa-lg white-text"></i></a>
                    <a class="px-3"><i class="fa fa-linkedin fa-lg white-text"></i></a>
                </div>
            </div>
        </div>
    </div>-->
    <div class="footer-copyright">
        <div class="container-fluid">
            <p>&copy; <a href="https://www.fetr.ac.in">Copyright by RNGPIT 2019</a> - <a href="" data-toggle="modal" data-target="#modalDeveloper">Developed By RNGPIT</a> - <img src='http://online-visit-counter.com/cg.php?t=MTQzNTQ5MQ==' border='0' alt='hit counter'></p>
        </div>
    </div>
</footer>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/popper.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script type="text/javascript" src="js/swc.js"></script>
<script type="text/javascript" src="js/validate.js"></script>
<?php
    if($isotpboxsignal == true){
        echo '<script>
                    alert("Your Are Verified Successfully!");
                    $("#modalLoginForm").modal("show");
              </script>';
    }

    if($issigninotpsignal == true){
        echo "<script>$('#modalOtpBox').modal('show');</script>";
    }
?>
</body>
</html>