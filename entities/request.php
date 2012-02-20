<?php

	class Request {
		
		protected $id = 0;
		protected $round = 0;
		protected $status = 0;
		protected $module;
		protected $roomtype;
		protected $building;
		protected $rooms = array();
		protected $park;
		protected $period = 0;
		protected $day = 0;
		protected $weeks = array();
		protected $length = 0;
		protected $period = 0;
		protected $numStudents = 0;
		protected $priority = false;
		protected $specReq = "";
		
		/**
		 * Saves the request to the database
		 */
		public function save() {
			
		}
		
		
		
		/**
		 * Rooms
		 */
		public function getRooms() {
			return $this->numRooms;
		}
		public function addRooms($room) {
			array_push($this->numRooms, $room);
		}
		public function setRooms($rooms) {
			$this->rooms = $rooms;
		}
		
		/**
		 * Park
		 */
		public function getPark() {
			return $this->park;
		}
		public function setPark($park) {
			$this->park = $park;
		}
		
		/**
		 * Period
		 */
		public function getPeriod() {
			return $this->period;
		}
		public function setPeriod($period) {
			$this->period = $period;
		}
		
		/**
		 * Day
		 */
		public function getDay() {
			return $this->day;
		}
		public function setDay($day) {
			$this->day = $day;
		}
		
		/**
		 * Weeks
		 */
		public function getWeeks() {
			return $this->weeks;
		}
		public function setWeeks($weeks) {
			$this->weeks = $weeks;
		}
		
		/**
		 * Length
		 */
		public function getLength() {
			return $this->length;
		}
		public function setLength($length) {
			$this->length = $length;
		}		
		
		/**
		 * Num Rooms
		 */
		public function getNumRooms() {
			return $this->period;
		}
		public function setNumRooms($period) {
			$this->period = $period;
		}
		
		/**
		 * Students
		 */
		public function getNumStudents() {
			return $this->numStudents;
		}
		public function setNumStudents($numStudents) {
			$this->numStudents = $numStudents;
		}
		
		/**
		 * Priority
		 */
		public function getPriority() {
			return $this->priority;
		}
		public function setPriority($priority) {
			$this->priority = $priority;
		}
		
		/**
		 * Spec Req
		 */
		public function getSpecReq() {
			return $this->specReq;
		}
		public function setSpecReq($req) {
			$this->specReq = $req;
		}
		
	}

?>
