<?php
require "../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Create a new PHPMailer instance


function send_meeting_invitation($to, $subject, $description, $startDateTime, $endDateTime, $location) {

    $mail = new PHPMailer();

    // Set the SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aubuntu2023@gmail.com';
    $mail->Password = 'hfhdyfsgdytzbokv';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->ContentType = 'text/calendar';
    $mail->isHTML(true);
    $mail->msgHTML('This is a test email with an iCalendar attachment');

    // Set the email content
    $mail->setFrom( 'aubuntu2023@gmail.com', 'Ubuntu');
    $mail->addAddress($to);
    $mail->Subject = $subject;

    //ICS content
    $ical_content = "BEGIN:VCALENDAR
    VERSION:2.0
    PRODID://UBUNTU//EN
    BEGIN:VEVENT
    UID:'http://www.icalmaker.com/event/d8fefcc9-a576-4432-8b20-40e90889affd
    DTSTAMP:".gmdate('Ymd').'T'.gmdate('His')."Z
    DTSTART:".gmdate('Ymd').'T'.gmdate('His')."Z
    DTEND:".gmdate('Ymd').'T'.gmdate('His')."Z
    SUMMARY:$subject
    DESCRIPTION:$description
    LOCATION:$location
    END:VEVENT
    END:VCALENDAR
    ";

    // Set the email body
    $mail->Body = 'This is a test email with an iCalendar attachment.';

    //For sending ical
    $mail->addStringAttachment($ical_content,'invite.ics','base64','text/calendar');

    // Send the email
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}

$to = 'philip.amarteyfio@ashesi.edu.gh';
$subject = 'Test Meeting';
$description = 'This is a test meeting invitation.';
$startDateTime = '2023-03-31 14:00:00';
$endDateTime = '2023-03-31 15:00:00';
$location = 'Conference Room 123';

if (send_meeting_invitation($to, $subject, $description, $startDateTime, $endDateTime, $location)) {
    echo 'Email sent successfully.';
} else {
    echo 'Email sending failed.';
}



?>