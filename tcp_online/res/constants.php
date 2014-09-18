<?
	class ACCESS_LEVELS{
		const director = 0;
		const fellow = 1;
		const intern = 2;
		const student = 3;
		
		static function AUTHENTICATE($_req_lvl){
			$auth_db_conn = new tcpdb();
			if($auth_db_conn->get_access_level($_SERVER['REMOTE_USER']) < $_req_lvl)
				die('Sorry, you are not authorized to view this page. <a href="javascript:history.go(-1)">Back</a>');
		}
			
	} //Access via ACCESS_LEVELS::director , etc.
	
	class DAYS_OF_WEEK{
		const MONDAY = 0;
		const TUESDAY = 1;
		const WEDNESDAY = 2;
		const THURSDAY = 3;
		const FRIDAY = 4;
	}
	
	class DB_INFO{
		const HOSTNAME = "fling.seas.upenn.edu";
		const USERNAME = "*****REDACTED FOR GITHUB*****";
		const PASSWORD = "*****REDACTED FOR GITHUB*****";
		const DB_NAME = "*****REDACTED FOR GITHUB*****";
	}
	
	$UPLOAD_FILE_PATH = "./submissions";
	$APPOINTMENTS_PER_PAGE = 14; //Really refers to the max # of days to look ahead
	
?>
