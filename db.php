<?php

	//Database class
	class Db {
		
		private $config;
		
		function __construct($parent) {
			$this->config = $parent->config;
			if (!$this->config->database["enable"]) return;
			mysql_connect($this->config->database["host"], $this->config->database["user"], $this->config->database["pass"]) or die("Unable to connect to SQL Database!");
			mysql_select_db($this->config->database["db"]) or die("Unable to select database!");
			$this->createTables();
		}
		
		/**
		 * Creates default tables if they don't exist
		 */
		private function createTables() {
			
			$sql = "
				CREATE TABLE IF NOT EXISTS `allocation` (
				  `request_id` int(11) NOT NULL,
				  `room_id` int(11) NOT NULL,
				  `period` int(11) NOT NULL,
				  `declined` tinyint(1) NOT NULL,
				  PRIMARY KEY (`request_id`,`room_id`,`period`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				
				CREATE TABLE IF NOT EXISTS `building` (
				  `building_id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_name` varchar(50) NOT NULL,
				  `park_id` int(11) NOT NULL,
				  PRIMARY KEY (`building_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `department` (
				  `department_id` int(11) NOT NULL AUTO_INCREMENT,
				  `department_name` varchar(70) NOT NULL,
				  PRIMARY KEY (`department_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `facility` (
				  `facility_id` int(11) NOT NULL AUTO_INCREMENT,
				  `facility_name` varchar(50) NOT NULL,
				  PRIMARY KEY (`facility_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `lecturer` (
				  `lecturer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `lecturer_name` varchar(70) NOT NULL,
				  PRIMARY KEY (`lecturer_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `module` (
				  `module_id` int(11) NOT NULL AUTO_INCREMENT,
				  `module_name` varchar(100) NOT NULL,
				  `department_id` int(11) NOT NULL,
				  PRIMARY KEY (`module_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `module_lecturer` (
				  `module_id` int(11) NOT NULL,
				  `lecturer_id` int(11) NOT NULL,
				  PRIMARY KEY (`module_id`,`lecturer_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				
				CREATE TABLE IF NOT EXISTS `park` (
				  `park_id` int(11) NOT NULL AUTO_INCREMENT,
				  `park_name` varchar(50) NOT NULL,
				  PRIMARY KEY (`park_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `request` (
				  `request_id` int(11) NOT NULL AUTO_INCREMENT,
				  `round_id` int(11) NOT NULL,
				  `status_id` int(11) NOT NULL,
				  `module_id` int(11) NOT NULL,
				  `roomtype_id` int(11) NOT NULL,
				  `building_id` int(11) NOT NULL,
				  `park_id` int(11) NOT NULL,
				  `period` int(11) NOT NULL,
				  `day` int(11) NOT NULL,
				  `length` int(11) NOT NULL,
				  `num_rooms` int(11) NOT NULL,
				  `num_students` int(11) NOT NULL,
				  `priority` tinyint(1) NOT NULL,
				  `spec_req` varchar(400) NOT NULL,
				  PRIMARY KEY (`request_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `requested_week` (
				  `request_id` int(11) NOT NULL,
				  `week` int(2) NOT NULL,
				  PRIMARY KEY (`request_id`,`week`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				
				CREATE TABLE IF NOT EXISTS `request_facility` (
				  `request_id` int(11) NOT NULL,
				  `facility_id` int(11) NOT NULL,
				  PRIMARY KEY (`request_id`,`facility_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				
				CREATE TABLE IF NOT EXISTS `room` (
				  `room_id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `room_name` varchar(50) NOT NULL,
				  `room_code` varchar(20) NOT NULL,
				  PRIMARY KEY (`room_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `room_facility` (
				  `room_id` int(11) NOT NULL,
				  `facility_id` int(11) NOT NULL,
				  PRIMARY KEY (`room_id`,`facility_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				
				CREATE TABLE IF NOT EXISTS `room_request` (
				  `booking_id` int(11) NOT NULL,
				  `room_id` int(11) NOT NULL,
				  PRIMARY KEY (`booking_id`,`room_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				
				CREATE TABLE IF NOT EXISTS `room_type` (
				  `type_id` int(11) NOT NULL AUTO_INCREMENT,
				  `type` varchar(50) NOT NULL,
				  PRIMARY KEY (`type_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `round` (
				  `round_id` int(11) NOT NULL AUTO_INCREMENT,
				  `round` int(11) NOT NULL,
				  `start_date` date NOT NULL,
				  `end_date` date NOT NULL,
				  `active` tinyint(1) NOT NULL,
				  PRIMARY KEY (`round_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				
				CREATE TABLE IF NOT EXISTS `status` (
				  `status_id` int(11) NOT NULL AUTO_INCREMENT,
				  `status_name` varchar(40) NOT NULL,
				  PRIMARY KEY (`status_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				";
			
			$this->query($sql);
			
		}
		
		/**
		 * Base MySQL query function. Cleans all parameters to prevent injection
		 */
		function query() {
			$args = func_get_args();
			$sql = array_shift($args);
			foreach ($args as $key => $value)
				$args[$key] = $this->clean($value);
			return mysql_query(vsprintf($sql, $args));
		}
		/**
		 * Stops MySQL injection
		 */
		private function clean($string) {
			return mysql_real_escape_string(trim($string));
		}
		
		/**
		 * Check login details
		 */
		public function checkLoginDetails($username, $password) {
			return true;
		}
		
		
	}
	
?>