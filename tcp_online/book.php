<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Book an Appointment</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey);
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	if(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern)){
		header("Location: book_avail.php?f=self");
		exit;
	}

	//At this point in the booking process, the director uses the same page as students.
?>








<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
Book an Appointment
 
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

Please select the TCP fellow with whom you would like to meet.  If you do not have a preference, press the button on the left to see all available appointment slots.

</p>
<hr>
<p align="left">
<table border="0" style="border: 0px;">
	<tr>
		<td style="text-align: right; margin-right: 0px; padding-right: 0px; border-bottom: 0px; border-right: 0px; vertical-align:top; margin-top:2px;"><a href="book_avail.php?f=all"><img src="images/booking_system/book_with_any_fellow.png" width="75%" alt="Book with any fellow" /></a></td>
		<td style="text-align: left; margin-left:0px; padding-left:0px; vertical-align: top; padding-top:17px; border-bottom: 0px; border-right: 0px" width="50%">
			<ul>
<?
	$fellows = $tcpdatalink->get_bookable_fellows();	
	for($i = 0; $i < sizeof($fellows); $i++){
		echo('

			<li>
				<a href="book_avail.php?f=' . $fellows[$i]->get_id() . '">' . $fellows[$i]->get_firstname() . ' ' . $fellows[$i]->get_lastname() . '
				</a>
				<font color="gray">
					(' . $fellows[$i]->get_major() . ' ' . $fellows[$i]->get_class_year() . ')
				</font>
			</li>
		');
		//TODO include feature to provide # of open slots for each fellow
	}

?>
			</ul>
		</td>
	</tr>
</table>




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
