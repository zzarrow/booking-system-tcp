<?
	//I wrote this class to avoid having to deal with PHP's dates library
	//I thought this would be easier
	//I was wrong.

	//Stored locally separating year, month, day
	//Year = yyyy, Month = mm, Day = dd
	//Stored in the database as yyyymmdd
	
	class tcp_date{
	
		private $year = 0;
		private $month = 0;
		private $day = 0;
	
		static function str_to_index($_time_str){
			$temp_arr = array(
					"9:00am" => 0,
					"9:30am" => 1,
					"10:00am" => 2,
					"10:30am" => 3,
					"11:00am" => 4,
					"11:30am" => 5,
					"12:00pm" => 6,
					"12:30pm" => 7,
					"1:00pm" => 8,
					"1:30pm" => 9,
					"2:00pm" => 10,
					"2:30pm" => 11,
					"3:00pm" => 12,
					"3:30pm" => 13,
					"4:00pm" => 14,
					"4:30pm" => 15,
					"5:00pm" => 16,
					"5:30pm" => 17,
					"6:00pm" => 18,
					"6:30pm" => 19,
					"7:00pm" => 20,
					"7:30pm" => 21,
					"8:00pm" => 22,
					"8:30pm" => 23
				  );
				  
			return $temp_arr[$_time_str];		
		}
		
		static function index_to_str($_time_index){
			$temp_arr = array(
					0 => "9:00am",
					1 => "9:30am",
					2 => "10:00am",
					3 => "10:30am",
					4 => "11:00am",
					5 => "11:30am",
					6 => "12:00pm",
					7 => "12:30pm",
					8 => "1:00pm",
					9 => "1:30pm",
					10 => "2:00pm",
					11 => "2:30pm",
					12 => "3:00pm",
					13 => "3:30pm",
					14 => "4:00pm",
					15 => "4:30pm",
					16 => "5:00pm",
					17 => "5:30pm",
					18 => "6:00pm",
					19 => "6:30pm",
					20 => "7:00pm",
					21 => "7:30pm",
					22 => "8:00pm",
					23 => "8:30pm"
			 );
			 
			 return $temp_arr[$_time_index];
		}
	
		public static function determine_day_of_week($y, $m, $d){
			$day_of_week = date('l', strtotime($y . '/' . $m . '/' . $d));
			//echo('dow: ' . $day_of_week . ' <br />');
			if($day_of_week == "Monday")
				return DAYS_OF_WEEK::MONDAY;
			if($day_of_week == "Tuesday")
				return DAYS_OF_WEEK::TUESDAY;
			if($day_of_week == "Wednesday")
				return DAYS_OF_WEEK::WEDNESDAY;
			if($day_of_week == "Thursday")
				return DAYS_OF_WEEK::THURSDAY;
			if($day_of_week == "Friday") //gotta get down
				return DAYS_OF_WEEK::FRIDAY;

			return $day_of_week;
		}
		
		//PHP can't overload constructors :-/
	
	/**
		function __construct(){
			//TODO - what needs to be done?
		}
		
		public function __construct($y, $m, $d, $dow){
			$this->year = $y;
			$this->month = $m;
			$this->day = $d;
			
			$this->day_of_week = $dow;
		}
	**/		
		
		function __construct($y, $m, $d){
			$this->year = $y;
			$this->month = $m;
			$this->day = $d;
			
			$this->day_of_week = $this->determine_day_of_week($this->year, $this->month, $this->day);
		}
		
		/**
		//Date in DB format (yyyymmdd)
		function __construct($dbdate){
			$this->year = (int)substr($dbdate, 0, 4);
			$this->month = (int)substr($dbdate, 4, 2);
			$this->day = (int)substr($dbdate, 6, 2);
			
			$this->day_of_week = $this->determine_day_of_week($this->year, $this->month, $this->day);
		}
		**/
		
		public static function get_tcpdate_from_dbdate($dbdate){
			$y = (int)substr($dbdate, 0, 4);
			$m = (int)substr($dbdate, 4, 2);
			$d = (int)substr($dbdate, 6, 2);
			
			return new tcp_date($y, $m, $d);
		}
		
		public static function get_dbdate_from_tcpdate($tcpdate){
			$month = $tcpdate->get_month();
			$day = $tcpdate->get_day();
			
			if($month < 10)
				$month = '0' . $month;
			
			if($day < 10)
				$day = '0' . $day;
				
			return $tcpdate->get_year() . $month . $day;
		}
		
		public function get_year(){
			return $this->year;
		}
		
		public function get_month(){
			return $this->month;
		}
		
		public function get_day(){
			return $this->day;
		}
		
		public static function get_today_as_db(){
			return date('Ymd');
		}
		
		public static function get_current_time_index(){
			//Cover times outside of our operating hours
			if((date('a') == "pm") && ((int)date('g') > 8))
				return 24; //highest possible value is 23 - 24 pretty much means "go to tomorrow"
			if((date('a') == "am") && ((int)date('g') < 9) || (int)date('g') == 12)
				return 0; //Points to beginning of todays
				
			$mins = (int)date('i');
			if($mins >= 30)
				$mins = "30";
			else
				$mins = "00";
			
			return tcp_date::str_to_index(date('g') . ':' . $mins . date('a'));
		}
		
		public function to_db_string(){
			$y = $this->get_year();
			$m = $this->get_month();
			$d = $this->get_day();
			
			if(length($m) == 1)
				$m = "0" . $m;
			
			if(length($d) == 1)
				$d = "0" . $d;
			
			return $y . $m . $d;
		}
		
		public function get_day_of_week(){
			return $this->day_of_week;	
		}
		
		public function to_string(){
			//return $this->day_of_week . ", " . $this->month . " " . $this->day . ", " . $this->year;
			return date("l, F jS", mktime(0, 0, 0, $this->month, $this->day, $this->year));
		}
		
		public static function raw_to_readable($d){
			$ret_date = new tcp_date($d{0} . $d{1} . $d{2} . $d{3},
						 $d{4} . $d{5},
						 $d{6} . $d{7}
						);
						
			return $ret_date->to_string();
		}
		
		public function is_in_past(){
			//TODO
		}
	}

?>