<?php

	/**
	 *	File: entities/allocation.php
	 *	Class: Allocation
	 *  Author: Oliver Woodings
	 *  Functionality: Abstracts the Allocation entity from the database
	 */
	 
	class Allocation {
		
		private $request;
		private $room;
		private $period;
		
		public function __construct($request, $room, $period) {
			$this->request = $request;
			$this->room = $room;
			$this->period = $period;
		}
		
		public function getRequest() {
			return $this->request;
		}
		public function getRoom() {
			return $this->room;
		}
		public function getPeriod() {
			return $this->period;
		}
		public function getDay() {
			return $this->request->getDay();
		}
		
	}

?>
