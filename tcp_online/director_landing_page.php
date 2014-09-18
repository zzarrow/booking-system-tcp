<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
TCP Online > Director Control Panel
 
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

<? ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::director); ?>

<h2 align="left">Welcome to TCP Online!</h2> 
<h5>You are logged into the Director Control Panel.</h5> 
<p align="left">
	<ul>




<?

//echo('<br /><b>Director Landing Page</b>');

$next_appointment = $tcpdatalink->get_next_dept_apt();
if($next_appointment != null){
	$d = tcp_date::get_tcpdate_from_dbdate($next_appointment->get_apt_date())->to_string();
	$t = tcp_date::index_to_str($next_appointment->get_time_index());



	$fellow_name = $tcpdatalink->get_fellow_name_by_id($next_appointment->get_fellow_id()); 
	echo("<li>
		<strong>The next TCP appointment is on " . $d . " at " . $t . " with " . $fellow_name . ".</strong>");

	echo('<li style="margin-left: 8%" type="square"><a href="view_appointment.php?id=' . $next_appointment->get_id() . '">View appointment details</a></li>');
	echo('<li style="margin-left: 8%" type="square"><a href="cancel_appointment.php?id=' . $next_appointment->get_id() . '">Cancel Appointment</a></li></li>');
} 
else
	echo('<li>There are no upcoming TCP appointments.</li><br />');


?>

	</ul>
</p>

<p align="center" style="padding-top:5%">
	<a href="book.php"><img src="images/booking_system/book_apt.png" alt="Book Appointment" width="60%" /></a><br />
	<a href="view_all_appointments.php"><img src="images/booking_system/view_all_apts.png" alt="View All Appointments" width="60%" /></a>
</p>


</div> 
 
</div> 
 
</div> 
 
 
 
 
 
<div id="nav"> 
 
<div class="innertube"> 
 
<ul> 
  <li class ="noicon current" ><a href="index.php">Director Control Panel Home</a></li> 
  <li class ="sub noicon " ><a href="book.php?f=self">Schedule Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li> 
  <li class ="sub noicon " ><a href="add_fellow.php">Add Fellow</a></li> 
  <li class ="sub noicon " ><a href="view_fellows.php">View / Modify Fellows</a></li>
  <li class ="sub noicon " ><a href="metadata.php">View system metadata</a></li> 
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
