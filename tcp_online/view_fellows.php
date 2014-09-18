<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('<html><body>');
	//echo('<h2>TCP Staff</h2>');
	
	$fellows = $tcpdatalink->get_active_fellows(); //Array of fellow objects
?>







<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
TCP Online > TCP Staff
 
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

<h1 align="left">TCP Staff Members</h1> 
<p align="left">All active and inactive TCP Staff Members (interns, fellows, and director(s)) are displayed below. To modify data for a staff member, click the <strong>Modify</strong> link to the right of the staff member's row in the table.  You can also change the status of a fellow (<b>Active</b> vs. <b>Inactive</b>) on the Modify page.  Note that Inactive staff members are generally former staff members that have graduated or are no longer TCP Staff Members.  They are marked as inactive because completely deleting them from the system would cause problems with past appointment data.</p>
<p align="left">

<?	
	echo('<h3>Active Staff</h3>');
	
	echo('
		<table border="1">
			<tr>
				<td>First name</td>
				<td>Last name</td>
				<td>Pennkey</td>
				<td>E-mail Address</td>
				<td>Position</td>
				<td>Can book apts.?</td>
				<td>Major</td>
				<td>Class Year</td>
				<td></td>
			</tr>
	');
	
	for($i = 0; $i < sizeof($fellows); $i++){
		$access_level = $fellows[$i]->get_access_level();
		if($access_level == ACCESS_LEVELS::director)
			$access_level = "Director";
		if($access_level == ACCESS_LEVELS::fellow)
			$access_level = "Fellow";
		if($access_level == ACCESS_LEVELS::intern)
			$access_level = "Intern";
		
		echo('
			<tr>
				<td>' . $fellows[$i]->get_firstname() . '</td>
				<td>' . $fellows[$i]->get_lastname() . '</td>
				<td>' . $fellows[$i]->get_pennkey() . '</td>
				<td>' . $fellows[$i]->get_email() . '</td>
				<td>' . $access_level . '</td>
				<td>' . (($fellows[$i]->can_book() == 1)? "Yes" : "No" ) . '</td>
				<td>' . $fellows[$i]->get_major() . '</td>
				<td>' . $fellows[$i]->get_class_year() . '</td>
				<td><a href="modify_fellow.php?fid=' . $fellows[$i]->get_id() . '">Modify</a></td>
			</tr>
		');
	}
	
	echo('<tr><td colspan="9"><center><a href="add_fellow.php">Add TCP Staff Member</a></center></td></tr>');	
	echo('</table>');
	
	$fellows = $tcpdatalink->get_inactive_fellows(); //Array of fellow objects
	
	echo('<h3>Inactive Staff</h3>');
	
	echo('
		<table border="1">
			<tr>
				<td>First name</td>
				<td>Last name</td>
				<td>Pennkey</td>
				<td>E-mail Address</td>
				<td>Position</td>
				<td>Major</td>
				<td>Class Year</td>
				<td></td>
			</tr>
	');
	
	for($i = 0; $i < sizeof($fellows); $i++){
		$access_level = $fellows[$i]->get_access_level();
		if($access_level == ACCESS_LEVELS::director)
			$access_level = "Director";
		if($access_level == ACCESS_LEVELS::fellow)
			$access_level = "Fellow";
		if($access_level == ACCESS_LEVELS::intern)
			$access_level = "Intern";
		
		echo('
			<tr>
				<td>' . $fellows[$i]->get_firstname() . '</td>
				<td>' . $fellows[$i]->get_lastname() . '</td>
				<td>' . $fellows[$i]->get_pennkey() . '</td>
				<td>' . $fellows[$i]->get_email() . '</td>
				<td>' . $access_level . '</td>
				<td>' . $fellows[$i]->get_major() . '</td>
				<td>' . $fellows[$i]->get_class_year() . '</td>
				<td><a href="modify_fellow.php?fid=' . $fellows[$i]->get_id() . '">Modify</a></td>
			</tr>
		');
	}
	
	
?>

</table>
	
</p>

</div> 
 
</div> 
 
</div> 
 
 
 
 
 
<div id="nav"> 
 
<div class="innertube"> 
 
<ul> 
  <li class ="noicon" ><a href="index.php">Director Control Panel Home</a></li> 
  <li class ="sub noicon " ><a href="book.php?f=self">Schedule Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li> 
  <li class ="sub noicon " ><a href="add_fellow.php">Add Fellow</a></li> 
  <li class ="current noicon " ><a href="view_fellows.php">View / Modify Fellows</a></li>
  <li class ="sub noicon " ><a href="metadata.php">View system metadata</a></li> 
  <li class ="noicon " ><a href="help.php">Help</a></li> 
  <li class="noicon "><a href="http://www.seas.upenn.edu/~tcp">&laquo; Back to TCP Site</a></li> 
</ul> 
<br /> 
<!-- <p id="apply" ><a  href="http://www.seas.upenn.edu/~tcp/fellowapp.shtml" ><strong>Apply to be a Fellow</strong></a></p> -->
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
