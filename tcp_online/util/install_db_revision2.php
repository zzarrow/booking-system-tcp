<?
	$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****');
	mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
	
	$query = "
		ALTER TABLE fellows
			ADD `is_active` smallint(2);
	";
	
	$go = mysql_query($query) or die("Could not run query 1: " . mysql_error());
	
	echo("Install script executed.");
?>