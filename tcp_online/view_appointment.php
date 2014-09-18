
<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
//	echo('<html><body>');
//	echo('<h2>Appointment Details</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
//	echo('Logged in as: ' . $pennkey);
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
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
	
	if(!isset($_GET['id']))
		die('Invalid appointment ID.');
	$aid = (int)$_GET['id'];
	
	$appointment = $GLOBALS['tcpdatalink']->get_apt_by_id($aid);
	if($appointment == null)
		die('Invalid appointment ID.');
	
	if(!can_view_apt($pennkey, $appointment))
		die('You are unauthorized to view this appointment. <br /><a href="javascript:history.go(-1)">Back</a>');
	
	$fellow = $tcpdatalink->get_fellow_by_id($appointment->get_fellow_id());
	

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


<h2 align="left">Appointment Details</h2> 
 
<p align="left">
<? if($access_level == ACCESS_LEVELS::student)
	echo('Your TCP appointment was booked with the following information:');   
   if(($access_level == ACCESS_LEVELS::fellow) || $access_level == ACCESS_LEVELS::intern)
	echo('A TCP appointment was booked with you with the following information:');
   if($access_level == ACCESS_LEVELS::director)
	echo('A TCP appointment was booked with the following information:');
?>

<ul>



<?

	if($access_level != ACCESS_LEVELS::student){
		echo('<li>Student: <strong>' . $appointment->get_first_name() . ' ' . $appointment->get_last_name() . '</strong></li>');
		echo('<li>E-mail address: <a href="mailto:' . $appointment->get_email() . '">' . $appointment->get_email() . '</a></li>');
		echo('<li>Pennkey: <strong>' . $appointment->get_pennkey() . '</strong></li>');
	}

	if($access_level != ACCESS_LEVELS::student) echo('<br />');
	
	echo('<li>Date: <strong>' . tcp_date::raw_to_readable($appointment->get_apt_date()) . '</strong></li>');
	echo('<li>Time: <strong>' . tcp_date::index_to_str($appointment->get_time_index()) . '</strong></li>');
	echo('<li>Location: <strong>' . tcpdb::get_meta_key("mtg_location") . '</strong></li>');

   if($access_level != ACCESS_LEVELS::student){
	echo('<br /><li>' . $appointment->get_first_name() . ' left the following comment when booking the appointment:</li>
	<li type="square" style="margin-left: 5%"><strong>' . (($appointment->get_comment() == "") ? "<font color=\"gray\">(None)</font>" : $appointment->get_comment()). '</strong></li>
	');
   } else {
	echo('<li>You will be meeting with TCP Fellow <strong>' . $fellow->get_firstname() . ' ' . $fellow->get_lastname() . '</strong> (<a href="mailto:' . $fellow->get_email() . '">' . $fellow->get_email() . '</a>)</li>');
	echo('<li type="square" style="margin-left: 5%">Please send a draft or outline of your assignemnt to ' . $fellow->get_firstname() . ' at least 48 hours before your appointment.</li>');
   }
	echo('</ul>');

   if($access_level == ACCESS_LEVELS::student)
	echo('If you would like to change this appointment, please contact ' . $fellow->get_firstname() . ' directly, or <a href="cancel_appointment.php?id=' . $appointment->get_id() . '">cancel the appointment</a> using the button below and then <a href="book.php">book again</a>.');
   else
	echo('If you would like to change this appointment, please contact ' . $appointment->get_first_name() .  ' directly, or note ' . $appointment->get_first_name() . '\'s Pennkey (' . $appointment->get_pennkey() . '), then <a href="cancel_appointment.php?id=' . $appointment->get_id() . '">cancel the appointment</a> using the button below, and then <a href="book.php">book again</a>.');

/**
        echo('<br /><ul><li>Appointment ID: ' . $appointment->get_id() . '</li>');
        echo('<li>Student: <a href="mailto:' . $appointment->get_email() . '">' . $appointment->get_first_name() . ' ' . $appointment->get_last_name() . '</a> (' . $appointment->get_pennkey() . ')</li>');
        //echo('<li>Fellow: <a href="mailto:' . $fellow->get_email() . '">' . $fellow->get_first_name() . ' ' . $fellow->get_last_name() . '</a>(' . $fellow->get_major() . ' ' . $fellow->get_class_year() . ')</li>');
        echo('<li>Fellow: ' . $fellow->get_firstname() . ' ' . $fellow->get_lastname() . ' (' . $fellow->get_major() . ' ' . $fellow->get_class_year() . ')</li>');
        echo('<li>Date: ' . tcp_date::raw_to_readable($appointment->get_apt_date()) . '</li>');
        echo('<li>Time: ' . tcp_date::index_to_str($appointment->get_time_index()) . '</li>');
        echo('<li>Comment: ' . $appointment->get_comment() . '</li>');
        echo('<li>Feedback left: ' . $appointment->get_feedback_left() . '</li>');
        echo('</ul>');

        echo('<br /><a href="cancel_appointment.php?id=' . $appointment->get_id() . '">Cancel Appointment</a>');
**/
?>




</ul>





	

</p>

<p align="center" style="padding-top:5%">
	<a href="cancel_appointment.php?id=<? echo($appointment->get_id()); ?>"><img src="images/booking_system/cancel_apt.png" alt="Cancel Appointment" /></a>
</p>


</div> 
 
</div> 
 
</div> 
 
 
 
 
 
<div id="nav"> 
 
<div class="innertube"> 
 
<ul> 
<?
  if($access_level == ACCESS_LEVELS::fellow){
    echo('
  <li class ="noicon" ><a href="index.php">Fellow Control Panel Home</a></li> 
  <li class ="sub noicon " ><a href="book.php?f=self">Book Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li> 
  <li class ="sub noicon " ><a href="fellow_availability.php">Edit Weekly Availability</a></li> 
  <li class ="sub noicon " ><a href="add_schedule_block.php">Add Schedule Block</a></li> 
  <li class ="sub noicon " ><a href="view_schedule_blocks.php">View/Remove Schedule Blocks</a></li> 
  <li class ="noicon " ><a href="help.php">Help</a></li> 
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li>
    ');
  } else if($access_level == ACCESS_LEVELS::director){

echo('
<ul>
  <li class ="noicon" ><a href="index.php">Director Control Panel Home</a></li>
  <li class ="sub noicon " ><a href="book.php?f=self">Schedule Appointment</a></li>
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li>
  <li class ="sub noicon " ><a href="add_fellow.php">Add Fellow</a></li>
  <li class ="sub noicon " ><a href="view_fellows.php">View / Modify Fellows</a></li>
  <li class ="sub noicon " ><a href="metadata.php">View system metadata</a></li>
  <li class ="noicon " ><a href="help.php">Help</a></li>
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li>
</ul>
<br />
');
}else {
  echo('<li class ="noicon " ><a href="index.php">Home</a></li> 
  <li class ="sub noicon " ><a href="book.php">Book Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View Appointments</a></li> 
  <li class ="noicon " ><a href="help.php">Help</a></li> 
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li>');
  }
?>
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
