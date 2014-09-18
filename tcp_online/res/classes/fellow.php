<?
	class fellow{
		private $id = 0;
		private $access_level = ACCESS_LEVELS::intern;
		private $pennkey = null;
		
		private $firstname = null;
		private $lastname = null;
	
		private $can_book_appointments = 1;	
	
		private $major = null;
		private $class_year = 0;
		
		private $email = null;
		private $active = false;
		
		private $availabilities = array(
									DAYS_OF_WEEK::MONDAY => array(
																	0 => 0,
																	1 => 0,
																	2 => 0,
																	3 => 0,
																	4 => 0,
																	5 => 0,
																	6 => 0,
																	7 => 0,
																	8 => 0,
																	9 => 0,
																	10 => 0,
																	11 => 0,
																	12 => 0,
																	13 => 0,
																	14 => 0,
																	15 => 0,
																	16 => 0,
																	17 => 0,
																	18 => 0,
																	19 => 0,
																	20 => 0,
																	21 => 0,
																	22 => 0,
																	23 => 0,									
										),
									DAYS_OF_WEEK::TUESDAY => array(
																	0 => 0,
																	1 => 0,
																	2 => 0,
																	3 => 0,
																	4 => 0,
																	5 => 0,
																	6 => 0,
																	7 => 0,
																	8 => 0,
																	9 => 0,
																	10 => 0,
																	11 => 0,
																	12 => 0,
																	13 => 0,
																	14 => 0,
																	15 => 0,
																	16 => 0,
																	17 => 0,
																	18 => 0,
																	19 => 0,
																	20 => 0,
																	21 => 0,
																	22 => 0,
																	23 => 0,									
										),
									DAYS_OF_WEEK::WEDNESDAY => array(
																	0 => 0,
																	1 => 0,
																	2 => 0,
																	3 => 0,
																	4 => 0,
																	5 => 0,
																	6 => 0,
																	7 => 0,
																	8 => 0,
																	9 => 0,
																	10 => 0,
																	11 => 0,
																	12 => 0,
																	13 => 0,
																	14 => 0,
																	15 => 0,
																	16 => 0,
																	17 => 0,
																	18 => 0,
																	19 => 0,
																	20 => 0,
																	21 => 0,
																	22 => 0,
																	23 => 0,									
										),
									DAYS_OF_WEEK::THURSDAY => array(
																	0 => 0,
																	1 => 0,
																	2 => 0,
																	3 => 0,
																	4 => 0,
																	5 => 0,
																	6 => 0,
																	7 => 0,
																	8 => 0,
																	9 => 0,
																	10 => 0,
																	11 => 0,
																	12 => 0,
																	13 => 0,
																	14 => 0,
																	15 => 0,
																	16 => 0,
																	17 => 0,
																	18 => 0,
																	19 => 0,
																	20 => 0,
																	21 => 0,
																	22 => 0,
																	23 => 0,									
										),
									DAYS_OF_WEEK::FRIDAY => array(
																	0 => 0,
																	1 => 0,
																	2 => 0,
																	3 => 0,
																	4 => 0,
																	5 => 0,
																	6 => 0,
																	7 => 0,
																	8 => 0,
																	9 => 0,
																	10 => 0,
																	11 => 0,
																	12 => 0,
																	13 => 0,
																	14 => 0,
																	15 => 0,
																	16 => 0,
																	17 => 0,
																	18 => 0,
																	19 => 0,
																	20 => 0,
																	21 => 0,
																	22 => 0,
																	23 => 0,									
										),
								  );
								  
		private $block_list = array(); //array of schedule_block objects							  
		private $appointments = array(); //array of appointment objects

		public function get_firstname(){
			return $this->firstname;
		}
		
		public function set_firstname($fn){
			$this->firstname = $fn;
		}
		
		public function get_lastname(){
			return $this->lastname;
		}
		
		public function set_lastname($ln){
			$this->lastname = $ln;
		}

		public function get_email(){
			return $this->email;
		}
		
		public function set_email($_email){
			$this->email = $_email;
		}
		
		public function is_active(){
			return $this->active;
		}
		
		public function set_active($_active){
			$this->active = $_active;
		}
		
		public function can_book(){
			return $this->can_book_appointments;
		}
		
		public function set_can_book($_can_book){
			$this->can_book_appointments = $_can_book;
		}								  
								  
		public function get_id(){
			return $this->id;
		}
		
		public function set_id($_id){
			$this->id = $_id;
		}
		
		public function get_access_level(){
			return $this->access_level;
		}
		
		public function set_access_level($_al){
			$this->access_level = $_al;
		}
		
		public function get_pennkey(){
			return $this->pennkey;
		}
		
		public function set_pennkey($_pennkey){
			$this->pennkey = $_pennkey;
		}
		
		public function get_major(){
			return $this->major;
		}
		
		public function set_major($_major){
			$this->major = $_major;		
		}
		
		public function get_class_year(){
			return $this->class_year;
		}
		
		public function set_class_year($_class_year){
			$this->class_year = $_class_year;
		}
		
		public function get_availabilities(){
			return $this->availabilities;
		}
		
		public function set_availabilities($_avail){
			$this->availabilities = $_avail;
		}
		
		public function get_appointments(){
			return $this->appointments;
		}
		
		public function set_appointments($_apt){
			$this->appointments = $_apt;
		}
		
		public function get_block_list(){
			return $this->block_list;
		}
		
		public function set_block_list($_block){
			$this->block_list = $_block;
		}
		
		public function get_raw_availibility($day_of_week){
			return $this->availabilities[$day_of_week];
		}
		
		public function set_raw_availability($day_of_week, $avail){
			$this->availabilities[$day_of_week] = $avail;
		}
		
		public function get_availability_at_time_index($day_of_week, $time_index){
			//echo('size of availabilities: ' . sizeof($this->availabilities));
			$avail_for_day = $this->availabilities[$day_of_week];
			//echo('size of avail for day: ' . sizeof($avail_for_day) . '<br />');
			//for($i = 0; $i < sizeof($avail_for_day); $i++)
				//echo('avail_for_day[' . $i . '] = ' . $avail_for_day[$i] . '<br />');
			//echo('Going to return value for time index ' . $time_index . ': ' . $avail_for_day[$time_index] . '<br />');
			return $avail_for_day[$time_index];
		}
		
		public function set_availability_at_time_index($day_of_week, $time_index, $avail_bit){
			$this->availabilities[$day_of_week][time_index] = avail_bit;
		}
		
		public function has_appointment_at_time($_date, $time_index){
			for($i = 0; $i < sizeof($this->get_appointments()); $i++){
			
				//echo('apt_date: ' . $this->appointments[$i]->get_apt_date() . '<br />');
				//echo('db_date: ' . tcp_date::get_dbdate_from_tcpdate($_date) . '<br />');
				//echo('time_index input: ' . $time_index . '<br />');
				//echo('time_index apt: ' . $this->appointments[$i]->get_time_index() . '<br />');
				
				if(($this->appointments[$i]->get_apt_date() == tcp_date::get_dbdate_from_tcpdate($_date)) && ($this->appointments[$i]->get_time_index() == $time_index))
					return 1;
			}
					
			return 0;			
		}
		
		public function has_block_at_time($_date, $time_index){
			for($i = 0; $i < sizeof($this->block_list); $i++)
				if(($this->block_list[$i]->get_block_date() == tcp_date::get_dbdate_from_tcpdate($_date)) && ($this->block_list[$i]->get_time_index() == $time_index))
					return 1;
			
			return 0;
		}
		
		public function is_available($_date, $time_index){
		
		//	echo('availability at time index: ' . $this->get_availability_at_time_index($_date->get_day_of_week(), $time_index) . '<br />');
		//	echo('Has appointment at time: ' . $this->has_appointment_at_time($_date, $time_index) . '<br />');
		//	echo('Has block at time: '. $this->has_block_at_time($_date, $time_index) . '<br />');
		
			return(
				($this->get_availability_at_time_index($_date->get_day_of_week(), $time_index)
				&& !(($this -> has_appointment_at_time($_date, $time_index)) || ($this -> has_block_at_time($_date, $time_index))))
			);
		}
		
		//See (array-based) formats for availability, appointments, f_blocks
		//Hmm.... PHP doesn't support overloading constructors.  We'll keep these commented out
		//in case they're needed later.
		
		function __construct($f_id, $f_access_level, $f_pennkey, $f_firstname, $f_lastname, $f_email, $f_active, $f_canbook, $f_major, $f_class_year, $f_availability){
			$this->set_id($f_id);
			$this->set_access_level($f_access_level);
			$this->set_pennkey($f_pennkey);
			
			$this->set_firstname($f_firstname);
			$this->set_lastname($f_lastname);
			$this->set_email($f_email);
			$this->set_active($f_active);
	
			$this->set_can_book($f_canbook);
			
			$this->set_major($f_major);
			$this->set_class_year($f_class_year);
			
			$this->set_availabilities($f_availability);
			$this->set_appointments(array());
			$this->set_block_list(array());
		}
	}
?>