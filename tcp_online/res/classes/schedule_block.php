<?
	class schedule_block{
		private $id;
		private $fellow_id;
		private $block_date; //tcp date object
		private $time_index; //refer to constants
		
		public function __construct($_id, $_fellow_id, $_block_date, $_time_index){
			$this->id = $_id;
			$this->fellow_id = $_fellow_id;
			$this->block_date = $_block_date;
			$this->time_index = $_time_index;
		}
		
		public function get_id(){
			return $this->id;
		}
		
		public function set_id($_id){
			$this->id = $_id;
		}
		
		public function get_fellow_id(){
			return $this->fellow_id;
		}
		
		public function set_fellow_id($_fid){
			$this->fellow_id = $_fid;
		}
		
		public function get_block_date(){
			return $this->block_date;
		}
		
		public function set_block_date($_block_date){
			$this->block_date = $_block_date;
		}
		
		public function get_time_index(){
			return $this->time_index;
		}
		
		public function set_time_index($_time_index){
			$this->time_index = $_time_index;
		}
	}

?>