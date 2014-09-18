<html>
<body>
<center><h2><b>Add Fellow To Database</b></h2></center><hr>

<?

	if(isset($_POST['submit'])){
		$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****') or die('Could not connect to database: ' . mysql_error());
		mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
		
		//Obvious SQL injections possible here...
		//...don't actually use this in production.
		
		mysql_query("
			INSERT INTO fellows (
				`id`, `access_level`, `pennkey`, `firstname`, `lastname`, `email`, `is_active`, `can_book`, `major`, `class_year`, `availability`	
			) VALUES (
				'',
				'" . $_POST['access_level'] . "',
				'" . $_POST['pennkey'] . "',
				'" . $_POST['firstname'] . "',
				'" . $_POST['lastname'] . "',
				'" . $_POST['email'] . "',
				'" . $_POST['active'] . "',
				'" . $_POST['can_book'] . "',
				'" . $_POST['major'] . "',
				'" . $_POST['class_year'] . "',
				'" . $_POST['availability'] . "' 
			)
		
		") or die('Error running query: ' . mysql_error());
		
		echo('<font color="#008800"><b>Fellow added to database.</b></font><hr>');
	}

?>


<html>
<body>
	<form method="POST" action = "#">
	<table border = 1>
		<tr><td>access_level</td><td><input type="text" name="access_level"></td></tr>
		<tr><td>pennkey</td><td><input type="text" name="pennkey"></td></tr>
		<tr><td>firstname</td><td><input type="text" name="firstname"></td></tr>
		<tr><td>lastname</td><td><input type="text" name="lastname"></td></tr>
		<tr><td>email</td><td><input type="text" name="email"></td></tr>
		<tr><td>is_active</td><td><input type="text" name="active"></td></tr>
		<tr><td>can_book</td><td><input type="text" name="can_book"></td></tr>
		<tr><td>major</td><td><input type="text" name="major"></td></tr>
		<tr><td>class_year</td><td><input type="text" name="class_year"></td></tr>
		<tr><td>availability</td><td><input type="text" name="availability"></td></tr>
	</table>
	<input type="submit" name="submit" value="Add Fellow">
	</form>
</body>
</html>