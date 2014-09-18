<html>
<body>
<center><h2><b>Add Appointment To Database</b></h2></center><hr>

<?

	if(isset($_POST['submit'])){
		$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****') or die('Could not connect to database: ' . mysql_error());
		mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
		
		//Obvious SQL injections possible here...
		//...don't actually use this in production.
		
		mysql_query("
			INSERT INTO appointments (
				`id`, `fellow_id`, `pennkey`, `firstname`, `lastname`, `email`, `apt_date`, `comment`, `filename`, `upload_date`, `time_index`, `feedback_left`
			) VALUES (
				'',
				'" . $_POST['fellow_id'] . "',
				'" . $_POST['pennkey'] . "',
				'" . $_POST['firstname'] . "',
				'" . $_POST['lastname'] . "',
				'" . $_POST['email'] . "',
				'" . $_POST['apt_date'] . "',
				'" . $_POST['comment'] . "',
				'" . $_POST['filename'] . "',
				'" . $_POST['upload_date'] . "',
				'" . $_POST['time_index'] . "',
				'" . $_POST['feedback_left'] . "' 
			)	
		") or die('Error running query: ' . mysql_error());
		
		echo('<font color="#008800"><b>Appointment added to database.</b></font><hr>');
	}

?>


<html>
<body>
	<form method="POST" action = "#">
	<table border = 1>
		<tr><td>fellow_id</td><td><input type="text" name="fellow_id"></td></tr>
		<tr><td>pennkey</td><td><input type="text" name="pennkey"></td></tr>
		<tr><td>firstname</td><td><input type="text" name="firstname"></td></tr>
		<tr><td>lastname</td><td><input type="text" name="lastname"></td></tr>
		<tr><td>email</td><td><input type="text" name="email"></td></tr>
		<tr><td>apt_date</td><td><input type="text" name="apt_date"></td></tr>
		<tr><td>comment</td><td><input type="text" name="comment"></td></tr>
		<tr><td>filename</td><td><input type="text" name="filename"></td></tr>
		<tr><td>upload_date</td><td><input type="text" name="upload_date"></td></tr>
		<tr><td>time_index</td><td><input type="text" name="time_index"></td></tr>
		<tr><td>feedback_left</td><td><input type="text" name="feedback_left"></td></tr>
	</table>
	<input type="submit" name="submit" value="Add Appointment">
	</form>
</body>
</html>