<?php

	class Round {
		
		private $id;
		private $name;
		private $startDate;
		private $endDate;
		private $active;
		private $semester;
		
		public function __construct($id, $name, $startDate, $endDate, $active, $semester) {
			$this->id = $id;
			$this->name = $name;
			$this->startDate = $startDate;
			$this->endDate = $endDate;
			$this->active = $active;
			$this->semester = $semester;
		}
		
		public function getId() {
			return $this->id;
		}
		public function getName() {
			return $this->name;
		}
		public function getStartDate() {
			return $this->startDate;
		}
		public function getEndDate() {
			return $this->endDate;
		}
		public function getActive() {
			return $this->active;
		}
		public function getSemester() {
			return $this->semester;
		}
		public function setActive($active) {
			$this->active = $active;
		}
		
	}


?>