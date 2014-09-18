<?
	class appointment{
		private $id = 0;
		private $fellow_id = 0;
		private $pennkey = null;
		private $first_name = null;
		private $last_name = null;
		private $email = null;
		private $apt_date = null;
		private $comment = null;
		private $filename = null;
		private $filedate = null;
		private $time_index = 0;
		private $feedback_left = 0;
	
		function __construct(
			$a_id,
			$a_fellow_id,
			$a_pennkey,
			$a_first_name,
			$a_last_name,
			$a_email,
			$a_apt_date,
			$a_comment,
			$a_filename,
			$a_filedate,
			$a_time_index,
			$a_feedback_left
		){
			$this->id = $a_id;
			$this->fellow_id = $a_fellow_id;
			$this->pennkey = $a_pennkey;
			$this->first_name = $a_first_name;
			$this->last_name = $a_last_name;
			$this->email = $a_email;
			$this->apt_date = $a_apt_date;
			$this->comment = $a_comment;
			$this->filename = $a_filename;
			$this->filedate = $a_filedate;
			$this->time_index = $a_time_index;
			$this->feedback_left = $a_feedback_left;
		}
	
		public function get_id(){
			return $this->id;
		}
		
		public function get_fellow_id(){
			return $this->fellow_id;
		}
		
		public function get_pennkey(){
			return $this->pennkey;
		}
		
		public function get_first_name(){
			return $this->first_name;
		}
		
		public function get_last_name(){
			return $this->last_name;
		}
	
		public function get_email(){
			return $this->email;
		}
		
		public function get_apt_date(){
			return $this->apt_date;
		}
		
		public function get_comment(){
			return $this->comment;
		}
		
		public function get_filename(){
			return $this->filename;
		}
		
		public function get_filedate(){
			return $this->filedate;
		}
		
		public function get_time_index(){
			return $this->time_index;
		}
		
		public function get_feedback_left(){
			return $this->feedback_left;
		}
		
		public function set_id($p){
			$this->id = $p;
		}
		
		public function set_fellow_id($p){
			$this->fellow_id = $p;
		}
		
		public function set_pennkey($p){
			$this->pennkey = $p;
		}
		
		public function set_first_name($p){
			$this->first_name = $p;
		}
		
		public function set_last_name($p){
			$this->last_name = $p;
		}
	
		public function set_email($p){
			$this->email = $p;
		}
		
		public function set_apt_date($p){
			$this->apt_date = $p;
		}
		
		public function set_comment($p){
			$this->comment = $p;
		}
		
		public function set_filename($p){
			$this->filenames = $p;
		}
		
		public function set_filedate($p){
			$this->filedate = $p;
		}
		
		public function set_time_index($p){
			$this->time_index = $p;
		}
		
		public function set_feedback_left($p){
			$this->feedback_left = $p;
		}		
	}
?>