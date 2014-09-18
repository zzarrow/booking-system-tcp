<?
	//TODO: Auto-population of information from previous appointments.

	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Book An Appointment - Student Information</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey);
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	//echo('<br />Access level: ' . $access_level . '<br /><br />');
	
	//d = date, t = time index, f = fellow_id
	
	function confirm_availability($apt_date, $apt_time, $fid){
		//These setter calls should be moved inside the constructor of fellow... oh well
		$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id($fid);
		if($fellow == null)
			die('Invalid fellow ID.  <a href="javscript:history.go(-1)">Back</a>');
		$fellow->set_appointments($GLOBALS['tcpdatalink']->get_appointments_for_fellow($fellow->get_id()));
		$fellow->set_block_list($GLOBALS['tcpdatalink']->get_block_list_for_fellow($fellow->get_id()));
				
		$apts = $fellow->get_appointments();
		//for($i = 0; $i < sizeof($apts); $i++){
		//	echo('<br />Appointment: ' . $apts[$i]->get_apt_date() . ' ' . $apts[$i]->get_time_index() . ' <br />');
		//}
		
		//echo('Availability: ' . $this->get_availability_at_time_index($_date->get_day_of_week(), $time_index) . '<br />');
		//echo('Has apt at time: ' . $fellow -> has_appointment_at_time($apt_date, $apt_time) . '<br />');
		//echo('Has block at time: ' . $fellow -> has_block_at_time($apt_date, $apt_time) . '<br />');
		

		//echo('Calling is_available with date: ' . tcp_date::get_tcpdate_from_dbdate($apt_date)->to_string() . ' and time index ' . $apt_time);	
		return $fellow->is_available(tcp_date::get_tcpdate_from_dbdate($apt_date), $apt_time);
	}
	
	function get_auth_pennkey($pennkey_from_form, $_access_level){
		if($_access_level <= ACCESS_LEVELS::intern)
			return $pennkey_from_form;
		else
			return $GLOBALS['pennkey']; //The user's pennkey (To ensure students can only book for themselevs)
	}
	
	function commit_to_db($_fid,
						  $_pennkey,
						  $_firstname,
						  $_lastname,
						  $_email,
						  $_apt_date,
						  $_comment,
						  $_time_index){
	
		//Create appointment object
		//tcpdb::book_appointment(appointment);
	
		$appointment = new appointment("",
										$_fid,
										$_pennkey,
										$_firstname,
										$_lastname,
										$_email,
										$_apt_date,
										$_comment,
										"",
										"",
										$_time_index,
										"0");
	
		$GLOBALS['tcpdatalink']->book_appointment($appointment);
	
	}

	function get_fellow_form_control($_d, $_t){
		if(isset($_GET['f'])){
			//We know the fellow
			$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id(tcpdb::db_sanitize($_GET['f']));
			return '<input type="hidden" name="f" value="' . $fellow->get_id() . '">' . $fellow->get_firstname() . ' ' . $fellow->get_lastname() . ' <font color="gray">(' . $fellow->get_major() . ' ' . $fellow->get_class_year() . ')</font>';
		} else{
			//We don't know the fellow.  Let them choose.
			//Get all fellows, check is_available on each.
			$out_str = "";
			$fellow_arr = $GLOBALS['tcpdatalink']->get_bookable_fellows();

			$numDisplayed = 0;
			$firstId = -1;
			for($i = 0; $i < sizeof($fellow_arr); $i++){
				if($fellow_arr[$i]->is_available(tcp_date::get_tcpdate_from_dbdate($_d), $_t)){
					$out_str = $out_str . '<input type="radio" name="f" value="' . $fellow_arr[$i]->get_id() . '" ' . (($numDisplayed > 0) ? ('checked') : '') . 'checked>&nbsp; ' . $fellow_arr[$i]->get_firstname() . ' ' . $fellow_arr[$i]->get_lastname() . ' <font color="gray">(' . $fellow_arr[$i]->get_major() . ' ' . $fellow_arr[$i]->get_class_year() . ')</font><br />';
				$firstId = $fellow_arr[$i]->get_id();
				$numDisplayed++;
				}
			}
		}

		//When we only have 1 fellow, we will end up with a single radio button option. We're going to
		//set $_GET['f'] and make a recursive call to handle.
		if($numDisplayed == 1){
			$_GET['f'] = $firstId;
			return get_fellow_form_control($_d, $_t);
		}

		return $out_str;
	}

	function send_student_email($_addr){
		//Send student a confirmation e-mail
		
		//We have apt. info
		//Get fellow info
		
		$f = (isset($_GET['f'])) ? (tcpdb::db_sanitize($_GET['f'])) : ($_POST['f']);
		$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id(tcpdb::db_sanitize($f));
		if($fellow == null)
			die('Invalid fellow ID. <a href="javascript:history.go(-1)">Back</a>');		

		$subj = "TCP Appointment Booked for " . tcp_date::raw_to_readable(tcpdb::db_sanitize($_GET['d'])) . " at " . tcp_date::index_to_str(tcpdb::db_sanitize($_GET['t'])); 
		$msg = "Dear " . tcpdb::db_sanitize($_POST['firstname']) . ",<br /><br />
		
		Your TCP appointment has been successfully booked with TCP Fellow <b>" . $fellow->get_firstname() . " " . $fellow->get_lastname() . "</b>.<br /><br />
		
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Appointment date: <b>" . tcp_date::raw_to_readable(tcpdb::db_sanitize($_GET['d'])) . "</b><br />
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Appointment time: <b>" . tcp_date::index_to_str(tcpdb::db_sanitize($_GET['t'])) . "</b><br />
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Appointment location: <b>" . tcpdb::get_meta_key('mtg_location') . "</b><br /><br />
		
		If you need to cancel or change this appointment, please visit the <a href=\"http://www.seas.upenn.edu/~tcp\">TCP Online Booking System</a>.  If you have any questions, please e-mail " . $fellow->get_firstname() . " directly at <a href=\"mailto:" . $fellow->get_email() . "\">" . $fellow->get_email() . "</a>.<br /><br />
		
		Please try to send a draft or outline of your assignment to " . $fellow->get_firstname() . " at least 48 hours before your appointment using the e-mail address above.  If you have any questions, please contact " . $GLOBALS['tcpdatalink']->get_contact_name() . " at <a href=\"mailto:" . $GLOBALS['tcpdatalink']->get_contact_email() . "\">" . $GLOBALS['tcpdatalink']->get_contact_email() . "</a><br /><br />
		
		Thank you,<br />
		- Technical Communication Program<br /><br />
		
		<b>Note:</b> This e-mail address is not monitored, so <b>please do not reply directly to this e-mail</b>.
		";
		
		//echo('<br />Sending e-mail to ' . $_addr . '<br />Subject: ' . $subj . '<br />Message:<br />' . $msg);
		
		//FOR DEBUG:
		//$email = "zzarrow@gmail.com";

		//FOR PRODUCTION:
		$email = $_addr;		

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: ' . tcpdb::db_sanitize($_POST['firstname'] . ' ' . $_POST['lastname']) . '<' . $email . '>' . "\r\n";
		$headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";
		
		mail("",
			$subj,
			$msg,
			$headers);
		
		return;
	}
	
	function send_fellow_email($_fid){
		$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id($_fid);
		
		$subj = "TCP Appointment Booked for " . tcp_date::raw_to_readable(tcpdb::db_sanitize($_GET['d'])) . " at " . tcp_date::index_to_str(tcpdb::db_sanitize($_GET['t'])); 
		$msg = "Dear " . $fellow->get_firstname() . ",<br /><br />
		
		A new TCP appointment has been booked by <b>" . tcpdb::db_sanitize($_POST['firstname'] . " " . $_POST['lastname']) . "</b> (<a href=\"mailto:" . tcpdb::db_sanitize($_POST['email']) . "\">" . tcpdb::db_sanitize($_POST['email']) . "</a>).<br /><br />
		
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Appointment date: <b>" . tcp_date::raw_to_readable(tcpdb::db_sanitize($_GET['d'])) . "</b><br />
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Appointment time: <b>" . tcp_date::index_to_str(tcpdb::db_sanitize($_GET['t'])) . "</b><br />
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; User Comments: <b>" . tcpdb::db_sanitize($_POST['comment']) . "</b><br /><br />
		
		If you need to cancel or change this appointment, please visit the <a href=\"http://www.seas.upenn.edu/~tcp\">TCP Online Booking System</a>. If you have any questions, please contact " . $GLOBALS['tcpdatalink']->get_contact_name() . " at <a href=\"mailto:" . $GLOBALS['tcpdatalink']->get_contact_email() . "\">" . $GLOBALS['tcpdatalink']->get_contact_email() . "</a><br /><br />
		
		Thank you,<br />
		- Technical Communication Program<br /><br />
		
		<b>Note:</b> This e-mail address is not monitored, so <b>please do not reply directly to this e-mail</b>.
		";
		
		//echo('<br />Sending e-mail to ' . $_addr . '<br />Subject: ' . $subj . '<br />Message:<br />' . $msg);
		
		//FOR DEBUG:
		$email = "zzarrow@gmail.com";

		//FOR PRODUCTION:
		$email = $fellow->get_email();		

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: ' . tcpdb::db_sanitize($_POST['firstname'] . ' ' . $_POST['lastname']) . '<' . $email . '>' . "\r\n";
		$headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";
		
		mail("",
			$subj,
			$msg,
			$headers);
		
		return;
	}
	

	
	
	//check for POST
	//If post[submit] exists
		//Confirm availability
			//If not available
				//display error; kill execution
		//Check permissions on pennkey
			//If student, force their own pennkey
			//else, whatever was submitted in pennkey field
		//Insert into database (sanitize input)
		//Send e-mails
		//return success/failure message
	//else
		//Confirm availability
			//If not available
				//display error; kill execution
		//Display form
			//Make date, time, fellow pre-set
			//If student:
				//Make pennkey preset
			//If fellow or director:
				//Allow to enter pennkey
			
	
?>

<?
        if(isset($_POST['submit'])){
		$f = (isset($_GET['f']) ? tcpdb::db_sanitize($_GET['f']) : $_POST['f']);
                if (!confirm_availability(tcpdb::db_sanitize($_GET['d']), tcpdb::db_sanitize($_GET['t']), $f))
                        die('Sorry, the selected fellow is not available at that time.  Please try another timeslot.  <a href="javascript:history.go(-1)">Back</a>');
                $booking_pennkey = get_auth_pennkey($_POST['pennkey'], $access_level); //Security feature that ensures a student can only book for him/herself; fellow can book for anybody.
                commit_to_db(
                        tcpdb::db_sanitize($f),
                        $booking_pennkey,
                        tcpdb::db_sanitize($_POST['firstname']),
                        tcpdb::db_sanitize($_POST['lastname']),
                        tcpdb::db_sanitize($_POST['email']),
                        tcpdb::db_sanitize($_GET['d']),
                        tcpdb::db_sanitize($_POST['comment']),
                        tcpdb::db_sanitize($_GET['t'])
                );

                if(
                        ($access_level <= ACCESS_LEVELS::intern)
                                && (isset($_POST['send_student_email']))
                        || ($access_level == ACCESS_LEVELS::student)
                  ) { //For anybody but students, the student e-mail is optional

                        send_student_email(tcpdb::db_sanitize($_POST['email']));
                }

                send_fellow_email(tcpdb::db_sanitize($f));

		$id = $tcpdatalink->get_apt_id_from_info(
			$booking_pennkey,
			tcpdb::db_sanitize($_GET['d']),
			tcpdb::db_sanitize($_GET['t'])
		);

		header ("Location: view_appointment.php?id=" . $id); 
		exit;

 //               echo('<h2>Appointment Booked</h2>');
 //               echo('Your appointment has been successfully booked.  Please <a href="view_appointment.php?id=">click here if you are</a> not redirected.');
 //               echo('<script>self.location="view_appointment.php?id=' . $id . '"</script>');
        } 
?>



<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
Book Appointment
 
</title> 
 
<meta name="keywords" content="penn engineering, technical communication program, technical communcation, tcp, technical writing"> 
 
<meta name="description" content="The Technical Communication Program (TCP) in the School of Engineering is designed to improve undergraduate students. proficiency in the skills of communication and information literacy by supporting these skills in SEAS undergraduate classes. The goal is for every SEAS undergraduate to participate in at least three Engineering courses (including the Senior Design Project) in which development of communication and library research skills is a focus"> 
 
<link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/screen.css" type="text/css" media="screen, projection"> 
 
<link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/print.css" type="text/css" media="print"> 
 
<!--[if IE]>
 
  <link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/ie.css" type="text/css" media="screen, projection">
 
<![endif]--> 
 
<link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/style.css" type="text/css" media="screen, projection"> 
 
</head> 
 
<body> 
 
<div id="header"> 
 
<div id="logo" class="prefix-1"><a href="http://www.seas.upenn.edu" class="noicon"><img src="http://www.seas.upenn.edu/~tcp/images/top_nav_eng_bnr.gif" alt="Penn Engineering Logo" /></a></div> 
 
</div> 
 
<div id="bar"></div> 
 
 
 
 
 
<div id="maincontainer"> 
 
<div id="contentwrapper"> 
 
<div id="contentcolumn"> 
 
<div class="innertube"> 


<h2 align="left">Book a TCP Appointment</h2> 

<p align="left">
	Please fill in the information below to finish booking your appointment.  If you have booked an appointment before, some of your information may already be filled in.
</p>
 
<p align="center">








<?
		

	if(!isset($_POST['submit'])){
		if(
			(
				isset($_GET['f'])
			) 
			&& 
				!confirm_availability(
					tcpdb::db_sanitize($_GET['d']),
					tcpdb::db_sanitize($_GET['t']),
					tcpdb::db_sanitize($_GET['f']))
				)	
			die('Sorry, the selected fellow is not available at that time.  Please try another timeslot.  <a href="javascript:history.go(-1)">Back</a>');
		$pennkey_str = ($access_level <= ACCESS_LEVELS::intern)
							? '<input type="text" name="pennkey" value="' . $pennkey . '"><br />'
							: '<input type="hidden" name="pennkey" value="' . $pennkey . '">' . $pennkey . '<br />';
		echo('
			<form method="POST" action="#">
			<table>
				<tr><td>Date:</td><td>' . tcp_date::raw_to_readable($_GET['d']) . '</td></tr>
				<tr><td>Time:</td><td>' . tcp_date::index_to_str($_GET['t']) . '</td></tr>
				<tr><td>Fellow:</td><td>' . get_fellow_form_control($_GET['d'], $_GET['t']) . '</td></tr>
				<tr><td>First name:</td><td><input type="text" name="firstname"></td></tr>
				<tr><td>Last name:</td><td><input type="text" name="lastname"></td></tr>
				<tr><td>Pennkey:</td><td>' . $pennkey_str. '</td></tr>
				<tr><td>E-mail Address:</td><td><input type="text" name="email" size="34" value="' . $pennkey . '@seas.upenn.edu"></td></tr>
				<tr><td>Comment:</td><td><textarea name="comment" rows=6 cols=50></textarea></td></tr>
		');
		
		if($access_level <= ACCESS_LEVELS::intern)
			echo('
				<tr><td colspan="2">Send e-mail notification to student? &nbsp; <input type="checkbox" name="send_student_email" value="1" checked></td></tr>
				<br />
			');
				
		echo('
				<tr><td colspan="2" align="center">
					<input type="submit" name="submit" value="Book Appointment" style="height: 60px; width: 450px">
				</td></tr>
			</table>
			</form>
		');
	
	}
?>


	

</p>

</div> 
 
</div> 
 
</div> 
 
 
 
 
 
<div id="nav"> 
 
<div class="innertube"> 
 
<?

if($access_level == ACCESS_LEVELS::director){
        echo('
<ul>
  <li class ="noicon" ><a href="index.php">Director Control Panel Home</a></li>
  <li class ="current sub noicon " ><a href="book.php?f=self">Schedule Appointment</a></li>
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li>
  <li class ="sub noicon " ><a href="add_fellow.php">Add Fellow</a></li>
  <li class ="sub noicon " ><a href="view_fellows.php">View / Modify Fellows</a></li>
  <li class ="sub noicon " ><a href="metadata.php">View system metadata</a></li>
  <li class ="noicon " ><a href="help.php">Help</a></li>
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li>

</ul>

        ');
} else if(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern)){
echo('
        <ul>
  <li class ="noicon current" ><a href="index.php">Fellow Control Panel Home</a></li>
  <li class ="current sub noicon " ><a href="book.php?f=self">Book Appointment</a></li>
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li>
  <li class ="sub noicon " ><a href="fellow_availability.php">Edit Weekly Availability</a></li>
  <li class ="sub noicon " ><a href="add_schedule_block.php">Add Schedule Block</a></li>
  <li class ="sub noicon " ><a href="view_schedule_blocks.php">View/Remove Schedule Blocks</a></li>
  <li class ="noicon " ><a href="help.php">Help</a></li>
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li>
</ul>

');
} else {
echo('
<ul>
  <li class ="noicon " ><a href="index.php">Home</a></li>
  <li class ="current sub noicon " ><a href="book.php">Book Appointment</a></li>
  <li class ="sub noicon " ><a href="view_all_appointments.php">View Appointments</a></li>
  <li class ="noicon " ><a href="help.php">Help</a></li>
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li>
</ul>
');
}
?>

</div> 
 
</div> 
 
 
 
 
 
<div id="rightcolumn"> 
 
<div class="innertube"> 
 
  <div align="left"><img src="http://www.seas.upenn.edu/~tcp/images/towne-front.jpg" alt="Towne" /> </div> 
 
</div> 
 
</div> 
 
</div> 
 
 
 
 
<div id="footer"><hr class="space" /><hr/><div class="innertube"><p><a href="http://www.seas.upenn.edu/~tcp" class ="noicon">Technical Communication Program</a> | <a href="http://www.seas.upenn.edu" class="noicon">School of Engineering and Applied Science</a> | <a href="http://www.upenn.edu" class="noicon">University of Pennsylvania</a><br /> 
   Room 306 Towne Building | 220 South 33rd Street | Philadelphia, PA 19104-6391 | t 215.573.6486</p></div></div> 
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"> 
</script> 
<script type="text/javascript"> 
_uacct = "UA-1517333-1";
urchinTracker();
</script> 
</body> 
</html> 
