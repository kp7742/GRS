<?php
require 'PHPMailer/PHPMailerAutoload.php';

function sendMail($eMail){
    $mail = new PHPMailer;                              // Passing `true` enables exceptions
    //$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'kp747007@gmail.com';                 // SMTP username
        $mail->Password = 'lhqpyicjmahoyhhx';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                   // TCP port to connect to

        //Recipients
        $mail->setFrom('admin@indianricell.tk', "IndiaNRICell.tk");
        $mail->addAddress($eMail);     // Add a recipient
        $mail->addCC('admin@indianricell.tk');

        //Content
        $mail->Subject = 'Query Submission';
        $mail->Body    = 'Your Query is Submitted, We will Contact Back you soon!';

        if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
}

function QuerySubMail($user, $dept, $query){
    $eMail = "vcj.fetr@gmail.com";
    $mail = new PHPMailer;                              // Passing `true` enables exceptions
    //Server settings
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'kp747007@gmail.com';                 // SMTP username
        $mail->Password = 'lhqpyicjmahoyhhx';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                   // TCP port to connect to

        //Recipients
        $mail->setFrom('admin@grievance.fetr.ac.in', "Grievance Redressal System");
        $mail->addAddress($eMail);// Add a recipient
        $mail->addCC('admin@grievance.fetr.ac.in');

        //Content
        $mail->Subject = 'Raised Concern';
        $mail->Body =
            "Name: ".$user[0]['FName']." ".$user[0]['LName'].
            "\n\nEnrollment No.: ".$user[0]['Enroll'].
            "\n\nDeparment: ".$user[0]['Dept'].
            "\n\nSemester: ".$user[0]['Sem'].
            "\n\nEMail: ".$user[0]['EMail'].
            "\n\nMobile Number: ".$user[0]['Mobile'].
            "\n\nType of Grievance: ".$dept.
            "\n\nGrievance: ".$query;

        if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
}

function ConcernReplyMail($eMail, $user){
    $mail = new PHPMailer;                              // Passing `true` enables exceptions
    //Server settings
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'kp747007@gmail.com';                 // SMTP username
        $mail->Password = 'lhqpyicjmahoyhhx';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                   // TCP port to connect to

        //Recipients
        $mail->setFrom('admin@grievance.fetr.ac.in', "Grievance Redressal System");
        $mail->addAddress($eMail);// Add a recipient
        $mail->addCC('admin@grievance.fetr.ac.in');

        //Content
        $mail->Subject = 'Raised Concern';
        $mail->Body = "Hello ".$user[0]['FName']." ".$user[0]['LName'].",\n\nThank you for your concern. We will look into it and acknowledge through your registered email!";

        if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
}

function SendOtp($eMail,$fname,$lname, $otp){
    $mail = new PHPMailer;                              // Passing `true` enables exceptions
    //Server settings
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'kp747007@gmail.com';                 // SMTP username
        $mail->Password = 'lhqpyicjmahoyhhx';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                   // TCP port to connect to

        //Recipients
        $mail->setFrom('admin@grievance.fetr.ac.in', "Grievance Redressal System");
        $mail->addAddress($eMail);// Add a recipient
        $mail->addCC('admin@grievance.fetr.ac.in');

        //Content
        $mail->Subject = 'OTP';
        $mail->Body = "Hello ".$fname." ".$lname.",\n\nYour One Time Password is ".$otp."\n\nPlease Login in Your Account and Enter Otp to Verify Account.";

        if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
}

function SendVerified($eMail,$fname,$lname){
    $mail = new PHPMailer;                              // Passing `true` enables exceptions
    //Server settings
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'kp747007@gmail.com';                 // SMTP username
        $mail->Password = 'lhqpyicjmahoyhhx';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                   // TCP port to connect to

        //Recipients
        $mail->setFrom('admin@grievance.fetr.ac.in', "Grievance Redressal System");
        $mail->addAddress($eMail);// Add a recipient
        $mail->addCC('admin@grievance.fetr.ac.in');

        //Content
        $mail->Subject = 'Account';
        $mail->Body = "Hello ".$fname." ".$lname.",\n\nYour Account is Verified Successfully!";

        if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
}
?>