<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	$pennkey = $_SERVER['REMOTE_USER'];	
?>



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

<p align="left">
<h2>Add a TCP Staff Member</h2>

<?
	ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::director);

	if(isset($_POST['submit'])){
		//Commit data
		
		$tcpdatalink->add_fellow(
			tcpdb::db_sanitize($_POST['firstname']),
			tcpdb::db_sanitize($_POST['lastname']),
			tcpdb::db_sanitize($_POST['email']),
			tcpdb::db_sanitize($_POST['active']),
			tcpdb::db_sanitize($_POST['pennkey']),
			tcpdb::db_sanitize($_POST['access_level']),
			tcpdb::db_sanitize($_POST['major']),
			tcpdb::db_sanitize($_POST['class_year']),
			tcpdb::db_sanitize($_POST['can_book'])
		);
		
		echo('<br /><center>New TCP Staff Member <b>' . $_POST['firstname'] . ' ' . $_POST['lastname'] . '</b> has been successfully created.<br />
				<br /><a href="index.php">Director Control Panel Home</a> &nbsp; | &nbsp; <a href="view_fellows.php">View / Modify TCP Staff</a> &nbsp; | &nbsp; <a href="add_fellow.php">Add Another TCP Staff Member</a> 
			</center>');					
	} else{		
		//Display form
		echo('
		
			<form method="POST" action="">
			<center>
			<table>
				<tr>
					<td><br />Add new:</td>
					<td>
						<input type="radio" name="access_level" value="2">&nbsp; Intern<br />
						<input type="radio" name="access_level" value="1" checked>&nbsp; Fellow<br />
						<input type="radio" name="access_level" value="0">&nbsp; Director<br />
					</td>
				</tr>
				<tr>
					<td>First name</td>
					<td><input type="text" name="firstname"></td>
				</tr>
				<tr>
					<td>Last name</td>
					<td><input type="text" name="lastname"></td>
				</tr>
				<tr>
					<td>Pennkey</td>
					<td><input type="text" name="pennkey"></td>
				</tr>
				<tr>
					<td>E-mail Address</td>
					<td><input type="text" name="email"></td>
				</tr>
				<tr>
					<td>Major</td>
					<td><input type="text" name="major"></td>
				</tr>
				<tr>
					<td>Class year</td>
					<td><input type="text" name="class_year"></td>
				</tr>
				<tr>
					<td>Allowed to book<br />appointments?</td>
					<td><input type="radio" name="can_book" value="1" checked>Yes<br />
					<input type="radio" name="can_book" value="0">No</td>
				</tr>
				<tr>
					<td valign="center">Is fellow active?</td>
					<td>
						<input type="radio" name="active" value="1" checked>Yes<br />
						<input type="radio" name="active" value="0">No<br />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" name="submit" value="Create" style="height:50px; width: 200px">
					</td>
				</tr>
			</table>
			</center>
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
 
<ul> 
  <li class ="noicon" ><a href="index.php">Director Control Panel Home</a></li> 
  <li class ="sub noicon " ><a href="book.php?f=self">Schedule Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li> 
  <li class ="current sub noicon " ><a href="add_fellow.php">Add Fellow</a></li> 
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
