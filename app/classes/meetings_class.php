<?php
//require db_configuration file
set_include_path(dirname(__FILE__)."/../");
include_once('database/db_config.php');

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


/**
 * Class for performing operations related to groups in the database.
 * 
 * @author Philip Amarteyfio
 * @version 0.1
 * 
 */


 /*----------------------------------------------------/
                    MEETINGs FUNCTIONS
 /----------------------------------------------------*/


 class meetings_class extends db_config
 {
    /**
     * Function to add a meeting
     * 
     * 
     * @author Philip Amarteyfio
     */
    function add_meeting($group_id,$meeting_title,$meeting_desc,$meeting_type,$meeting_loc,$begins_at,$ends_at)
    {
        $query = "INSERT INTO meetings (group_id,meeting_title,meeting_desc,meeting_type,meeting_loc,begins_at,ends_at) VALUES (?,?,?,?,?,?,?)";

        $params = [$group_id,$meeting_title,$meeting_desc,$meeting_type,$meeting_loc,$begins_at,$ends_at];

        return $this->run_query($query,$params);
    }


    /**
     * Function to select meetings
     * 
     * 
     * @author Philip Amarteyfio
     */
    function select_group_meetings($group_id)
    {
        $query = "SELECT * FROM meetings WHERE group_id = ? AND ends_at > NOW()";

        $param = [$group_id];

        return $this->db_fetch_all($query,$param);
    }


    /**
     * Function to add attendance record
     * 
     * 
     * @author Philip Amarteyfio
     */

    function add_attendance($meeting_id,$attendee_id,$group_id,$stat)
    {
        $query = "INSERT INTO attendance (meeting_id,attendee_id,group_id,status) VALUES (?,?,?,?)";

        $params = [$meeting_id,$attendee_id,$group_id,$stat];

        return $this->run_query($query,$params);
    }


    /**
     * Function set attendance
     * 
     * 
     * @author Philip Amarteyfio
     */
    function set_attendance($meeting_id,$attendee_id,$stat)
    {
        $query = "UPDATE attendance SET status = ? WHERE meeting_id = ? AND attendee_id = ?";

        $params = [$stat,$meeting_id,$attendee_id];

        return $this->run_query($query,$params);
    }


    /**
     * Function to select user attendance record
     * 
     * @author Philip Amarteyfio
     * 
     */
    function select_attendance($user_id,$group_id)
    {
        $query = "SELECT * FROM attendance WHERE attendee_id = ? AND group_id = ?";

        $param = [$user_id,$group_id];

        return $this->db_fetch_all($query,$param);
    }


    /***
     * Function to select a meeting
     * 
     * 
     * 
     */
    function select_meeting($meeting_id)
    {
        $query = "SELECT * FROM meetings WHERE meeting_id = ?";

        $param = [$meeting_id];

        return $this->db_fetch_one($query,$param);
    }

    /**
     * Function to check a users attendance
     * 
     * 
     * @author Philip Amarteyfio
     */
    function check_attendance($attendee_id,$meeting_id)
    {
        $query = "SELECT * FROM attendance WHERE attendee_id = ? AND meeting_id = ?";

        $params = [$attendee_id,$meeting_id];

        $data = $this->db_fetch_one($query,$params);

        if(!empty($data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    /**
     * Function to regulate attendance log
     * 
     * 
     * @author Philip Amarteyfio
     */
    function attend_log($group_id)
    {
        $query_a = "SELECT * FROM meetings WHERE group_id = ? AND begins_at < NOW() AND ends_at < NOW()";

        $params = [$group_id];

        $data = $this->db_fetch_all($query_a,$params);

        //select users
        $query_b = "SELECT * FROM user_groups WHERE group_id = ?";
        
        $members = $this->db_fetch_all($query_b,$params);

        foreach($members as $member)
        {
             foreach($data as $row)
             {
                if($this->check_attendance($member['user_id'],$row['meeting_id']) == false)
                {
                    $this->add_attendance($row['meeting_id'],$member['user_id'],$group_id,2);
                }
             }
        }
    }


    /**
     * Funtion to send email notification
     * 
     * 
     */
    function send_meeting_invitation($to, $subject, $description, $startDateTime, $endDateTime, $location) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer();
    
        // Set the SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aubuntu2023@gmail.com';
        $mail->Password = 'hfhdyfsgdytzbokv';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->isHTML(FALSE);
    
        // Set the email content
        $mail->setFrom('aubuntu2023@gmail.com', 'Ubuntu');
        $mail->addAddress($to);
        $mail->Subject = $subject;
    
        // Create the ICS content
        $uid = uniqid();
        $ical_content = "BEGIN:VCALENDAR
        VERSION:2.0
        PRODID://Ubuntu Teamwork Systems//NONSGML Events//EN
        BEGIN:VEVENT
        UID:'http://www.icalmaker.com/event/d8fefcc9-a576-4432-8b20-40e90889affd
        DTSTAMP:".date('Ymd\THis\Z')."
        DTSTART:" . date('Ymd\THis\Z', strtotime($startDateTime)) . "
        DTEND:" . date('Ymd\THis\Z', strtotime($endDateTime)) . "
        SUMMARY:".$subject."
        ORGANIZER;CN=Sender Name:mailto:aubuntu2023@gmail.com
        ATTENDEE;CN=Recipient Name:mailto:".$to."
        DESCRIPTION:".$description."
        LOCATION:".$location."
        END:VEVENT
        END:VCALENDAR";
    
        // Set the email body
        $mail->Body = "A new meeting invite has been created for a group you are in. Kindly add the meeting to your calendar and join us.\n Meeting Name:".$subject."\n Meeting Description:".$description."\n Starts at:". $startDateTime."\n Ends at:".$endDateTime."\n Location: ".$location."\nSent from Ubuntu Server";
    
        // Add the ICS attachment
        $mail->addStringAttachment($ical_content, 'meeting.ics', 'base64', 'text/calendar');
    
        // Send the email
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
    

}  