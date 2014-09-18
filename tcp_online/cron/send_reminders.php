#!/usr/bin/php

<?php

	$log = array();

	$db = mysql_connect("fling.seas.upenn.edu", "mwester", "tcpfellows");
	mysql_select_db("mwester");

	date_default_timezone_set("America/New_York");

	function add_to_log($msg){
		//Log error
		//Send mail to sysadmin with error information
		global $log;
		$log[] = $msg;
		echo('Logging: ' . $msg . '\r\n');
	}

	function log_possibly_fatal_error($err){
		global $log;
		$log[] = $err;

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: Zach Zarrow<zzarrow@seas.upenn.edu>' . "\r\n";
                $headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";

                $subj = "TCP CRON Job Error Report: Daily Appointment Reminders";

                $msg = "<b><font color=\"#FF0000\">An error occurred while processing the Daily Appointment Reminders.</font></b>  The error occurred at <b>" . date("m/d/Y h:i:s a", time()) . "</b>.<br /><br /><font face=\"Courier New\" color=\"#FF0000\">";

		$msg .= $err;

                $msg .= "</font><br /><br />At the time of the error, the execution log contains:<br /><br /><font face=\"Courier New\">";

		for($i = 0; $i < sizeof($log); $i++){
			$msg .= $GLOBALS['log'][$i];
			$msg .= "<br />";
		}	

		$msg .= "</font>";

                mail("",
                        $subj,
                        $msg,
                        $headers);

	}

	function flush_log(){
		//TODO: Implement a more modular solution here

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: Zach Zarrow<zzarrow@seas.upenn.edu>' . "\r\n";
                $headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";

		$subj = "TCP CRON Job Log: Daily Appointment Reminders";

		$msg = "Job completed at <b>" . date("m/d/Y h:i:s a", time()) . "</b>.<br /><br /><font face=\"Courier New\">";

		global $log;
		for($i = 0; $i < sizeof($log); $i++){
			$msg .= $log[$i];
			$msg .= "<br />";
		}

		$msg .= "</font>";

                mail("",
                        $subj,
                        $msg,
                        $headers);

	}

	function index_to_str($_time_index){
                        $temp_arr = array(
                                        0 => "9:00am",
                                        1 => "9:30am",
                                        2 => "10:00am",
                                        3 => "10:30am",
                                        4 => "11:00am",
                                        5 => "11:30am",
                                        6 => "12:00pm",
                                        7 => "12:30pm",
                                        8 => "1:00pm",
                                        9 => "1:30pm",
                                        10 => "2:00pm",
                                        11 => "2:30pm",
                                        12 => "3:00pm",
                                        13 => "3:30pm",
                                        14 => "4:00pm",
                                        15 => "4:30pm",
                                        16 => "5:00pm",
                                        17 => "5:30pm",
                                        18 => "6:00pm",
                                        19 => "6:30pm",
                                        20 => "7:00pm",
                                        21 => "7:30pm",
                                        22 => "8:00pm",
                                        23 => "8:30pm"
                         );

                         return $temp_arr[$_time_index];
        }


	function send_fellow_email($_fid, $_apt_date, $_apt_time_index, $_apt_first_name, $_apt_last_name, $_apt_email_addr){
		$q = mysql_query("SELECT firstname, lastname, email FROM fellows WHERE `id`='" . $_fid . "'") or log_possibly_fatal_error('Fellow query failed.  If you do not receive an e-mail indicating that this job successfully completed, all notifications may not have been sent.  MySQL said: ' . mysql_error());
		$f = mysql_fetch_object($q) or log_possibly_fatal_error('Fellow query did not return any results.  If you do not receive an e-mail indicating that this job successfully copmleted, all notifications may not have been sent.  MySQL said: ' . mysql_error());
		send_student_email($_apt_date, $_apt_time_index, $f->firstname, $f->lastname, $f->email);
	}

        function send_student_email($_apt_date, $_apt_time_index, $_apt_first_name, $_apt_last_name, $_apt_email_addr){
		
		$tomorrow = date('Ymd', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
		$dayofweek = date('l', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

		$str_time = index_to_str($_apt_time_index);
                $subj = "Reminder: TCP Appointment Tomorrow (" . $dayofweek . ") at " . $str_time;
                $msg = "Dear " . $_apt_first_name . ",<br /><br />

                This e-mail is being sent as a reminder for your TCP appointment tomorrow <b>(" . $dayofweek . ")</b> at <b>" . $str_time . "</b>.<br /><br />

To view all appointment details, or if you need to cancel or change this appointment, please visit the <a href=\"http://www.seas.upenn.edu/~tcp\">TCP Online Booking System</a>.<br /><br />

Thank you,<br />
- Technical Communication Program<br /><br />

<b>Note:</b> This e-mail address is not monitored, so <em>please do not reply directly to this e-mail</em>.
                ";

                //echo('<br />Sending e-mail to ' . $_addr . '<br />Subject: ' . $subj . '<br />Message:<br />' . $msg);

                //FOR DEBUG:
                $email = "zzarrow@gmail.com";

                //FOR PRODUCTION:
                //$email = $_apt->get_email();

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: ' . $_apt_first_name . ' ' . $_apt_last_name . '<' . $_apt_email_addr . '>' . "\r\n";
                $headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";

                mail("",
                        $subj,
                        $msg,
                        $headers) or log_possibly_fatal_error("PHP mail call for notification to " . $email . " failed.  If a Job Completed e-mail is not received, this error was fatal and notifications may not have been sent.");

                return;
        }




	//To be run at 6:00pm every night

	$tomorrow = date('Ymd', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
	$q = mysql_query("SELECT * from `appointments` WHERE `apt_date` = '" . $tomorrow . "'") or log_possibly_fatal_error('Appointments query failed.  If a Job Completed e-mail is not received, this error was fatal and notifications may not have been sent.  MySQL said: ' . mysql_error());
	
	while($f = mysql_fetch_object($q)){
		send_student_email($f->apt_date, $f->time_index, $f->firstname, $f->lastname, $f->email);
		add_to_log('E-mail sent to student ' . $f->firstname . ' ' . $f->lastname . '(' . $f->email . ') for appointment on ' . $f->apt_date . ' at time index ' . $f->time_index);
		send_fellow_email($f->fellow_id, $f->apt_date, $f->time_index, $f->firstname, $f->lastname, $f->email);
		add_to_log('E-mail sent to fellow with ID ' . $f->fellow_id . ' for appointment on ' . $f->apt_date . ' at time index ' . $f->time_index);
	}

	flush_log();
	mysql_close($db);
?>
