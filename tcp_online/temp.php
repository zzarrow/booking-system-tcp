<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Cancel Appointment</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey . '<br />');
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	function send_email_to_student($_apt){
		$subj = "TCP Appointment Canceled (" . tcp_date::raw_to_readable($_apt->get_apt_date()) . " at " . tcp_date::index_to_str($_apt->get_time_index()) . ")"; 
		$msg = "Dear " . $_apt->get_first_name() . ",<br /><br />
		
		Your TCP appointment originally scheduled for " . tcp_date::raw_to_readable($_apt->get_apt_date()) . " at " . tcp_date::index_to_str($_apt->get_time_index()) . "  <b>has been canceled</b>.<br /><br />
		
		To book another appointment, please visit the <a href=\"http://www.seas.upenn.edu/~tcp\">TCP Online Booking System</a>.  If you have any questions, please contact " . $GLOBALS['tcpdatalink']->get_contact_name() . " at <a href=\"mailto:" . $GLOBALS['tcpdatalink']->get_contact_email() . "\">" . $GLOBALS['tcpdatalink']->get_contact_email() . "</a>.<br /><br />		
		
		Thank you,<br />
		- Technical Communication Program<br /><br />
		
		<b>Note:</b> This e-mail address is not monitored, so <b>please do not reply directly to this e-mail</b>.
		
		";
		
		//echo('<br />Sending e-mail to ' . $_addr . '<br />Subject: ' . $subj . '<br />Message:<br />' . $msg);
		
		//USE THIS IN PRODUCTION: $email = $_apt->get_email();
		//FOR DEBUG:
		$email = "zzarrow@gmail.com";
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: ' . $_apt->get_first_name() . ' ' . $_apt->get_last_name() . '<' . $email . '>' . "\r\n";
		$headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";
		
		mail("",
			$subj,
			$msg,
			$headers);
		
		return;
	}
	
	function send_email_to_fellow($_apt){
		$fellow = $GLOBALS['tcpdatalink']->get_fellow_by_id($_apt->get_fellow_id());
		
