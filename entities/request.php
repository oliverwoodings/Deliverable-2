<?php
	/**
	 *	File: entities/request.php
	 *	Class: Request
	 *  Author: Oliver Woodings
	 *  Functionality: Abstracts the Request entity from the database
	 */
	class Request {
		
		protected $id;
		protected $round;
		protected $status;
		protected $module;
		protected $roomType;
		protected $rooms = array();
		protected $park;
		protected $period;
		protected $day;
		protected $weeks = array();
		protected $length;
		protected $numStudents;
		protected $numRooms;
		protected $priority = false;
		protected $specReq;
		protected $facilities = array();
		
		/**
		 * Id
		 */
		public function getId() {
			return $this->id;
		}
		public function setId($id) {
			$this->id = $id;
		}
		
		/**
		 * Round
		 */
		public function getRound() {
			return $this->round;
		}
		public function setRound($round) {
			$this->round = $round;
		}
		
		/**
		 * Status
		 */
		public function getStatus() {
			return $this->status;
		}
		public function setStatus($status) {
			$this->status = $status;
		}
		
		/**
		 * Module
		 */
		public function getModule() {
			return $this->module;
		}
		public function setModule($module) {
			$this->module = $module;
		}
		
		/**
		 * RoomType
		 */
		public function getRoomType() {
			return $this->roomType;
		}
		public function setRoomType($roomType) {
			$this->roomType = $roomType;
		}
		
		/**
		 * Rooms
		 */
		public function getRooms() {
			return $this->rooms;
		}
		public function addRoom($room) {
			array_push($this->rooms, $room);
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
			return $this->numRooms;
		}
		public function setNumRooms($numRooms) {
			$this->numRooms = $numRooms;
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
		 * Facilties
		 */
		public function getFacilities() {
			return $this->facilities;
		}
		public function setFacilities($facilities) {
			$this->facilities = $facilities;
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
