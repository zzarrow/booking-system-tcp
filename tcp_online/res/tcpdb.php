<?
	class tcpdb{
		private $conn = null; //db handle
		
		function __construct(){
			$this->conn = mysql_connect(DB_INFO::HOSTNAME, DB_INFO::USERNAME, DB_INFO::PASSWORD) or die("Error: Unable to connect to database.<br />MySQL said: " . mysql_error());
			mysql_select_db(DB_INFO::DB_NAME, $this->conn);
		}
		
		function generate_avail_arr($raw_data){
			//echo("Raw data: " . $raw_data . "<br />");
			$avail = array(array());
			//DAYS_OF_WEEK constants maps to the integers 0 through 4
			//For code simplicity, we'll hard code this into the for loop	
			for($i = 0; $i <= 4; $i++)
				for($j = 0; $j <= 23; $j++){
						if(strlen($raw_data) <= ((24 * $i) + $j)){
							$avail[$i][$j] = 0; //check this... might be backwards
							continue;
						}
						$avail[$i][$j] = $raw_data{(24 * $i) + $j};
						//echo('Setting avail[' . $i . '][' . $j . ']<br />');
						//echo('raw_data{' . 24*$i + $j . '}<br />');
						//echo($raw_data{(24 * $i) + $j} . '<br />');
					}
					
			return $avail;							
		}
		
		function create_appointments_from_query($_query){
			$q = mysql_query($_query) or die("Could not run query: " . mysql_error());
			$appointments = array();
			while($fetch = mysql_fetch_object($q)){
				$appointments[] = new appointment(
					$fetch->id,
					$fetch->fellow_id,
					$fetch->pennkey,
					$fetch->firstname,
					$fetch->lastname,
					$fetch->email,
					$fetch->apt_date,
					$fetch->comment,
					$fetch->filename,
					$fetch->upload_date,
					$fetch->time_index,
					$fetch->feedback_left
				);
			}
			
			return $appointments;
		}
		
		//return an array of appointments
		function get_appointments_for_fellow($fid){
			return $this->create_appointments_from_query("SELECT * from `appointments` WHERE `fellow_id`='" . $fid . "' ORDER BY `id` ASC");
		}
		
		function create_block_list_from_query($_query){
			$q = mysql_query($_query) or die('Error retrieving block list: ' . mysql_error());
			$blocks = array();
			while($fetch = mysql_fetch_object($q)){
				$blocks[] = new schedule_block(
					$fetch->id,
					$fetch->fellow_id,
					$fetch->block_date,
					$fetch->time_index
				);
			}			
			return $blocks;
		}
		
		//return an array of block objects
		function get_block_list_for_fellow($fid){
			return $this->create_block_list_from_query(
				"SELECT * from `schedule_blocks`
					WHERE `fellow_id`='" . $fid . "'
						AND (
							(`block_date` > " . tcp_date::get_today_as_db() . ")
							|| (
								(`block_date` = " . tcp_date::get_today_as_db() . ")
								 && (`time_index` >= " . tcp_date::get_current_time_index() . ")
								)
							) ORDER BY `block_date` ASC"
			); //Only retrieve blocks that are in the future (or the current time_index)
		}
		
		function add_schedule_block($_fid, $_date, $_time_index){			
			mysql_query("
				INSERT INTO schedule_blocks (
					`id`, `fellow_id`, `block_date`, `time_index`
				) VALUES (
					'',
					$_fid,
					$_date,
					$_time_index
				)			
			") or die('Error adding schedule block: ' . mysql_error());		
		}
		
		function remove_schedule_block($_bid){
			mysql_query("
				DELETE FROM schedule_blocks WHERE `id`='" . $_bid . "' LIMIT 1;			
			") or die('Error removing schedule blocks: ' . mysql_error());
		}
		
		function can_fellow_remove_block($_fid, $_bid){
			//Can simplify using a JOIN...
			$query = mysql_query("SELECT `fellow_id` from schedule_blocks WHERE `id`='" . $_bid . "' LIMIT 1;") or die('Cannot authenticate block remove request: ' . mysql_error());
			if(mysql_num_rows($query) < 1)
				return false;
			return true;
		}
		
		function create_fellows_from_query($_query){
			$run = mysql_query($_query) or die("Error running query.<br />MySQL said: " . mysql_error());
			//if(mysql_num_rows($run) == 0){
			//	echo('No fellows found for query: ' . $_query . '<br />');
			//	return null;
			//}
			$fellow_arr = array();
			while($fetch = mysql_fetch_object($run)){	
				$curr_fellow = new fellow(
										$fetch->id,
										$fetch->access_level,
										$fetch->pennkey,
										$fetch->firstname,
										$fetch->lastname,
										$fetch->email,
										$fetch->is_active,
										$fetch->can_book,
										$fetch->major,
										$fetch->class_year,
										$this->generate_avail_arr($fetch->availability)
								);
				$curr_fellow->set_appointments($this->get_appointments_for_fellow($fetch->id));
				$curr_fellow->set_block_list($this->get_block_list_for_fellow($fetch->id));
				
				$fellow_arr[] = $curr_fellow;
			}
			
			return $fellow_arr;
		}
		//returns an array of fellows
		function get_all_fellows(){
			return $this->create_fellows_from_query("SELECT * from `fellows` ORDER BY `lastname` ASC");			
		}
		
		function get_active_fellows(){
			return $this->create_fellows_from_query("SELECT * from `fellows` WHERE `is_active`='1' ORDER BY `lastname` ASC");			
		}
		
		function get_inactive_fellows(){
			return $this->create_fellows_from_query("SELECT * from `fellows` WHERE `is_active`='0' ORDER BY `lastname` ASC");			
		}
		
		//returns an array of fellows
		function get_bookable_fellows(){
			return $this->create_fellows_from_query("SELECT * from `fellows` WHERE `can_book`='1' and `is_active`=1 ORDER BY `id` ASC");
		}
		
		//returns a fellow singleton
		function get_fellow_by_id($_fid){
			//PHP currently can't dereference a return result directly from a function
			$fellow_arr = $this->create_fellows_from_query("SELECT * from `fellows` WHERE `id`='" . $_fid . "' LIMIT 1");
			if(sizeof($fellow_arr) == 0)
				return null;
			return $fellow_arr[0];
		}
		
		function get_fellow_name_by_id($_fid){
			$query = mysql_query("SELECT `firstname`, `lastname` from `fellows` WHERE `id`='" . $_fid . "' LIMIT 1;") or die('Error running query: ' . mysql_error());
			$result = mysql_fetch_object($query);
			
			return $result->firstname . " " . $result->lastname;
		}
		
		//returns a fellow singleton
		function get_fellow_by_pennkey($_pennkey){
			$fellow_arr = $this->create_fellows_from_query("SELECT * from `fellows` WHERE `pennkey`='" . $_pennkey . "' LIMIT 1"); 
			return $fellow_arr[0];
		}
		
		function get_fellow_id_by_pennkey($_pennkey){
			return $this->get_fellow_by_pennkey($_pennkey)->get_id();
		}
		
		function book_appointment($_appointment){
			mysql_query("
			INSERT INTO appointments (
				`id`, `fellow_id`, `pennkey`, `firstname`, `lastname`, `email`, `apt_date`, `comment`, `filename`, `upload_date`, `time_index`, `feedback_left`
			) VALUES (
				'',
				'" . $_appointment->get_fellow_id() . "',
				'" . $_appointment->get_pennkey() . "',
				'" . $_appointment->get_first_name() . "',
				'" . $_appointment->get_last_name() . "',
				'" . $_appointment->get_email() . "',
				'" . $_appointment->get_apt_date() . "',
				'" . $_appointment->get_comment() . "',
				'" . $_appointment->get_filename() . "',
				'" . $_appointment->get_filedate() . "',
				'" . $_appointment->get_time_index() . "',
				'" . $_appointment->get_feedback_left() . "' 
			)	
		") or die('Error running query: ' . mysql_error());
		}
		
		function cancel_appointment($aid){
			mysql_query("
				DELETE FROM `appointments` WHERE `id`=" . $aid . " LIMIT 1;
			") or die('Error cancelling appointment: ' . mysql_error());
			
			//echo('<br />Appointment canceled.');
		}
		
		function update_fellow_availability($_fellow, $_availability){
//			echo("<br />Updating availability to: " . $_availability);
			mysql_query("
				UPDATE fellows
				SET availability='" . $_availability . "'
				WHERE `id`='" . $_fellow->get_id() . "' LIMIT 1;
			") or die('Error running query: ' . mysql_error());
		}
		
		function get_access_level($_pennkey){
			//FOR DEBUG ONLY
			//return ACCESS_LEVELS::director;
			//END DEBUG
			$go = mysql_query('SELECT `access_level` from `fellows` WHERE `pennkey`="' . $_pennkey . '" LIMIT 1') or die('Query failed: ' . mysql_error());
			if(mysql_num_rows($go) > 0)
				return mysql_fetch_object($go)->access_level;	
			else
				return ACCESS_LEVELS::student;
		}
	
		//returns an appointment or null
		function get_next_student_apt($_pennkey){
			//Find appointments with either:
				//A later date
				//or
				//The same date, but a later time
			$sel = 'SELECT * from `appointments` WHERE `pennkey`="' . $_pennkey . '"
					AND
						(
						    (`apt_date` > ' . tcp_date::get_today_as_db() . ')
							OR (
								(`apt_date` = ' . tcp_date::get_today_as_db() . ')
								AND `time_index` >= ' . tcp_date::get_current_time_index() . '
							)
						 )					
					ORDER BY `apt_date` ASC
					LIMIT 1';
			$apt_arr = $this->create_appointments_from_query($sel);
			if($apt_arr == null)
				return null;
			return $apt_arr[0];								
		}
		
		function get_next_fellow_apt($_pennkey){
			//Could rewrite this query to use a JOIN
			
			$fid = $this->get_fellow_id_by_pennkey($_pennkey);
			
			//then, find all appointments for that fellow
			$apt_arr = $this->create_appointments_from_query('SELECT * from `appointments` WHERE `fellow_id`="' . $fid . '"
											AND
												(
												    (`apt_date` > ' . tcp_date::get_today_as_db() . ')
													OR (
														(`apt_date` = ' . tcp_date::get_today_as_db() . ')
														AND `time_index` >= ' . tcp_date::get_current_time_index() . '
													)
												 )					
										ORDER BY `apt_date` ASC
										LIMIT 1');
			if($apt_arr == null)
				return null;
			return $apt_arr[0];
		}
		
		function get_next_dept_apt(){
			$apt_arr = $this->create_appointments_from_query('SELECT * from `appointments` WHERE 
												(
												    (`apt_date` > ' . tcp_date::get_today_as_db() . ')
													OR (
														(`apt_date` = ' . tcp_date::get_today_as_db() . ')
														AND `time_index` >= ' . tcp_date::get_current_time_index() . '
													)
												 )					
										ORDER BY `apt_date` ASC
										LIMIT 1');
			if($apt_arr == null)
				return null;
			return $apt_arr[0];
		}
		
		function get_apt_by_id($_aid){
			$apt_arr = $this->create_appointments_from_query('
				SELECT * from `appointments` WHERE `id`="' . $_aid . '" LIMIT 1;			
			');
			
			if($apt_arr == null)
				return null;			
			return $apt_arr[0];
		}

		function get_apt_id_from_info($_pennkey, $_apt_date, $_time_index){
			$apt_arr = $this->create_appointments_from_query("
				SELECT * from `appointments`
					WHERE `pennkey`='" . $_pennkey . "'
					AND `apt_date`='" . $_apt_date . "'
					AND `time_index`='" . $_time_index . "'
				LIMIT 1;
			");

			if(sizeof($apt_arr) == 0)
				return null;

			return $apt_arr[0]->get_id();
		}		

		static function db_sanitize($_input){
			return trim(strip_tags(addslashes($_input)));
		}
		
		function add_fellow(
							$_firstname,
							$_lastname,
							$_email,
							$_active,
							$_pennkey,
							$_access_level,
							$_major,
							$_class_year,
							$_can_book
							){
			mysql_query("
				INSERT INTO fellows (
					`id`,
					`access_level`,
					`pennkey`,
					`firstname`,
					`lastname`,
					`email`,
					`is_active`,
					`can_book`,
					`major`,
					`class_year`,
					`availability`
				) VALUES (
					'',
					'" . $_access_level . "',
					'" . $_pennkey . "',
					'" . $_firstname . "',
					'" . $_lastname . "',
					'" . $_email . "',
					'" . $_active . "',
					'" . $_can_book . "',
					'" . $_major . "',
					'" . $_class_year . "',
					'000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'				
				)			
			") or die('Error adding new fellow to database: ' . mysql_error());			
		}
		
		function update_fellow_metadata(
			$_fid,
			$_firstname,
			$_lastname,
			$_email,
			$_active,
			$_pennkey,
			$_access_level,
			$_major,
			$_class_year,
			$_can_book
		){
			mysql_query("
				UPDATE fellows
					SET `firstname`='" . $_firstname . "',
						`lastname`='" . $_lastname . "',
						`email`='" . $_email . "',
						`is_active`='" . $_active . "',
						`pennkey`='" . $_pennkey . "',
						`access_level`='" . $_access_level . "',
						`major`='" . $_major . "',
						`class_year`='" . $_class_year . "',
						`can_book`='" . $_can_book . "'
					WHERE `id`='" . $_fid . "' LIMIT 1;
			") or die('Error updating fellow information: ' . mysql_error());		
		}
		
		function get_meta_key($_key){
			$query = mysql_query(
				"SELECT `val` from rdb_metainfo WHERE `key`='" . $_key . "' LIMIT 1;"
			) or die('Cannot get metainfo from database: ' . mysql_error());
			
			return(mysql_fetch_object($query)->val);
		}
		
		function set_meta_key($_key, $_val){
			mysql_query("
				UPDATE rdb_metainfo SET(
					`val`='" . $_val . "'
				) WHERE `key`=" . $_key . "' LIMIT 1;				
			") or die('Cannot update metainformation: ' . mysql_error());
		}
		
		function get_contact_name(){
			return $this->get_meta_key('contact_name');
		}
		
		function set_contact_name($_name){
			$this->set_meta_key('contact_name', $_name);
		}
		
		function get_contact_email(){
			return $this->get_meta_key('contact_email');
		}
		
		function set_contact_email($_email){
			$this->set_meta_key('contact_name', $_email);
		}
		
		function get_sysadmin_name(){
			return $this->get_meta_key('sysadmin_name');
		}
		
		function set_sysadmin_name($_name){
			$this->set_meta_key('sysadmin_name', $_name);
		}
		
		function get_sysadmin_email(){
			return $this->get_meta_key('sysadmin_email');
		}
		
		function set_sysadmin_email($_email){
			$this->set_meta_key('sysadmin_name', $_email);
		}
		
		function get_system_url(){
			return $this->get_meta_key('system_url');
		}
		
		function set_system_url($_url){
			$this->set_meta_key('contact_name', $_url);
		}
	}
?>
