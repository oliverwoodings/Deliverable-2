<?php

	class Round {
		
		private $id;
		private $round;
		private $startDate;
		private $endDate;
		private $active;
		
		public function __construct($id, $round, $startDate, $endDate, $active) {
			$this->id = $id;
			$this->round = $round;
			$this->startDate = $startDate;
			$this->endDate = $endDate;
			$this->active = $active;
		}
		
		public function getId() {
			return $this->id;
		}
		public function getRound() {
			return $this->round;
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
		
	}


?>