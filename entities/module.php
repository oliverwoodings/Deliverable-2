<?php
    
    /**
     * Represents a module entry in the database
     */
	class Module {
		
		private $id;
		private $name;
		private $department;
		private $code;
		private $lecturers;
		
		public function __construct() {
			$a = func_get_args();
			$i = func_num_args();
			if (method_exists($this,$f='__construct'.$i)) {
				call_user_func_array(array($this,$f),$a);
			} 
		}
		private function __construct4($name, $department, $code, $lecturers) {
			$this->name = $name;
			$this->department = $department;
			$this->code = $code;
			$this->lecturers = $lecturers;
		}
		private function __construct5($id, $name, $department, $code, $lecturers) {
			$this->id = $id;
			$this->name = $name;
			$this->department = $department;
			$this->code = $code;
			$this->lecturers = $lecturers;
		}
        
        /**
         * Getters
         */
        public function getId() {
            return $this->id;
        }
		public function setId($id) {
			$this->id = $id;
		}
        public function getName() {
            return $this->name;
        }
		public function setName($name) {
			$this->name = $name;
		}
		public function getDepartment() {
            return $this->department;
        }
		public function setDepartment($department) {
			$this->department = $department;
		}
		public function getCode() {
			return $this->code;
		}
		public function setCode($code) {
			$this->code = $code;
		}
        public function getLecturers() {
            return $this->lecturers;
        }
		public function setLecturers($lecturers) {
			$this->lecturers = $lecturers;
		}		
		
	}

?>
