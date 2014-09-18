<?
	include('tcp_common.php');
	
	ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::fellow);
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Weekly Availability</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey);

	$fellow = $tcpdatalink->get_fellow_by_pennkey($pennkey);

	function isChecked($dow, $time_index){
		if($GLOBALS['fellow']->get_availability_at_time_index($dow, $time_index) ==1)
			return "checked";
		else
			return "";
	}

?>



















<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
TCP Online > Fellow Control Panel
 
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

<h2 align="left">Edit Weekly Availability</h2> 
<p align="left">
<? if(!isset($_POST['submit'])) echo('The table below determines your weekly availability for students to book TCP appointments with you.  Check the boxes for the timeslots in which you are available to meet with students, then press the <strong>Update Hours</strong> button below to save.'); ?>




<?

	if(isset($_POST['submit'])){
		//m0...m23
		//t0...t23
		//...
		
		$availability_str = '';		
		$dow_arr = array('m', 't', 'w', 'r', 'f');
				
		for($i = 0; $i < 5; $i++) //dow loop
			for($j = 0; $j <= 23; $j++) //time_index loop
				$availability_str = $availability_str . (isset($_POST[$dow_arr[$i] . $j]) ? $_POST[$dow_arr[$i] . $j] : 0);
		
		//echo('<br />Availability string: ' . $availability_str);
		
		$tcpdatalink->update_fellow_availability($fellow, $availability_str);
		
		//echo('<br />Availability updated.');
		echo('<center>Your weekly availability has been updated.<br />
			<a href="index.php">Home</a>&nbsp; | &nbsp; <a href="fellow_availability.php">Back to Availability</a>
		</center>');
	
	} else{

		echo('
			<form method="POST" value="#">
		<center>
			<table border = "1">
				<tr>
					<td></td>
					<td align="center"><b>Monday</b></td>
					<td align="center"><b>Tuesday</b></td>
					<td align="center"><b>Wednesday</b></td>
					<td align="center"><b>Thursday</b></td>
					<td align="center"><b>Friday</b></td>
				</tr>
				<tr>
					<td align="center">9:00am</td>
					<td align="center"><input type="checkbox" name="m0" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 0)) .' ></td>
					<td align="center"><input type="checkbox" name="t0" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 0)) .' ></td>
					<td align="center"><input type="checkbox" name="w0" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 0)) .' ></td>
					<td align="center"><input type="checkbox" name="r0" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 0)) .' ></td>
					<td align="center"><input type="checkbox" name="f0" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 0)) .' ></td>
				</tr>
				<tr>
					<td align="center">9:30am</td>
					<td align="center"><input type="checkbox" name="m1" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 1)) .' ></td>
					<td align="center"><input type="checkbox" name="t1" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 1)) .' ></td>
					<td align="center"><input type="checkbox" name="w1" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 1)) .' ></td>
					<td align="center"><input type="checkbox" name="r1" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 1)) .' ></td>
					<td align="center"><input type="checkbox" name="f1" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 1)) .' ></td>
				</tr>
				<tr>
					<td align="center">10:00am</td>
					<td align="center"><input type="checkbox" name="m2" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 2)) .' ></td>
					<td align="center"><input type="checkbox" name="t2" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 2)) .' ></td>
					<td align="center"><input type="checkbox" name="w2" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 2)) .' ></td>
					<td align="center"><input type="checkbox" name="r2" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 2)) .' ></td>
					<td align="center"><input type="checkbox" name="f2" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 2)) .' ></td>
				</tr>			
				<tr>
					<td align="center">10:30am</td>
					<td align="center"><input type="checkbox" name="m3" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 3)) .' ></td>
					<td align="center"><input type="checkbox" name="t3" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 3)) .' ></td>
					<td align="center"><input type="checkbox" name="w3" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 3)) .' ></td>
					<td align="center"><input type="checkbox" name="r3" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 3)) .' ></td>
					<td align="center"><input type="checkbox" name="f3" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 3)) .' ></td>
				</tr>			
				<tr>
					<td align="center">11:00am</td>
					<td align="center"><input type="checkbox" name="m4" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 4)) .' ></td>
					<td align="center"><input type="checkbox" name="t4" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 4)) .' ></td>
					<td align="center"><input type="checkbox" name="w4" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 4)) .' ></td>
					<td align="center"><input type="checkbox" name="r4" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 4)) .' ></td>
					<td align="center"><input type="checkbox" name="f4" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 4)) .' ></td>
				</tr>			
				<tr>
					<td align="center">11:30am</td>
					<td align="center"><input type="checkbox" name="m5" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 5)) .' ></td>
					<td align="center"><input type="checkbox" name="t5" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 5)) .' ></td>
					<td align="center"><input type="checkbox" name="w5" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 5)) .' ></td>
					<td align="center"><input type="checkbox" name="r5" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 5)) .' ></td>
					<td align="center"><input type="checkbox" name="f5" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 5)) .' ></td>
				</tr>			
				<tr>
					<td align="center">12:00pm</td>
					<td align="center"><input type="checkbox" name="m6" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 6)) . ' ></td>
					<td align="center"><input type="checkbox" name="t6" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 6)) . ' ></td>
					<td align="center"><input type="checkbox" name="w6" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 6)) . ' ></td>
					<td align="center"><input type="checkbox" name="r6" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 6)) . ' ></td>
					<td align="center"><input type="checkbox" name="f6" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 6)) . ' ></td>
				</tr>			
				<tr>
					<td align="center">12:30pm</td>
					<td align="center"><input type="checkbox" name="m7" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 7)) . ' ></td>
					<td align="center"><input type="checkbox" name="t7" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 7)) . ' ></td>
					<td align="center"><input type="checkbox" name="w7" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 7)) . ' ></td>
					<td align="center"><input type="checkbox" name="r7" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 7)) . ' ></td>
					<td align="center"><input type="checkbox" name="f7" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 7)) . ' ></td>
				</tr>			
				<tr>
					<td align="center">1:00pm</td>
					<td align="center"><input type="checkbox" name="m8" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 8)) . ' ></td>
					<td align="center"><input type="checkbox" name="t8" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 8)) . ' ></td>
					<td align="center"><input type="checkbox" name="w8" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 8)) . ' ></td>
					<td align="center"><input type="checkbox" name="r8" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 8)) . ' ></td>
					<td align="center"><input type="checkbox" name="f8" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 8)) . ' ></td>
				</tr>			
				<tr>
					<td align="center">1:30pm</td>
					<td align="center"><input type="checkbox" name="m9" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 9)) . ' ></td>
					<td align="center"><input type="checkbox" name="t9" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 9)) . ' ></td>
					<td align="center"><input type="checkbox" name="w9" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 9)) . ' ></td>
					<td align="center"><input type="checkbox" name="r9" value="1"' . (isChecked(DAYS_OF_WEEK::THURSDAY, 9)) . ' ></td>
					<td align="center"><input type="checkbox" name="f9" value="1"' . (isChecked(DAYS_OF_WEEK::FRIDAY, 9)) . ' ></td>
				</tr>
				<tr>
					<td align="center">2:00pm</td>
					<td align="center"><input type="checkbox" name="m10" value="1"' . (isChecked(DAYS_OF_WEEK::MONDAY, 10)) . ' ></td>
					<td align="center"><input type="checkbox" name="t10" value="1"' . (isChecked(DAYS_OF_WEEK::TUESDAY, 10)) . ' ></td>
					<td align="center"><input type="checkbox" name="w10" value="1"' . (isChecked(DAYS_OF_WEEK::WEDNESDAY, 10)) . ' ></td>
					<td align="center"><input type="checkbox" name="r10" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 10)) . ' ></td>
					<td align="center"><input type="checkbox" name="f10" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 10)) . ' ></td>
				</tr>			
				<tr>
					<td>2:30pm</td>
					<td align="center"><input type="checkbox" name="m11" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 11)) . ' ></td>
					<td align="center"><input type="checkbox" name="t11" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 11)) . ' ></td>
					<td align="center"><input type="checkbox" name="w11" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 11)) . ' ></td>
					<td align="center"><input type="checkbox" name="r11" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 11)) . ' ></td>
					<td align="center"><input type="checkbox" name="f11" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 11)) . ' ></td>
				</tr>	
				<tr>
					<td align="center">3:00pm</td>
					<td align="center"><input type="checkbox" name="m12" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 12)) . ' ></td>
					<td align="center"><input type="checkbox" name="t12" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 12)) . ' ></td>
					<td align="center"><input type="checkbox" name="w12" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 12)) . ' ></td>
					<td align="center"><input type="checkbox" name="r12" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 12)) . ' ></td>
					<td align="center"><input type="checkbox" name="f12" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 12)) . ' ></td>
				</tr>		
				<tr>
					<td align="center">3:30pm</td>
					<td align="center"><input type="checkbox" name="m13" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 13)) . ' ></td>
					<td align="center"><input type="checkbox" name="t13" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 13)) . ' ></td>
					<td align="center"><input type="checkbox" name="w13" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 13)) . ' ></td>
					<td align="center"><input type="checkbox" name="r13" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 13)) . ' ></td>
					<td align="center"><input type="checkbox" name="f13" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 13)) . ' ></td>
				</tr>
				<tr>
					<td align="center">4:00pm</td>
					<td align="center"><input type="checkbox" name="m14" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 14)) . ' ></td>
					<td align="center"><input type="checkbox" name="t14" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 14)) . ' ></td>
					<td align="center"><input type="checkbox" name="w14" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 14)) . ' ></td>
					<td align="center"><input type="checkbox" name="r14" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 14)) . ' ></td>
					<td align="center"><input type="checkbox" name="f14" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 14)) . ' ></td>
				</tr>
				<tr>
					<td align="center">4:30pm</td>
					<td align="center"><input type="checkbox" name="m15" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 15)) . ' ></td>
					<td align="center"><input type="checkbox" name="t15" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 15)) . ' ></td>
					<td align="center"><input type="checkbox" name="w15" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 15)) . ' ></td>
					<td align="center"><input type="checkbox" name="r15" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 15)) . ' ></td>
					<td align="center"><input type="checkbox" name="f15" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 15)) . ' ></td>
				</tr>
				<tr>
					<td align="center">5:00pm</td>
					<td align="center"><input type="checkbox" name="m16" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 16)) . ' ></td>
					<td align="center"><input type="checkbox" name="t16" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 16)) . ' ></td>
					<td align="center"><input type="checkbox" name="w16" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 16)) . ' ></td>
					<td align="center"><input type="checkbox" name="r16" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 16)) . ' ></td>
					<td align="center"><input type="checkbox" name="f16" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 16)) . ' ></td>
				</tr>
				<tr>
					<td align="center">5:30pm</td>
					<td align="center"><input type="checkbox" name="m17" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 17)) . ' ></td>
					<td align="center"><input type="checkbox" name="t17" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 17)) . ' ></td>
					<td align="center"><input type="checkbox" name="w17" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 17)) . ' ></td>
					<td align="center"><input type="checkbox" name="r17" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 17)) . ' ></td>
					<td align="center"><input type="checkbox" name="f17" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 17)) . ' ></td>
				</tr>
				<tr>
					<td align="center">6:00pm</td>
					<td align="center"><input type="checkbox" name="m18" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 18)) . ' ></td>
					<td align="center"><input type="checkbox" name="t18" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 18)) . ' ></td>
					<td align="center"><input type="checkbox" name="w18" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 18)) . ' ></td>
					<td align="center"><input type="checkbox" name="r18" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 18)) . ' ></td>
					<td align="center"><input type="checkbox" name="f18" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 18)) . ' ></td>
				</tr>
				<tr>
					<td align="center">6:30pm</td>
					<td align="center"><input type="checkbox" name="m19" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 19)) . ' ></td>
					<td align="center"><input type="checkbox" name="t19" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 19)) . ' ></td>
					<td align="center"><input type="checkbox" name="w19" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 19)) . ' ></td>
					<td align="center"><input type="checkbox" name="r19" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 19)) . ' ></td>
					<td align="center"><input type="checkbox" name="f19" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 19)) . ' ></td>
				</tr>
				<tr>
					<td align="center">7:00pm</td>
					<td align="center"><input type="checkbox" name="m20" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 20)) . ' ></td>
					<td align="center"><input type="checkbox" name="t20" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 20)) . ' ></td>
					<td align="center"><input type="checkbox" name="w20" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 20)) . ' ></td>
					<td align="center"><input type="checkbox" name="r20" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 20)) . ' ></td>
					<td align="center"><input type="checkbox" name="f20" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 20)) . ' ></td>
				</tr>
				<tr>
					<td align="center">7:30pm</td>
					<td align="center"><input type="checkbox" name="m21" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 21)) . ' ></td>
					<td align="center"><input type="checkbox" name="t21" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 21)) . ' ></td>
					<td align="center"><input type="checkbox" name="w21" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 21)) . ' ></td>
					<td align="center"><input type="checkbox" name="r21" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 21)) . ' ></td>
					<td align="center"><input type="checkbox" name="f21" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 21)) . ' ></td>
				</tr>
				<tr>
					<td align="center">8:00pm</td>
					<td align="center"><input type="checkbox" name="m22" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 22)) . ' ></td>
					<td align="center"><input type="checkbox" name="t22" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 22)) . ' ></td>
					<td align="center"><input type="checkbox" name="w22" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 22)) . ' ></td>
					<td align="center"><input type="checkbox" name="r22" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 22)) . ' ></td>
					<td align="center"><input type="checkbox" name="f22" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 22)) . ' ></td>
				</tr>
				<tr>
					<td align="center">8:30pm</td>
					<td align="center"><input type="checkbox" name="m23" value="1"' .  (isChecked(DAYS_OF_WEEK::MONDAY, 23)) . ' ></td>
					<td align="center"><input type="checkbox" name="t23" value="1"' .  (isChecked(DAYS_OF_WEEK::TUESDAY, 23)) . ' ></td>
					<td align="center"><input type="checkbox" name="w23" value="1"' .  (isChecked(DAYS_OF_WEEK::WEDNESDAY, 23)) . ' ></td>
					<td align="center"><input type="checkbox" name="r23" value="1"' .  (isChecked(DAYS_OF_WEEK::THURSDAY, 23)) . ' ></td>
					<td align="center"><input type="checkbox" name="f23" value="1"' .  (isChecked(DAYS_OF_WEEK::FRIDAY, 23)) . ' ></td>
				</tr>
			</table>
			
			<br />
			
			<input type="submit" name="submit" value="Update Hours" style="height: 25px; width: 100px">
			</form>
		</center>
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
  <li class ="current sub noicon " ><a href="fellow_availability.php">Edit Weekly Availability</a></li>
  <li class ="sub noicon " ><a href="add_schedule_block.php">Add Schedule Block</a></li>
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
