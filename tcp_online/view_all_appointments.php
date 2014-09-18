<?
	include('tcp_common.php');
	
	$tcpdatalink = new tcpdb();
	//echo('<html><body>');
	//echo('<h2>All Appointments</h2>');
	
	$pennkey = $_SERVER['REMOTE_USER'];
	$access_level = $tcpdatalink->get_access_level($pennkey);
	
	if($access_level == ACCESS_LEVELS::director)
		include('appointments_director.php');
	else if(($access_level == ACCESS_LEVELS::fellow) || ($access_level == ACCESS_LEVELS::intern))
		include('appointments_fellow.php');
	else
		include('appointments_student.php');
		
?>
