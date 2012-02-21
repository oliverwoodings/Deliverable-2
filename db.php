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
		 * Delete a module by its code
		 */
		public function deleteModuleByCode($code) {
			$sql = "DELETE FROM `module` WHERE LOWER(module_code) = LOWER('%s')";
			$this->query($sql, $code);
		}
		
		/**
		 * Save a module object
		 */
		public function saveModule($module) {
			//If module doesn't exist in the database yet
			if (strlen($module->getId()) < 1) {
				$sql = "INSERT INTO `module` (module_name, module_code, department_id) VALUES ('%s', '%s', '%s')";
				$this->query($sql, $module->getName(), $module->getCode(), $module->getDepartment()->getId());
				$module->setId(mysql_insert_id());
			} 
			//Else remove update and remove lecturer links
			else {
				$sql = "DELETE FROM `module_lecturer` WHERE module_id = '%s'";
				$this->query($sql, $module->getId());
				$sql = "UPDATE `module` SET module_name = '%s', module_code = '%s', department_id = '%s' WHERE module_id = '%s'";
				$this->query($sql, $module->getName(), $module->getCode(), $module->getDepartment()->getId(), $module->getId());
			}
			
			//Re-add updated lecturer links
			foreach ($module->getLecturers() as $lecturer) {
				$sql = "INSERT INTO `module_lecturer` (module_id, lecturer_id) VALUES('%s', '%s')";
				$this->query($sql, $module->getId(), $lecturer->getId());
			}
		}
		
		/**
		 * Returns status by its name
		 */
		public function getStatusByName($name) {
			$res = $this->query("SELECT * FROM `status` WHERE `status_name` = '%s'", $name);
			$row = mysql_fetch_assoc($res);
			if ($row == false) return false;
			return new Entity($row["status_id"], $row["status_name"]);
		}
        
        /**
         * Gets a module by its code
         */
        public function getModuleByCode($code) {
            $res = $this->query("SELECT * FROM `module` WHERE `module_code` = '%s'", $code);
            $row = mysql_fetch_assoc($res);
            if ($row == false) return false;
            return new Module($row["module_id"], $row["module_name"], $this->getDepartmentById($row["department_id"]), $row["module_code"], $this->getLecturersByModuleId($row["module_id"]));
        }
        
        /**
         * Gets a module by its id
         */
        public function getModuleById($id) {
            $res = $this->query("SELECT * FROM `module` WHERE `module_id` = '%s'", $id);
            $row = mysql_fetch_assoc($res);
            if ($row == false) return false;
            return new Module($row["module_id"], $row["module_name"], $this->getDepartmentById($row["department_id"]), $row["module_code"], $this->getLecturersByModuleId($row["module_id"]));
        }
		
        /**
         * Returns a list of all modules
         */
		public function getModules() {
			$res = $this->query("SELECT * FROM `module`");
			$modules = array();
			while($row = mysql_fetch_assoc($res)) {
				$module = new Module($row["module_id"], $row["module_name"], $this->getDepartmentById($row["department_id"]), $row["module_code"], $this->getLecturersByModuleId($row["module_id"]));
				array_push($modules, $module);
			}
			return $modules;
		}
		
        /**
         * Gets a list of all lecturers assigned to a module
         */
		public function getLecturersByModuleId($id) {
			$res = $this->query("SELECT * FROM `lecturer` WHERE `lecturer_id` IN (SELECT `lecturer_id` FROM `module_lecturer` WHERE module_id = '%s')", $id);
            $lecturers = array();
            while ($row = mysql_fetch_assoc($res)) {
                $lecturer = new Entity($row["lecturer_id"], $row["lecturer_name"]);
                array_push($lecturers, $lecturer);
            }
            return $lecturers;
		}
        
        /**
         * Returns a list of lecturers
         */
        public function getLecturers() {
        	$res = $this->query("SELECT * FROM `lecturer`");
            $lecturers = array();
            while ($row = mysql_fetch_assoc($res)) {
                $lecturer = new Entity($row["lecturer_id"], $row["lecturer_name"]);
                array_push($lecturers, $lecturer);
            }
            return $lecturers;
        }
        
        /**
         * Returns a single lecturer
         */
        public function getLecturerById($id) {
			$res = $this->query("SELECT * FROM `lecturer` WHERE lecturer_id = '%s'", $id);
            $row = mysql_fetch_assoc($res);
            if ($row == false) return false;
            return new Entity($row["lecturer_id"], $row["lecturer_name"]);
		}
        
        /**
         * Returns a single department
         */
		public function getDepartmentById($id) {
			$res = $this->query("SELECT * FROM `department` WHERE department_id = '%s'", $id);
            $row = mysql_fetch_assoc($res);
            if ($row == false) return false;
            return new Entity($row["department_id"], $row["department_name"]);
		}
		
        /**
         * Returns a single department
         */
		public function getDepartmentByName($name) {
			$res = $this->query("SELECT * FROM `department` WHERE LOWER(department_name) = LOWER('%s')", $name);
            $row = mysql_fetch_assoc($res);
            if ($row == false) return false;
            return new Entity($row["department_id"], $row["department_name"]);
		}
        
        /**
		 * Returns list of department names
		 */
		public function getDepartments() {
			$res = $this->query("SELECT department_name FROM `department`");
			$departments = array();
			while ($row = mysql_fetch_assoc($res)) {
				array_push($departments, $row["department_name"]);
			}
			return $departments;
		}
		
		/**
		 * Returns entity list of parks
		 */  
		public function getRoomTypes() {
			$res = $this->query("SELECT * FROM `room_type`");
			$types = array();
			while($row = mysql_fetch_assoc($res)) {
				$type = new Entity($row["type_id"], $row["type"]);
				array_push($types, $type);
			}
			return $types;
		}
		
		/**
		 * Returns entity list of parks
		 */  
		public function getParks() {
			$res = $this->query("SELECT * FROM `park`");
			$parks = array();
			while($row = mysql_fetch_assoc($res)) {
				$park = new Entity($row["park_id"], $row["park_name"]);
				array_push($parks, $park);
			}
			return $parks;
		}
		
		/**
		 * Check login details
		 */
		public function checkLoginDetails($username, $password) {
			$res = $this->query("SELECT * FROM `department` WHERE LOWER(department_name) = LOWER('%s')", $username);
			$row = mysql_fetch_assoc($res);
			if ($row == false || sha1($password) !== $row["password"]) return false;
			return true;
		}
				
		/**
		 * Base MySQL query function. Cleans all parameters to prevent injection
		 */
		public function query() {
			$args = func_get_args();
			$sql = array_shift($args);
			if (count($args) > 0) {
				foreach ($args as $key => $value)
					$args[$key] = $this->clean($value);
				$res = mysql_query(vsprintf($sql, $args));
			} else {
				$res = mysql_query($sql);
			}
			//Error handling
			if (mysql_error() != "") die(mysql_error());
			return $res;
		}
		
		/**
		 * Creates default tables if they don't exist
		 */
		private function createTables() {
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `allocation` (
				  `request_id` int(11) NOT NULL,
				  `room_id` int(11) NOT NULL,
				  `period` int(11) NOT NULL,
				  `declined` tinyint(1) NOT NULL,
				  PRIMARY KEY (`request_id`,`room_id`,`period`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `building` (
				  `building_id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_name` varchar(50) NOT NULL UNIQUE,
                  `building_code` varchar(10) NOT NULL UNIQUE,
				  `park_id` int(11) NOT NULL,
				  PRIMARY KEY (`building_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `department` (
				  `department_id` int(11) NOT NULL AUTO_INCREMENT,
				  `department_name` varchar(70) NOT NULL UNIQUE,
				  `password` varchar(150) NOT NULL,
				  PRIMARY KEY (`department_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `facility` (
				  `facility_id` int(11) NOT NULL AUTO_INCREMENT,
				  `facility_name` varchar(50) NOT NULL UNIQUE,
				  PRIMARY KEY (`facility_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `lecturer` (
				  `lecturer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `lecturer_name` varchar(70) NOT NULL UNIQUE,
				  PRIMARY KEY (`lecturer_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `module` (
				  `module_id` int(11) NOT NULL AUTO_INCREMENT,
				  `module_code` varchar(100) NOT NULL UNIQUE,
				  `module_name` varchar(100) NOT NULL UNIQUE,
				  `department_id` int(11) NOT NULL,
				  PRIMARY KEY (`module_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `module_lecturer` (
				  `module_id` int(11) NOT NULL,
				  `lecturer_id` int(11) NOT NULL,
				  PRIMARY KEY (`module_id`,`lecturer_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `park` (
				  `park_id` int(11) NOT NULL AUTO_INCREMENT,
				  `park_name` varchar(50) NOT NULL,
				  PRIMARY KEY (`park_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
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
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `requested_week` (
				  `request_id` int(11) NOT NULL,
				  `week` int(2) NOT NULL,
				  PRIMARY KEY (`request_id`,`week`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `request_facility` (
				  `request_id` int(11) NOT NULL,
				  `facility_id` int(11) NOT NULL,
				  PRIMARY KEY (`request_id`,`facility_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `room` (
				  `room_id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `room_code` varchar(20) NOT NULL,
                  `capacity` int(11) NOT NULL,
				  PRIMARY KEY (`room_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
			
			$this->query("
				CREATE TABLE IF NOT EXISTS `room_facility` (
				  `room_id` int(11) NOT NULL,
				  `facility_id` int(11) NOT NULL,
				  PRIMARY KEY (`room_id`,`facility_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `room_request` (
				  `booking_id` int(11) NOT NULL,
				  `room_id` int(11) NOT NULL,
				  PRIMARY KEY (`booking_id`,`room_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `room_type` (
				  `type_id` int(11) NOT NULL AUTO_INCREMENT,
				  `type` varchar(50) NOT NULL,
				  PRIMARY KEY (`type_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `round` (
				  `round_id` int(11) NOT NULL AUTO_INCREMENT,
				  `round` varchar(1) NOT NULL,
				  `start_date` date NOT NULL,
				  `end_date` date NOT NULL,
				  `active` tinyint(1) NOT NULL,
				  PRIMARY KEY (`round_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
				
			$this->query("
				CREATE TABLE IF NOT EXISTS `status` (
				  `status_id` int(11) NOT NULL AUTO_INCREMENT,
				  `status_name` varchar(40) NOT NULL UNIQUE,
				  PRIMARY KEY (`status_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
			
		}
		
		/**
		 * Stops MySQL injection
		 */
		private function clean($string) {
			return mysql_real_escape_string(trim($string));
		}
		
		
	}
	
?>