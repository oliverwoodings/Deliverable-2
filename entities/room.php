<?php

	class Room {
		
		private $id;
		private $code;
		private $building;
		private $type;
		private $facilities;
        private $capacity;
		
		public function __construct($id, $code, $building, $type, $facilities, $capacity) {
			$this->id = $id;
			$this->code = $code;
			$this->building = $building;
			$this->type = $type;
			$this->facilities = $facilities;
            $this->capacity = $capacity;
		}
		
		public function getId() {
			return $this->id;
		}
		public function getCode() {
			return $this->code;
		}
		public function getBuilding() {
			return $this->building;
		}
		public function getType() {
			return $this->type;
		}
		public function getFacilities() {
			return $this->facilities;
		}
        public function getCapacity() {
            return $this->capacity;
        }
        
        public function getAsArray() {
            $facilities = array();
            foreach ($this->facilities as $facility) array_push($facilities, get_object_vars($facility));
            return array("id" => $this->id, "code" => $this->code, "building" => $this->building->getAsArray(), "type" => get_object_vars($this->type), "facilities" => $facilities, "capacity" => $this->capacity);
        }
		
	}

?>
