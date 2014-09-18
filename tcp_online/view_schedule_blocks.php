<?
	include('tcp_common.php');
	
	ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::fellow);
	
	$tcpdatalink = new tcpdb();
//	echo('<html><body>');
//	echo('<h2>Schedule Blocks</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	$fid = $tcpdatalink->get_fellow_id_by_pennkey($pennkey);

?>









<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
TCP Online > View Schedule Blocks
 
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

<h2 align="left">Your Schedule Blocks</h2> 
<?
if(!isset($_POST['submit'])){
echo('<p align="left">
	All of the Schedule Blocks that you have added are listed below.  If you would like to remove any Schedule Blocks, check the box next to each Block you would like to remove.  When you are finished, press the button at the bottom to remove the Schedule Blocks.

</p>');
}
?>

<p align="center">
<center>
	

<?	
	if(isset($_POST['submit'])){
		//We are passed in : b{n} = {id} forall selected id's, where n = seq num
		//Can break from loop when b{n} is undefined
		
		$ctr = 0;
		foreach($_POST as $key => $val){
			if($key == "submit")
				continue;
			
			if($tcpdatalink->can_fellow_remove_block($fid, tcpdb::db_sanitize($val))){
				$tcpdatalink->remove_schedule_block(tcpdb::db_sanitize($val));
				$ctr++;
			} else {
				echo('<br /><b>Note:</b> You do not have permission to remove one or more selected blocks.<br />');
			}
		}
		
		echo('<p><strong>' . $ctr . '</strong> schedule blocks successfully removed.</p><p>
			<a href="view_schedule_blocks.php">View / Remove Schedule Blocks</a>&nbsp; | &nbsp; <a href="add_schedule_block.php">Add Schedule Block</a>&nbsp; | &nbsp; <a href="index.php">Fellow Control Panel Home</a>
			</p>
			');
	} else {
	
		echo('
			<form method="post" action="#">
			<table border="1">
				<tr>
					<td><b>Date</b></td>
					<td><b>Time</b></td>
					<td></td>
				</tr>		
		');
	
		$block_arr = $tcpdatalink->get_block_list_for_fellow($fid);
		for($i = 0; $i < sizeof($block_arr); $i++){
			echo('
				<tr>
					<td>' . tcp_date::raw_to_readable($block_arr[$i]->get_block_date()) . '</td>
					<td>' . tcp_date::index_to_str($block_arr[$i]->get_time_index()) . '</td>
					<td><input type="checkbox" name="b' . $i . '" value="' . $block_arr[$i]->get_id() . '"></td>
				</tr>
			');
		}
		
		echo('<tr><td colspan="3" align="center"><a href="add_schedule_block.php">Add Schedule Block</a></td></tr>');
		echo('</table>');
		
		echo('<input type="submit" name="submit" value="Remove Selected Blocks" style="height: 50px; width: 200px">');
		echo('</form>');
	}
	
//	echo('</body></html>');
?>














</center>
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
  <li class ="sub noicon " ><a href="add_schedule_block.php">Add Schedule Block</a></li>
  <li class ="current sub noicon " ><a href="view_schedule_blocks.php">View/Remove Schedule Blocks</a></li>
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
