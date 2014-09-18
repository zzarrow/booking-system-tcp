<html>
<body>
<center><h2><b>Add Schedule Block To Database</b></h2></center><hr>

<?

	if(isset($_POST['submit'])){
		$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****') or die('Could not connect to database: ' . mysql_error());
		mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
		
		//Obvious SQL injections possible here...
		//...don't actually use this in production.
		
		mysql_query("
			INSERT INTO schedule_blocks (
				`id`, `fellow_id`, `block_date`, `time_index`
			) VALUES (
				'',
				'" . $_POST['fellow_id'] . "',
				'" . $_POST['block_date'] . "',
				'" . $_POST['time_index'] . "'
			)	
		") or die('Error running query: ' . mysql_error());
		
		echo('<font color="#008800"><b>Schedule block added to database.</b></font><hr>');
	}

?>


<html>
<body>
	<form method="POST" action = "#">
	<table border = 1>
		<tr><td>fellow_id</td><td><input type="text" name="fellow_id"></td></tr>
		<tr><td>block_date</td><td><input type="text" name="block_date"></td></tr>
		<tr><td>time_index</td><td><input type="text" name="time_index"></td></tr>
	</table>
	<input type="submit" name="submit" value="Add Schedule block">
	</form>
</body>
</html>