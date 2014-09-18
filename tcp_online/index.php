<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>Logged In Landing Page</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	
	//echo('Logged in as: ' . $pennkey);
	
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	//echo('<br />Access level: ' . $access_level);
	
	if($access_level == ACCESS_LEVELS::director)
		include('director_landing_page.php');
	else if(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern))
		include('fellow_landing_page.php');
	else
		include('student_landing_page.php');
	
	
	//tcpdb->book_appointment is written
?>
