<?
	$conn = mysql_connect('localhost', '*****REDACTED FOR GITHUB*****', '*****REDACTED FOR GITHUB*****');
	mysql_select_db('*****REDACTED FOR GITHUB*****', $conn);
	
	$query = "
		CREATE TABLE rdb_metainfo (
         `key` varchar(64) PRIMARY KEY,
         `val` varchar(64)
       );";
       
	
	$query4 = "
    	INSERT INTO rdb_metainfo (
    		`key`, `val`
    	) VALUES (
    		'sysadmin_name',
    		'Zach Zarrow'		
    	)
    ";

	$query5 = "
    	INSERT INTO rdb_metainfo (
    		`key`, `val`
    	) VALUES (
    		'sysadmin_email',
    		'zzarrow@seas.upenn.edu'		
    	)
    ";
    
    $query6 = "
    	INSERT INTO rdb_metainfo (
    		`key`, `val`
    	) VALUES (
    		'system_url',
    		'http://fling.seas.upenn.edu/~tcp/cgi-bin/booking_system'		
    	)
    ";
    
	$go4 = mysql_query($query4) or die("Could not run query 4: " . mysql_error());
	$go5 = mysql_query($query5) or die("Could not run query 5: " . mysql_error());
	$go6 = mysql_query($query6) or die("Could not run query 6: " . mysql_error());
	
	echo("Install script executed.");
?>