<?
	include('tcp_common.php');
	
	ACCESS_LEVELS::AUTHENTICATE(ACCESS_LEVELS::director);
	$tcpdatalink = new tcpdb();
	$pennkey = $_SERVER['REMOTE_USER'];
	
	echo('<html><body>');
	echo('<h2>Modify Fellow</h2>');
	
	$fellow = $tcpdatalink->get_fellow_by_id($_GET['fid']);
	
	if(isset($_POST['submit'])){
		$tcpdatalink->update_fellow_metadata(
			$fellow->get_id(),
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
		
		echo('Information updated.');
	} else {
		//Render and populate form
		echo('
			
				<form method="POST" action="">
					Position:<br /> 
					<input type="radio" name="access_level" value="2" ' . (($fellow->get_access_level() == ACCESS_LEVELS::intern) ? 'checked' : '') . '>&nbsp; Intern<br />
					<input type="radio" name="access_level" value="1" ' . (($fellow->get_access_level() == ACCESS_LEVELS::fellow) ? 'checked' : '') . '>&nbsp; Fellow<br />
					<input type="radio" name="access_level" value="0" ' . (($fellow->get_access_level() == ACCESS_LEVELS::director) ? 'checked' : '') . '>&nbsp; Director<br />
					<br />
					First name: &nbsp; <input type="text" name="firstname" value="' . $fellow->get_firstname() . '"><br />
					Last name: &nbsp; <input type="text" name="lastname" value="' . $fellow->get_lastname() . '"><br />
					E-mail Adderess: &nbsp; <input type="text" name="email" value="' . $fellow->get_email() . '"><br />
					Pennkey: &nbsp; <input type="text" name="pennkey" value="' . $fellow->get_pennkey() . '"><br />
					Major: &nbsp; <input type="text" name="major" value="' . $fellow->get_major() . '"><br />
					Class year: &nbsp; <input type="text" name="class_year" value="' . $fellow->get_class_year() . '"><br />
					Allowed to book appointments?<br />
					<input type="radio" name="can_book" value="1" ' . (($fellow->can_book() == "1") ? 'checked' : '') . '>Yes<br />
					<input type="radio" name="can_book" value="0" ' . (($fellow->can_book() == "0") ? 'checked' : '') . '>No<br />
					Is fellow active?<br />
					<input type="radio" name="active" value="1" ' . (($fellow->is_active() == "1") ? 'checked' : '') . '>Yes<br />
					<input type="radio" name="active" value="0" ' . (($fellow->is_active() == "0") ? 'checked' : '') . '>No<br />					
					<input type="submit" name="submit" value="Update">
				</form>
			');
	}
?>