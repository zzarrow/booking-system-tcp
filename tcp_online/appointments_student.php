<?

function generate_apt_table($data){

	for($i = 0; $i < sizeof($data); $i++){
		$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id($data[$i]->get_fellow_id()); 
		echo('
			<ul>
				<li><strong>' . tcp_date::raw_to_readable($data[$i]->get_apt_date()) . '</strong> - <span style="color: black">' . tcp_date::index_to_str($data[$i]->get_time_index()) . '</span></li>
				<li type="square" style="margin-left: 5%">Fellow: <a href="mailto:' . $fellow->get_email() . '">' . $fellow->get_firstname() . ' ' . $fellow->get_lastname() . '</a> <span style="color: gray">(' . $fellow->get_major() . ' ' . $fellow->get_class_year() . ')</span></li>
				<li type="square" style="margin-left: 5%"><strong>Booking comment</strong>: ' . $data[$i]->get_comment() . '</li>
			</ul>
		');
	}

/**
	echo("<table border = 1><tr><td><b>id</b></td><td><b>fellow_id</b></td><td><b>pennkey</b></td><td><b>firstname</b></td><td><b>lastname</b></td><td><b>email</b></td><td><b>apt_date</b></td><td><b>comment</b></td><td><b>filename</b></td><td><b>upload_date</b></td><td><b>time_index</b></td><td><b>feedback_left</b></td></tr>");
	for($i = 0; $i < sizeof($data); $i++)
		echo("<tr><td>" . $data[$i]->get_id() . "</td><td>" . $data[$i]->get_fellow_id() . "</td><td>" . $data[$i]->get_pennkey() . "</td><td>" . $data[$i]->get_first_name() . "</td><td>" . $data[$i]->get_last_name() . "</td><td>" . $data[$i]->get_email() . "</td><td>" . $data[$i]->get_apt_date() . "</td><td>" . $data[$i]->get_comment() . "</td><td>" . $data[$i]->get_filename() . "</td><td>" . $data[$i]->get_filedate() . "</td><td>" . $data[$i]->get_time_index() . "</td><td>" . $data[$i]->get_feedback_left() . "</td></tr>");
	echo("</table>");

**/
}

$upcoming_appointments = $tcpdatalink->create_appointments_from_query('SELECT * from `appointments` WHERE 
												`pennkey`="' . $pennkey . '" AND
												(
												    (`apt_date` > ' . tcp_date::get_today_as_db() . ')
													OR (
														(`apt_date` = ' . tcp_date::get_today_as_db() . ')
														AND `time_index` >= ' . tcp_date::get_current_time_index() . '
													)
												 )					
										ORDER BY `apt_date` DESC
										');
$prev_appointments = $tcpdatalink->create_appointments_from_query('SELECT * from `appointments` WHERE 
												`pennkey`="' . $pennkey . '" AND
												(
												    (`apt_date` < ' . tcp_date::get_today_as_db() . ')
													OR (
														(`apt_date` = ' . tcp_date::get_today_as_db() . ')
														AND `time_index` <= ' . tcp_date::get_current_time_index() . '
													)
												 )					
										ORDER BY `apt_date` DESC
										');

?>






<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
Your TCP Appointments
 
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


<h1 align="left">Your TCP Appointments</h1> 
 
<p align="left">
<div style="padding-left: 5%">

<?
echo('<h3>Upcoming Appointments</h3>');
if(sizeof($upcoming_appointments) == 0)//PHP sizeof gives array length
	echo('<li><span style="color: gray">You have no upcoming TCP appointments.</span></li>');
else
	generate_apt_table($upcoming_appointments);
?>

</div>
</p>

<p align="left">
<div style="padding-left: 5%">
<?
echo('<h3>Previous Appointments</h3>');
if(sizeof($prev_appointments) == 0)
	echo('<li style="margin-left:20%"><span style="color: gray">There are no previous TCP appointments to display.</span></li>');
else
	generate_apt_table($prev_appointments);

?>






</div>
</p>


</div> 
 
</div> 
 
</div> 
 
 
 
 
 
<div id="nav"> 
 
<div class="innertube"> 
 
<ul> 
  <li class ="noicon " ><a href="index.php">Home</a></li> 
  <li class ="sub noicon " ><a href="book.php">Book Appointment</a></li> 
  <li class ="sub noicon current" ><a href="view_all_appointments.php">View Appointments</a></li> 
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
