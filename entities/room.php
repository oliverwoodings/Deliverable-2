<?php

	class Room {
		
		private $id;
		private $name;
		private $building;
		private $park;
		private $type;
		
		public function __construct($id, $name, $building, $park, $type) {
			$this->id = $id;
			$this->name = $name;
			$this->building = $building;
			$this->park = $park;
			$this->type = $type;		
		}
		
		public function getId() {
			return $this->id;
		}
		public function getName() {
			return $this->name;
		}
		public function getBuilding() {
			return $this->building;
		}
		public function getPark() {
			return $this->park;
		}
		public function getType() {
			return $this->type;
		}
		
	}

?>
