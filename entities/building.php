<?php

	class Building {
		
		private $id;
		private $name;
		private $park;
		private $code;
		
		public function __construct($id, $name, $code, $park) {
			$this->id = $id;
			$this->code = $code;
			$this->park = $park;
			$this->name = $name;
		}
		
		public function getId() {
			return $this->id;
		}
		public function getCode() {
			return $this->code;
		}
		public function getPark() {
			return $this->park;
		}
		public function getName() {
			return $this->name;
		}
        
        public function getAsArray() {
            return array("id" => $this->id, "name" => $this->name, "park" => get_object_vars($this->park), "code" => $this->code);
        }
		
	}

?>
