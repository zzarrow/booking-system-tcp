<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Book An Appointment - Fellow Availability</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey);
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	//echo('<br />Access level: ' . $access_level . '<br /><br />');

	$fid = tcpdb::db_sanitize($_GET['f']);
	$query = null;

	if(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern))
		$fid = "self";

	if($fid == "self"){
		//If this page is called with self, but the user is not a fellow/intern/director,
		//cover this case by just resetting the fid to "all"
		if($access_level == ACCESS_LEVELS::student)
			$fid = "all";
		else
			$fid = $tcpdatalink->get_fellow_id_by_pennkey($pennkey);
	}
	if($fid == "all")
		//"all" cannot be called by a fellow or intern, so handle accordingly
		if(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern))
			$fid = $tcpdatalink->get_fellow_id_by_pennkey($pennkey);
	
	if($fid == "all"){
		if($access_level != ACCESS_LEVELS::director)
			$query = "SELECT * from `fellows` WHERE `can_book`=1 ORDER BY `class_year` ASC;"; //Decide how to order results
		else
			$query = "SELECT * from `fellows`;";
	} else {
		//KNOWN BUG: If the user forces a call to the page with fellow_id = a fellow
		//where can_book = 0, it will go through.  Not detrimental; find smooth way
		// to fix later.
		$query = "SELECT * from `fellows` WHERE `can_book`=1 AND `id`='" . $fid . "' ORDER BY `class_year` ASC LIMIT 1";
	}
	
	//Format of output:
	//Date
	//Time	Fellow (major class_year)	Book Now	

	$fellow_arr = $tcpdatalink->create_fellows_from_query($query);
	if(sizeof($fellow_arr) == 0)
		die('Invalid fellow ID. <a href="javascript:history.back(-1)">Back</a>');
	
	//echo('Query used: ' . $query . '<br />Size of fellow_arr: ' . sizeof($fellow_arr) . '<br />');
	
	for($i = 0; $i < sizeof($fellow_arr); $i++){
		$fellow_arr[$i]->set_appointments($tcpdatalink->get_appointments_for_fellow($fellow_arr[$i]->get_id()));
		$fellow_arr[$i]->set_block_list($tcpdatalink->get_block_list_for_fellow($fellow_arr[$i]->get_id()));
	}
	
	function get_date_from_index($date_index, $p, $per_page){
		//$day_offset = $date_index + ($p * $per_page);
		//return (string)(date('Ymd', strtotime('+' . $day_offset . ' days')));
		return (string)(date('Ymd', strtotime('+' . $date_index . ' days')));
	}

?>














<!DOCCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"> 
 
<head> 
 
 
 
 
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
 
<title>Penn Engineering > TCP >
 
Fellow Availability
 
</title> 
 
<meta name="keywords" content="penn engineering, technical communication program, technical communcation, tcp, technical writing"> 
 
<meta name="description" content="The Technical Communication Program (TCP) in the School of Engineering is designed to improve undergraduate students. proficiency in the skills of communication and information literacy by supporting these skills in SEAS undergraduate classes. The goal is for every SEAS undergraduate to participate in at least three Engineering courses (including the Senior Design Project) in which development of communication and library research skills is a focus"> 
 
<link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/screen.css" type="text/css" media="screen, projection"> 
 
<link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/print.css" type="text/css" media="print"> 
 
<!--[if IE]>
 
  <link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/ie.css" type="text/css" media="screen, projection">
 
<![endif]--> 
 
<link rel="stylesheet" href="http://www.seas.upenn.edu/~tcp/css/style.css" type="text/css" media="screen, projection"> 

<script type="text/javascript">
    function changeColor(tableRow, highLight){
	    if (highLight){
		tableRow.style.backgroundColor = '#dcfac9';
	    	document.body.style.cursor = 'hand';
	    }
    	    else{
	        tableRow.style.backgroundColor = '#FFFFFF';
    	   	document.body.style.cursor = 'default';
	    }
    }

    function nav(theUrl){
            document.location.href = theUrl;
    }
</script>

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

<?
	$fellow_name = "";
	if($_GET['f'] == "self")
		$fellow_name = "Your Availability";
	else if($_GET['f'] == "all")
		$fellow_name = "All Fellows";
	else {
		$fellow = $tcpdatalink->get_fellow_by_id(tcpdb::db_sanitize($_GET['f']));	
		$fellow_name = $fellow->get_firstname() . ' ' . $fellow->get_lastname();
	}
	

	$page_num = 0;
	if(isset($_GET['p']))
		$page_num = (int)$_GET['p'];
?>
<h2 align="left">Book an Appointment &raquo; Fellow Availability &raquo; <? echo($fellow_name); ?></h2> 
 
<p align="left" style="margin-bottom: 0px;">
Available appointment timeslots are listed below. Click on a time to book an appointment in that slot.
</p>

<p align="left">
<style>.tbl_nav_hdr{
	border: none;
	border-top: none;
	border-left: none;
	vertical-align:middle;
	margin-bottom:0px;
}</style>

<table class="tbl_nav_hdr" style="width: 130%; margin-bottom: 0px; padding-bottom: 0px">
<tr>
	<td class="tbl_nav_hdr" align="left" width="33%">
		<table class="tbl_nav_hdr">
			<tr>
				<td class="tbl_nav_hdr" style="padding-right: 0px">
					<?
					if($page_num != 0)
						echo('<a href="book_avail.php?f="' . $fid . '&p=' . (int)($page_num - 1) . '>');
					?>
				<? if($page_num != 0)									echo('<img src="images/booking_system/nav_left.png" width="60%" height="60%">
					</a>');
				?>
				</td>
				<td class="tbl_nav_hdr" align="left" style="padding-left: 0px;">
				<?
				if($page_num != 0)
					echo('<a href="book_avail.php?f=' . $fid . '&p=' . (int)($page_num - 1) . '">
						Previous Page
					</a>');
				?>
				</td>
			</tr>
		</table>
	</td>
	<td class="tbl_nav_hdr" width="33%">
		<p align="center" style="vertical-align: middle; margin-bottom: 0px">
			<?
				if($page_num != 0)
					echo('<a href="book_avail.php?f=' . $fid . '">
						Back to Today
						</a>');

				if(!(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern))){
					if($page_num != 0)
						echo('
							&nbsp; | &nbsp;
						');	
					echo('<a href="book.php">Select Another Fellow</a>');
				}
			?>
		</p>
	</td>
	<td class="tbl_nav_hdr" align="right" width="33%">
		<table class="tbl_nav_hdr">
			<tr>
				<td class="tbl_nav_hdr" align="right">
					<?
						echo('<a href="book_avail.php?f=' . $fid . '&p=' . (int)($page_num + 1) . '">
							Next Page
							</a>');
					?>
				</td>
				<td class="tbl_nav_hdr" align="right">
					<a href=""><img src="images/booking_system/nav_right.png" width="60%" height="60%"></a>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>


<table style="width: 130%" height="85%" style="margin-bottom: 0px; padding-top: 0px; margin-top: 0px">
<tr height="21%">
<?
	
        function confirm_availability($apt_date, $apt_time, $fid){
                //These setter calls should be moved inside the constructor of fellow... oh well
                $fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id($fid);
                $fellow->set_appointments($GLOBALS['tcpdatalink']->get_appointments_for_fellow($fellow->get_id()));
                $fellow->set_block_list($GLOBALS['tcpdatalink']->get_block_list_for_fellow($fellow->get_id()));

                $apts = $fellow->get_appointments();
                //for($i = 0; $i < sizeof($apts); $i++){
                //      echo('<br />Appointment: ' . $apts[$i]->get_apt_date() . ' ' . $apts[$i]->get_time_index() . ' <br />');
                //}

                //echo('Availability: ' . $this->get_availability_at_time_index($_date->get_day_of_week(), $time_index) . '<br />');
                //echo('Has apt at time: ' . $fellow -> has_appointment_at_time($apt_date, $apt_time) . '<br />');
                //echo('Has block at time: ' . $fellow -> has_block_at_time($apt_date, $apt_time) . '<br />');


                //echo('Calling is_available with date: ' . tcp_date::get_tcpdate_from_dbdate($apt_date)->to_string() . ' and time index ' . $apt_time);
                return $fellow->is_available(tcp_date::get_tcpdate_from_dbdate($apt_date), $apt_time);
        }


	
	
	$slot_arr = array();

	$dates_disp = 0;

	$GLOBALS['APPOINTMENTS_PER_PAGE'] = 15;

	$temp_max = $GLOBALS['APPOINTMENTS_PER_PAGE'];
	
	//Results per page / Pagination
	//KNOWN BUG: Results per page is inconsistent because of weekends - Tough to fix without restructuring
	//           Date continuity between pages is consistent, though, so this works for now.
	for($i = $page_num * $GLOBALS['APPOINTMENTS_PER_PAGE']; $i < (($page_num + 1) * $GLOBALS['APPOINTMENTS_PER_PAGE']); $i++){//date loop
	//for($i = $page_num * $temp_max; $i < (($page_num + 1) * $temp_max); $i++){	

		$slot_date = get_date_from_index($i, $page_num, $GLOBALS['APPOINTMENTS_PER_PAGE']);
		$slot_tcp_date = new tcp_date($slot_date{0} . $slot_date{1} . $slot_date{2} . $slot_date{3}, $slot_date{4} . $slot_date{5}, $slot_date{6} . $slot_date{7});
		$dow = tcp_date::determine_day_of_week($slot_tcp_date->get_year(), $slot_tcp_date->get_month(), $slot_tcp_date->get_day());
		//Don't include weekends
		if(($dow === "Saturday"
			|| $dow === "Sunday")){ //"Monday" is mapped to 0 which creates a bug.  Using === to compare
		
		//	$i--;
			//$temp_max++;
			continue;	
		}
		   //both value AND type.  For more information, see
								   //http://www.hashbangcode.com/blog/string-equals-zero-php-240.html
	
		if($dates_disp == 3){
			echo('</tr><tr height="21%">');
			$dates_disp = 0;
		}
		
		echo('<td style="width: 33%; text-align: center; vertical-align: top">');
		
		//echo('<td>');
		echo('<p align="left"><b>' . tcp_date::raw_to_readable($slot_date) . '</b></p>');
		//echo(tcp_date::raw_to_readable($slot_date));

		$dates_disp++;
		echo('<table>');
		$got_date = false;
		$times_disp = -1; //Weird bug causing an offset somewhere... this is a workaround
		for($j = 0; $j < 24; $j++){//time index loop
			$got_time = false;
			for($k = 0; $k < sizeof($fellow_arr); $k++){//fellow loop
		/**		$fellow = $fellow_arr[$k];
				//echo('Calling is_available with date: ' . $slot_tcp_date->to_string() . " and time index ' . $j  . '<br />");
				$fellow->set_appointments($GLOBALS['tcpdatalink']->get_appointments_for_fellow($fellow->get_id()));
				$fellow->set_block_list($GLOBALS['tcpdatalink']->get_block_list_for_fellow($fellow->get_id()));				
**/
				//if($fellow->is_available($slot_tcp_date, $j)){
				if(confirm_availability($slot_date, $j, $fellow_arr[$k]->get_id())){
					$slot_arr[$slot_date . $j][] = $fellow_arr[$k];
					//echo('Setting $slot_arr[' . $slot_date . $j . '][] = $fellow_arr[' . $k . ']');
					$got_date = true;
					$got_time = true;

					break; //Here, we only care if we find one
				}
			}	
			if(!$got_time){
				//echo('&nbsp; <font color="gray">' . tcp_date::index_to_str($j) . '</font> &nbsp;');
			} else{
				//echo('<table>');
				//echo('times disp: ' . $times_disp);

				$times_disp++;				

				if($times_disp == 4){
					echo('</tr><tr>');
					$times_disp = 0;
				}
				for($f = 0; $f < sizeof($slot_arr[$slot_date . $j]); $f++){
					$f_parm_string = "";
					if($fid != "all")
						$f_parm_string="&f=" . $slot_arr[$slot_date . $j][0]->get_id();
					echo('<td style="vertical-align: top; text-align:center" onMouseOver="changeColor(this, true);" onMouseOut="changeColor(this, false);" onClick="nav(\'book_information.php?d=' . get_date_from_index($i, $page_num, $GLOBALS['APPOINTMENTS_PER_PAGE']) . '&t=' . $j . $f_parm_string . '\');"><font color="#0000DD">' . tcp_date::index_to_str($j) . '</font></td>');						
					//echo('<td style="vertical-align: middle">' . $slot_arr[$slot_date . $j][$f]->get_firstname() . ' ' . $slot_arr[$slot_date . $j][$f]->get_lastname() . '<br /><span style="color: gray">(' . $slot_arr[$slot_date . $j][$f]->get_major() . ', ' . $slot_arr[$slot_date . $j][$f]->get_class_year() . ')</span></td>');
					//echo('<td style="vertical-align: middle; text-align: center"><a href="book_information.php?d=' . get_date_from_index($i, $page_num, $GLOBALS['APPOINTMENTS_PER_PAGE']) . '&t=' . $j . '&f=' . $slot_arr[$slot_date . $j][0]->get_id() . '">Book<br />Now</a></td>');
					//echo('</tr>');
				}
				//echo('</table>');
			}
		}
		if(!$got_date)
			echo('<center><font color="gray">No appointments available.</font></center></td>');
			
		echo('</tr></table></td>');
		//echo('<br />');
	}

	echo('</tr></table>');
	
/**
	if($page_num != 0)
		echo('<a href="book_avail.php?f=' . $fid . '&p=' . ((int)$page_num - 1) . '">Prev</a>&nbsp;');
	echo('<a href="book_avail.php?f=' . $fid . '&p=' . ((int)$page_num + 1) . '">Next</a>');
**/

?>



<table class="tbl_nav_hdr" style="width: 130%; margin-bottom: 0px; padding-bottom: 0px">
<tr>
        <td class="tbl_nav_hdr" align="left" width="33%">
                <table class="tbl_nav_hdr">
                        <tr>
                                <td class="tbl_nav_hdr" style="padding-right: 0px">
                                        <?
                                        if($page_num != 0)
                                                echo('<a href="book_avail.php?f="' . $fid . '&p=' . (int)($page_num - 1) . '>');
                                        ?>
                                <? if($page_num != 0)                                                                   echo('<img src="images/booking_system/nav_left.png" width="60%" height="60%">
                                        </a>');
                                ?>
                                </td>
                                <td class="tbl_nav_hdr" align="left" style="padding-left: 0px;">
                                <?
                                if($page_num != 0)
                                        echo('<a href="book_avail.php?f=' . $fid . '&p=' . (int)($page_num - 1) . '">
                                                Previous Page
                                        </a>');
                                ?>
                                </td>
                        </tr>
                </table>
        </td>
        <td class="tbl_nav_hdr" width="33%">
                <p align="center" style="vertical-align: middle; margin-bottom: 0px">
                        <?
                                if($page_num != 0)
                                        echo('<a href="book_avail.php?f=' . $fid . '">
                                                Back to Today
                                                </a>');
	
				if(!(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern))){
					if($page_num != 0)
						echo('
							&nbsp; | &nbsp;
						');	
					echo('<a href="book.php">Select Another Fellow</a>');
				}
			?>
                </p>
        </td>
        <td class="tbl_nav_hdr" align="right" width="33%">
                <table class="tbl_nav_hdr">
                        <tr>
                                <td class="tbl_nav_hdr" align="right">
                                        <?
                                                echo('<a href="book_avail.php?f=' . $fid . '&p=' . (int)($page_num + 1) . '">
                                                        Next Page
                                                        </a>');
                                        ?>
                                </td>
                                <td class="tbl_nav_hdr" align="right">
                                        <a href=""><img src="images/booking_system/nav_right.png" width="60%" height="60%"></a>
                                </td>
                        </tr>
                </table>
        </td>
</tr>
</table>





	

</p>

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

</div> 
 
</div> 
 
 
 
<!--

<div id="rightcolumn"> 
 
<div class="innertube"> 
 
  <div align="left"><img src="http://www.seas.upenn.edu/~tcp/images/towne-front.jpg" alt="Towne" /> </div> 
 
</div> 
 
</div> 

-->
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
