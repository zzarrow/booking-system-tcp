<?
	include('tcp_common.php');
	
	ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::fellow);
	
	$tcpdatalink = new tcpdb();
//	echo('<html><body>');
//	echo('<h2>Add Schedule Blocks</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];

?>

<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
TCP Online > Add Schedule Block
 
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

<? ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::fellow); ?>

<h2 align="left">Add Schedule Blocks</h2> 
<?
if(!isset($_POST['submit'])){
echo('<p align="left">
	Schedule blocks allow you to block out appointment timeslots that would otherwise be available for booking based on your <a href="fellow_availability.php">Weekly Availability</a>.  To block out timeslots, first select a date, and then check all of the boxes for the times you would like to block out.  When you are finished, press the button at the bottom to add the schedule blocks.
</p>');
}
?>
<p align="center">


<?
	if(isset($_POST['submit'])){
		//Commit changes
		//We have:
		//	year = YYYY
		//	month = mm
		//	day = dd
		//	ti{n} foreach time index that was selected
		
		$date_str = tcpdb::db_sanitize($_POST['year']) . tcpdb::db_sanitize($_POST['month']) . tcpdb::db_sanitize($_POST['day']);
		
		$ctr = 0;
		for($i = 0; $i <= 23; $i++){
			if(isset($_POST['ti' . $i])){
				//Originally created a schedule_block object and passed it to the tcpdb method, but weird
				//bugs prevented this from working.  So, we just directly pass the values and informally restrict
				//the usage of schedule_block to SELECT requests. 
				tcpdb::add_schedule_block($tcpdatalink->get_fellow_id_by_pennkey($pennkey), $date_str, $i);
				$ctr++;			
			}
		}	
		
		echo('<strong>' . $ctr . '</strong> appointment slots have been successfully blocked out.</p>
			<p align="center"><a href="view_schedule_blocks.php">View Schedule Blocks</a> &nbsp; | &nbsp; <a href="add_schedule_block.php">Add More Schedule Blocks</a>&nbsp; | &nbsp; <a href="index.php">Fellow Control Panel Home</a></p>');

	} else{
		//Display form
		
		//Month combo box
		//Day combo box
		//Year combo box
		//^^^All have today() as default values
		//Then a list of checkboxes foreach time index
		//onSubmit, add a block foreach time index selected
		//issue: only display checkboxes based on availability?
			//fornow: no; doesn't matter if a redundancy is added
		
		$month_names = array(
								"January",
								"February",
								"March",
								"April",
								"May",
								"June",
								"July",
								"August",
								"September",
								"October",
								"November",
								"December"
						);
		echo('
			<center>
			<form method="POST" action="#">
				
				<table border="0">
					<tr>
						<td colspan="3">
			Select a date: &nbsp; <select name = "month">
		');
		
		for($i = 1; $i <= 12; $i++){
			//In date string: F = full text, m = leading zero, n = no leading zero
			$i_fmt = $i;
			if($i < 10)
				$i_fmt = "0" . $i;
			if($i == (int)date('n'))
				echo('<option value = "' . $i_fmt . '" selected>' . $month_names[$i - 1] . '</option>');
			else
				echo('<option value = "' . $i_fmt . '">' . $month_names[$i - 1] . '</option>');
		}
		
		echo('
				</select>
				<select name = "day">
		');
		
		for($i = 1; $i <=31; $i++){
			//Yes, stuff like February 31st will be selectable but this won't cause any bugs so it's fine
			if($i == (int)date('d'))
				echo('<option value = "' . $i . '" selected>' . date('j') . '</option>');
			else{
				$i_fmt = $i;
				if($i < 10)
					$i_fmt = "0" . $i;
				echo('<option value = "' . $i_fmt . '">' . $i . '</option>');
			}
		}
		
		echo('	
				</select>
				<select name = "year">
		');
		
		//Display current year and next year (will cover academic year)
		echo('<option value="' . date('Y') . '">' . (int)date('Y') . '</option>');
		echo('<option value="' . ((int)date('Y') + 1) . '">' . ((int)date('Y') + 1) . '</option>');
		
		echo('				
				</select><br />
				
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							Select the times you would like to block out:
						</td>
					</tr>
					<tr>
						<td>
		');
		
		for($i = 0; $i < 24; $i++){
			echo('<input type="checkbox" name="ti' . $i . '" value="1">&nbsp; ' . tcp_date::index_to_str($i) . '<br />');
			if((((($i + 1) % 8) == 0)) && ($i != 23))
				echo('</td><td>');
					
		}

		echo('
						</td>
					</tr>
				</table>
			<br />
			<input type="submit" name="submit" value="Add Schedule Blocks" style="height: 50px; width: 300px">
				
								
			</form></center>
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
  <li class ="noicon " ><a href="index.php">Fellow Control Panel Home</a></li> 
  <li class ="sub noicon " ><a href="book.php?f=self">Book Appointment</a></li> 
  <li class ="sub noicon " ><a href="view_all_appointments.php">View All Appointments</a></li> 
  <li class ="sub noicon " ><a href="fellow_availability.php">Edit Weekly Availability</a></li>
  <li class ="current sub noicon " ><a href="add_schedule_block.php">Add Schedule Block</a></li>
  <li class ="sub noicon " ><a href="view_schedule_blocks.php">View/Remove Schedule Blocks</a></li>
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
