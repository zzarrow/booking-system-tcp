<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	$pennkey = $_SERVER['REMOTE_USER'];
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

<h2 align="left">TCP Online: System Metadata</h1> 
<p align="left">The global variables used throughout the TCP Online system are displayed below.  To modify these values, please click on the <b>Edit Data</b> link.</p>
<p align="left">
<?
	echo('<center><table border="1"><tr><td><b>Key</b></td><td><b>Value</b></td></tr>');
	echo('<tr><td>Default Meeting Location</td><td>' . $tcpdatalink->get_meta_key("mtg_location") . '</td></tr>');
	echo('<tr><td>Contact Name</td><td>' . $tcpdatalink->get_contact_name() . '</td></tr>');
	echo('<tr><td>Contact E-mail</td><td>' . $tcpdatalink->get_contact_email() . '</td></tr>');
	echo('<tr><td>System Administrator Name</td><td>' . $tcpdatalink->get_sysadmin_name() . '</td></tr>');
	echo('<tr><td>System Administrator E-mail</td><td>' . $tcpdatalink->get_sysadmin_email() . '</td></tr>');
	echo('<tr><td>System Base URL</td><td>' . $tcpdatalink->get_system_url() . '</td></tr>');
	echo('</table>');
	echo('<a href="metadata.php?mode=edit">Edit Data</a> (Coming Soon)</center>');
?>	
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
  <li class ="sub noicon " ><a href="view_fellows.php">View / Modify Fellows</a></li>
  <li class ="current sub noicon " ><a href="metadata.php">View system metadata</a></li> 
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
