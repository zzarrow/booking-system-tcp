<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	echo('<html><body>');
	echo('<h2>Appointment Details</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	echo('Logged in as: ' . $pennkey);
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	function can_view_apt($_pennkey, $_appointment){
		if($GLOBALS['access_level'] == ACCESS_LEVELS::director)
			return true; //Director can view everything
		
		if(($GLOBALS['access_level'] == ACCESS_LEVELS::fellow) ||
			($GLOBALS['access_level'] == ACCESS_LEVELS::intern)){

			//fid on appointment has to match user's fid
			
			return ($_appointment->get_fellow_id()
					== $GLOBALS['tcpdatalink']->get_fellow_id_by_pennkey($GLOBALS['pennkey']));	
		}
		
		if($GLOBALS['access_level'] == ACCESS_LEVELS::student){
			//appointment's student pennkey has to match user's pennkey
			return ($_appointment->get_pennkey() == $GLOBALS['pennkey']);
		}
	}
	
	if(!isset($_GET['id']))
		die('Invalid appointment ID.');
	$aid = (int)$_GET['id'];
	
	$appointment = $GLOBALS['tcpdatalink']->get_apt_by_id($aid);
	if($appointment == null)
		die('Invalid appointment ID.');
	
	if(!can_view_apt($pennkey, $appointment))
		die('You are unauthorized to view this appointment. <br /><a href="javascript:history.go(-1)">Back</a>');
	
	$fellow = $tcpdatalink->get_fellow_by_id($appointment->get_fellow_id());
	
	echo('<br /><ul><li>Appointment ID: ' . $appointment->get_id() . '</li>');
	echo('<li>Student: <a href="mailto:' . $appointment->get_email() . '">' . $appointment->get_first_name() . ' ' . $appointment->get_last_name() . '</a> (' . $appointment->get_pennkey() . ')</li>');
	//echo('<li>Fellow: <a href="mailto:' . $fellow->get_email() . '">' . $fellow->get_first_name() . ' ' . $fellow->get_last_name() . '</a>(' . $fellow->get_major() . ' ' . $fellow->get_class_year() . ')</li>');
	echo('<li>Fellow: ' . $fellow->get_firstname() . ' ' . $fellow->get_lastname() . ' (' . $fellow->get_major() . ' ' . $fellow->get_class_year() . ')</li>');
	echo('<li>Date: ' . tcp_date::raw_to_readable($appointment->get_apt_date()) . '</li>');
	echo('<li>Time: ' . tcp_date::index_to_str($appointment->get_time_index()) . '</li>');
	echo('<li>Comment: ' . $appointment->get_comment() . '</li>');
	echo('<li>Feedback left: ' . $appointment->get_feedback_left() . '</li>');
	echo('</ul>');

	echo('<br /><a href="cancel_appointment.php?id=' . $appointment->get_id() . '">Cancel Appointment</a>');
	
?>