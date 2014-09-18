<?

	if(isset($_GET['m']))
		$mode = $_GET['m'];
	if(!isset($_GET['m']) || (($mode != "f") && ($mode != "a") && ($mode != "s")))
		$mode = "db";

	$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****') or die('Could not connect to database: ' . mysql_error());
	mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
		
	echo("<html><body><center><h2><b>TCP Database</b></h2></center><hr>");
			
	if(($mode == "f") || ($mode == "db")){
		echo("<h3><b>Table: Fellows</b></h3>");
		echo("<table border = 1><tr><td><b>id</b></td><td><b>access_level</b></td><td><b>pennkey</b></td><td><b>firstname</b></td><td><b>lastname</b></td><td><b>email</b></td><td><b>is_active</b></td><td><b>can_book</b></td><td><b>major</b></td><td><b>class_year</b></td><td><b>availability</b></td></tr>");

		$q1 = mysql_query("SELECT * from `fellows`") or die('Could not retrieve fellows table: ' . mysql_error());
		while($fetch = mysql_fetch_object($q1))
			echo("<tr><td>$fetch->id</td><td>$fetch->access_level</td><td>$fetch->pennkey</td><td>$fetch->firstname</td><td>$fetch->lastname</td><td>$fetch->email</td><td>$fetch->is_active</td><td>$fetch->can_book</td><td>$fetch->major</td><td>$fetch->class_year</td><td>$fetch->availability</td></tr>");
	
		echo("</table>");
	}
	
	if(($mode == "a") || ($mode == "db")){
		echo("<h3><b>Table: Appointments</b></h3>");
		echo("<table border = 1><tr><td><b>id</b></td><td><b>fellow_id</b></td><td><b>pennkey</b></td><td><b>firstname</b></td><td><b>lastname</b></td><td><b>email</b></td><td><b>apt_date</b></td><td><b>comment</b></td><td><b>filename</b></td><td><b>upload_date</b></td><td><b>time_index</b></td><td><b>feedback_left</b></td></tr>");

		$q2 = mysql_query("SELECT * from `appointments`") or die('Could not retrieve appointments table: ' . mysql_error());
		while($fetch = mysql_fetch_object($q2))
			echo("<tr><td>$fetch->id</td><td>$fetch->fellow_id</td><td>$fetch->pennkey</td><td>$fetch->firstname</td><td>$fetch->lastname</td><td>$fetch->email</td><td>$fetch->apt_date</td><td>$fetch->comment</td><td>$fetch->filename</td><td>$fetch->upload_date</td><td>$fetch->time_index</td><td>$fetch->feedback_left</td></tr>");
	
		echo("</table>");
   	}
   	
   	if(($mode == "s") || ($mode == "db")){
   		echo("<h3><b>Table: Schedule Blocks</b></h3>");
		echo("<table border = 1><tr><td><b>id</b></td><td><b>fellow_id</b></td><td><b>block_date</b></td><td><b>time_index</b></td></tr>");

		$q3 = mysql_query("SELECT * from `schedule_blocks`") or die('Could not retrieve schedule_blocks table: ' . mysql_error());
		while($fetch = mysql_fetch_object($q3))
			echo("<tr><td>$fetch->id</td><td>$fetch->fellow_id</td><td>$fetch->block_date</td><td>$fetch->time_index</td></tr>");
	
		echo("</table>");
	}
	
	echo("<hr></body></html>");
?>