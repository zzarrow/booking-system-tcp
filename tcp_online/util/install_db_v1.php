<?
	$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****');
	mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
	
	$query = "
		CREATE TABLE fellows (
         id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
         access_level INT NOT NULL,
         pennkey varchar(100) NOT NULL,
         firstname varchar(64) NOT NULL,
         lastname varchar(64) NOT NULL,
         can_book INT NOT NULL,
         major varchar(64) NOT NULL,
         class_year INT NOT NULL,
         availability varchar(200) NOT NULL
       );";
       
    $query2 = "  
       CREATE TABLE appointments (
       	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
       	fellow_id INT NOT NULL,
       	pennkey varchar(64) NOT NULL,
       	firstname varchar(64) NOT NULL,
       	lastname varchar(64) NOT NULL,
       	email varchar(64) NOT NULL,
       	apt_date varchar(16) NOT NULL,
       	comment LONGTEXT,
       	filename varchar(64),
       	upload_date varchar(16),
       	time_index INT not null,
       	feedback_left INT
       );";
       
     $query3 = "
       CREATE TABLE schedule_blocks (
       	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
       	fellow_id INT NOT NULL,
       	block_date varchar(16) NOT NULL,
       	time_index INT NOT NULL       
       );";
	
	$go = mysql_query($query) or die("Could not run query 1: " . mysql_error());
	$go2 = mysql_query($query2) or die("Could not run query 2: " . mysql_error());
	$go3 = mysql_query($query3) or die("Could not run query 3: " . mysql_error());
	
	echo("Install script executed.");
?>