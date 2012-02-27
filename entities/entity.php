<?php

	class Entity {
		
		public $id;
		public $name;
		
		public function __construct($id, $name) {
			$this->id = $id;
			$this->name = $name;
		}
		
		public function getId() {
			return $this->id;
		}
		public function getName() {
			return $this->name;
		}
		
		public function getAsArray() {
			return array("id" => $this->id, "name" => $this->name);
		}
		
	}
	
?>
