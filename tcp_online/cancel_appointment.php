
<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Cancel Appointment</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey . '<br />');
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	function send_email_to_student($_apt){
		$subj = "TCP Appointment Canceled (" . tcp_date::raw_to_readable($_apt->get_apt_date()) . " at " . tcp_date::index_to_str($_apt->get_time_index()) . ")"; 
		$msg = "Dear " . $_apt->get_first_name() . ",<br /><br />
		
		Your TCP appointment originally scheduled for " . tcp_date::raw_to_readable($_apt->get_apt_date()) . " at " . tcp_date::index_to_str($_apt->get_time_index()) . "  <b>has been canceled</b>.<br /><br />
		
		To book another appointment, please visit the <a href=\"http://www.seas.upenn.edu/~tcp\">TCP Online Booking System</a>.  If you have any questions, please contact " . $GLOBALS['tcpdatalink']->get_contact_name() . " at <a href=\"mailto:" . $GLOBALS['tcpdatalink']->get_contact_email() . "\">" . $GLOBALS['tcpdatalink']->get_contact_email() . "</a>.<br /><br />		
		
		Thank you,<br />
		- Technical Communication Program<br /><br />
		
		<b>Note:</b> This e-mail address is not monitored, so <b>please do not reply directly to this e-mail</b>.
		
		";
		
		//echo('<br />Sending e-mail to ' . $_addr . '<br />Subject: ' . $subj . '<br />Message:<br />' . $msg);
		
		//FOR DEBUG:
		//$email = "zzarrow@gmail.com";

		//FOR PRODUCTION:
		$email = $_apt->get_email();		

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: ' . $_apt->get_first_name() . ' ' . $_apt->get_last_name() . '<' . $email . '>' . "\r\n";
		$headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";
		
		mail("",
			$subj,
			$msg,
			$headers);
		
		return;
	}
	
	function send_email_to_fellow($_apt){
		$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id($_apt->get_fellow_id());
		
		$subj = "TCP Appointment Canceled (" . tcp_date::raw_to_readable($_apt->get_apt_date()) . " at " . tcp_date::index_to_str($_apt->get_time_index()) . ")"; 
		$msg = "Dear " . $fellow->get_firstname() . ",<br /><br />
		
		Your TCP appointment originally scheduled for " . tcp_date::raw_to_readable($_apt->get_apt_date()) . " at " . tcp_date::index_to_str($_apt->get_time_index()) . " with " . $_apt->get_first_name() . " " . $_apt->get_last_name() . " <b>has been canceled</b>.<br /><br />
		
		To book another appointment, please visit the <a href=\"http://www.seas.upenn.edu/~tcp\">TCP Online Booking System</a>. If you have any questions, please contact " . $GLOBALS['tcpdatalink']->get_contact_name() . " at <a href=\"mailto:" . $GLOBALS['tcpdatalink']->get_contact_email() . "\">" . $GLOBALS['tcpdatalink']->get_contact_email() . "</a><br /><br />		
		
		Thank you,<br />
		- Technical Communication Program<br /><br />
		
		<b>Note:</b> This e-mail address is not monitored, so <b>please do not reply directly to this e-mail</b>.
		";
		
		//echo('<br />Sending e-mail to ' . $_addr . '<br />Subject: ' . $subj . '<br />Message:<br />' . $msg);
		
		//FOR DEBUG:
		//$email = "zzarrow@gmail.com";

		//FOR PRODUCTION:
		$email = $fellow->get_email();		

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: ' . $fellow->get_firstname() . ' ' . $fellow->get_lastname() . '<' . $email . '>' . "\r\n";
		$headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";
		
		mail("",
			$subj,
			$msg,
			$headers);
		
		return;
	}
	
	function can_view_apt($_pennkey, $_appointment){
		if($GLOBALS['access_level'] == ACCESS_LEVELS::director)
			return true; //Director can view everything
		
		if(($GLOBALS['access_level'] == ACCESS_LEVELS::fellow) ||
			($GLOBALS['access_level'] == ACCESS_LEVELS::intern)){

			//fid on appointment has to match user's fid
			
			return ($_appointment->get_fellow_id()
					== $GLOBALS['tcpdatalink']->get_fellow_id_by_pennkey($GLOBALS['pennkey']));	
		}
		
		if($GLOBALS['access_level'] == ACCESS_LEVELS::student){
			//appointment's student pennkey has to match user's pennkey
			return ($_appointment->get_pennkey() == $GLOBALS['pennkey']);
		}
	}

?>







<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
People
 
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


<h2 align="left">Cancel Appointment</h2> 
 
<p align="left">
Are you sure you want to cancel this appointment?
</p>

<p align="center" style="padding-top:1%">
<?
	
	if(!isset($_GET['id']))
		die('Invalid appointment ID.');
	$aid = (int)$_GET['id'];
	
	$appointment = $GLOBALS['tcpdatalink']->get_apt_by_id($aid);
	if($appointment == null)
		die('Invalid appointment ID.');
	
	if(!can_view_apt($pennkey, $appointment))
		die('You are not authorized to view this appointment. <br /><a href="javascript:history.go(-1)">Back</a>');
	
	if(isset($_GET['c']) && ($_GET['c'] == 'y')){
		$tcpdatalink->cancel_appointment($aid);
		if(isset($_GET['e']) && ($_GET['e'] == 't'))
			send_email_to_student($appointment);
		send_email_to_fellow($appointment);

		echo('The appointment has been canceled.<br /><a href="book.php">Click here</a> to book another appointment, or <a href="index.php">return to the home page.</a>');
	} else {
		//echo('Are you sure you would like to cancel this appointment?<br />');
		if($access_level <= ACCESS_LEVELS::intern) //Anybody but student has option to suppress
			echo('<a href="cancel_appointment.php?id=' . $aid . '&c=y&e=t"><img src="images/booking_system/cancel_apt_confirm_and_notify.png" alt="Yes, and automatically notify student" /></a><a href="cancel_appointment.php?id=' . $aid . '&c=y"><img src="images/booking_system/cancel_apt_confirm_and_suppress.png" alt="Yes, and suppress e-mail to student" /></a><br /><a href="view_appointment.php?id=' . $aid . '"><img src="images/booking_system/cancel_apt_abort.png" alt="No - Go back" /></a>');
		else
			echo('<a href="cancel_appointment.php?id=' . $aid . '&c=y"><img src="images/booking_system/cancel_apt_confirm.png" style="padding-right: 3%"/></a> <a href="view_appointment.php?id=' . $aid . '"><img src="images/booking_system/cancel_apt_abort.png" style="padding-left: 3%" /></a>');
	}			
	
?>

</p>


</div> 
 
</div> 
 
</div> 
 
 
 
 
 
<div id="nav"> 
 
<div class="innertube"> 
 
<ul> 
  <li class ="noicon " ><a href="index.php">Home</a></li> 
  <li class ="sub noicon " ><a href="book.php">Book Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View Appointments</a></li> 
  <li class ="noicon " ><a href="help.php">Help</a></li> 
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li> 
</ul> 
<br /> 
<!-- <p id="apply" ><a  href="http://www.seas.upenn.edu/~tcp/fellowapp.shtml" ><strong>Apply to be a Fellow</strong></a></p> -->
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

