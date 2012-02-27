<?php

	//Database class
	class Db {
		
		private $config;
        private $parent;
        private $buildings;
        private $roomTypes;
        private $parks;
        private $facilities;
        private $departments;
        private $statuses;
		private $rounds;
		
		function __construct($parent) {
            $this->parent = $parent;
			$this->config = $parent->config;
			if (!$this->config->database["enable"]) return;
			mysql_connect($this->config->database["host"], $this->config->database["user"], $this->config->database["pass"]) or die("Unable to connect to SQL Database!");
			mysql_select_db($this->config->database["db"]) or die("Unable to select database!");
			$this->createTables();
            
            //Pre-load data;
            $this->getRoomTypes();
            $this->getParks();
            $this->getBuildings();
            $this->getFacilities();
            $this->getDepartments();
			$this->getStatuses();
		}
		
		public function addAllocation($allocation) {
			$this->query("INSERT INTO `allocation` (request_id, room_id, period) VALUES('%s', '%s', '%s')", $allocation->getRequest()->getId(), $allocation->getRoom()->getId(), $allocation->getPeriod());
		}
		
		public function declineAllocation($id) {
			$res = $this->query("UPDATE `request` SET status_id='%s' WHERE request_id = '%s'", $this->getStatusByName("Declined")->getId(), $id);
		}
		
		public function deleteRequest($id) {
			$this->query("DELETE FROM `request` WHERE request_id = '%s'", $id);
			$this->query("DELETE FROM `requested_week` WHERE request_id = '%s'", $id);
			$this->query("DELETE FROM `room_request` WHERE request_id = '%s'", $id);
		}
		
		public function getUrgentRequests($limit = 10) {
			$urgent = array();
			foreach ($this->getRequests() as $request) {
				$rms = array();
				foreach ($request->getRooms() as $room) $rms[] = $room->getId();
				if ($request->getStatus()->getId() == $this->getStatusByName("Failed")->getId()) array_unshift($urgent, $request);
				else if ($request->getStatus()->getId() == $this->getStatusByName("Reallocated")->getId()) array_push($urgent, $request);
			}
			return array_slice($urgent, 0, $limit);
		}
		
		public function getHistory($limit = 0) {
			$res = $this->query("SELECT * FROM `history` ORDER BY history_id DESC" . ($limit > 0?"LIMIT " . $limit:""));
			$history = array();
			while ($row = mysql_fetch_assoc($res)) {
				$history[] = $row;
			}
			return $history;
		}
		
		public function addHistory($action, $msg) {
			$this->query("INSERT INTO `history` (action, msg) VALUES ('%s', '%s')", $action, $msg);
		}
		
		public function saveRequest($request) {
			$id = null;
			//Updating old
			if ($request->getId() != null) {
				$id = $request->getId();
				$sql = "UPDATE `request` SET round_id='%s', status_id='%s', module_id='%s', roomtype_id='%s', park_id='%s', period='%s', day='%s', length='%s', num_rooms='%s', num_students='%s', priority=%s, spec_req='%s' WHERE request_id = '%s'";
				$this->query($sql, $request->getRound()->getId(), $request->getStatus()->getId(), $request->getModule()->getId(),
							 ($request->getRoomType() == null?"":$request->getRoomType()->getId()),
							 ($request->getPark() == null?"":$request->getPark()->getId()), $request->getPeriod(), $request->getDay(),
							 $request->getLength(), $request->getNumRooms(), $request->getNumStudents(), $request->getPriority(), $request->getSpecReq(), $request->getId());
				$this->query("DELETE FROM `requested_week` WHERE request_id = '%s'", $id);
				$this->query("DELETE FROM `room_request` WHERE request_id = '%s'", $id);
				$this->query("DELETE FROM `request_facility` WHERE request_id = '%s'", $id);
			}
			//Inserting new
			else {
				$sql = "INSERT INTO `request` (round_id, status_id, module_id, roomtype_id, park_id, period, day, length, num_rooms, num_students, priority, spec_req)
						VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, '%s')";
				$type = $request->getRoomType() == null?"":$request->getRoomType()->getId();
				$park = $request->getPark() == null?"":$request->getPark()->getId();
				$spec = $request->getSpecReq() == null?"":$request->getSpecReq();
				$this->query($sql, $request->getRound()->getId(), $request->getStatus()->getId(), $request->getModule()->getId(),
							 $type, $park, $request->getPeriod(), $request->getDay(),
							 $request->getLength(), $request->getNumRooms(), $request->getNumStudents(), $request->getPriority(), $spec);
				$id = mysql_insert_id();
			}
			//Update request, facility and week
			foreach ($request->getFacilities() as $facility) {
				$this->query("INSERT INTO `request_facility` (request_id, facility_id) VALUES ('%s', '%s')", $id, $facility->getId());
			}
			foreach ($request->getWeeks() as $key => $week) {
				if ($week) $this->query("INSERT INTO `requested_week` (request_id, week) VALUES ('%s', '%s')", $id, $key);
			}
			foreach ($request->getRooms() as $room) {
				$this->query("INSERT INTO `room_request` (request_id, room_id) VALUES ('%s','%s')", $id, $room->getId());
			}
			return $id;
		}
		
		public function getRounds() {
			if (isset($this->rounds)) return $this->rounds;
			else {
				$res = $this->query("SELECT * FROM `round`");
				$rounds = array();
				while ($row = mysql_fetch_assoc($res)) {
					$rounds[$row["round_id"]] = new Round($row["round_id"], $row["round"], $row["start_date"], $row["end_date"], str_replace(array(0, 1), array(false, true), $row["active"]), $row["semester"]);
				}
				$this->rounds = $rounds;
				return $rounds;
			}
		}
		
		public function getActiveRound() {
			if (!isset($this->rounds)) $this->getRounds();
			foreach ($this->rounds as $round) {
				if ($round->getActive() && $round->getSemester() == $this->parent->auth->getUserSemester()) return $round;
			}
		}
        
        public function getStatuses() {
            if (isset($this->statuses)) return $this->statuses;
            else {
                $res = $this->query("SELECT * FROM `status`");
                $statuses = array();
                while ($row = mysql_fetch_assoc($res)) {
                    $statuses[$row["status_id"]] = new Entity($row["status_id"], $row["status_name"]);
                }
                $this->statuses = $statuses;
                return $statuses;
            }
        }
        
        public function getBuildings() {
            if (isset($this->buildings)) return $this->buildings;
            else {
                $res = $this->query("SELECT * FROM `building`");
                $buildings = array();
                while ($row = mysql_fetch_assoc($res)) {
                   $buildings[$row["building_id"]] = new Building($row["building_id"], $row["building_name"], $row["building_code"], $this->getParkById($row["park_id"]));
                }
                $this->buildings = $buildings;
                return $buildings;
            }
        }
		
		public function getRooms() {
            
            //Get list of room_facilities
            $rmFacilities = array();
            $res = $this->query("SELECT * FROM `room_facility`");
            while ($row = mysql_fetch_assoc($res)) $rmFacilities[] = $row;
            
            //Get rooms
            $res = $this->query("SELECT * FROM `room`");
            $rooms = array();
            while ($row = mysql_fetch_assoc($res)) {
                //Get room facilities
                $facs = array();
                foreach ($rmFacilities as $rmFac) {
                    if ($rmFac["room_id"] == $row["room_id"]) $facs[] = $this->getFacilityById($rmFac["facility_id"]);
                }
                //Get room
                $rooms[] = new Room($row["room_id"], $row["room_code"], $this->getBuildingById($row["building_id"]), $this->getRoomTypeById($row["room_type_id"]), $facs, $row["capacity"]);
            }
            return $rooms;
            
		}
		
		public function getFacilities() {
            if (isset($this->facilities)) return $this->facilities;
            else {
                $facilities = array();
                $res = $this->query("SELECT * FROM `facility`");
                while ($row = mysql_fetch_assoc($res)) $facilities[$row["facility_id"]] = new Entity($row["facility_id"], $row["facility_name"]);
                $this->facilities = $facilities;
                return $facilities;
            }
		}
		
		public function getAllocations(){
			$res = $this->query("SELECT * FROM `allocation`");
			$allocArr = array();
			while ($row = mysql_fetch_assoc($res)) {
				$allocation = new Allocation($this->getRequestById($row["request_id"]), $this->getRoomById($row["room_id"]), $row["period"]);
				if ($allocation->getRequest()->getRound()->getSemester() != $this->parent->auth->getUserSemester()) continue;
				$allocArr[] = $allocation;
			}
			return $allocArr;
		}
		
		public function getAllocationsByRoomId($id) {
			$res = $this->query("SELECT * FROM `allocation` WHERE room_id = '%s'", $id);
			$allocArr = array();
			while ($row = mysql_fetch_assoc($res)) {
				$allocation = new Allocation($this->getRequestById($row["request_id"]), $this->getRoomById($row["room_id"]), $row["period"]);
				if ($allocation->getRequest()->getRound()->getSemester() != $this->parent->auth->getUserSemester()) continue;
				$allocArr[] = $allocation;
			}
			return $allocArr;
		}
        
		public function getAllocationsByRequestId($id) {
			$res = $this->query("SELECT * FROM `allocation` WHERE request_id = '%s'", $id);
			$allocArr = array();
			while ($row = mysql_fetch_assoc($res)) {
				$allocation = new Allocation($this->getRequestById($row["request_id"]), $this->getRoomById($row["room_id"]), $row["period"]);
				if ($allocation->getRequest()->getRound()->getSemester() != $this->parent->auth->getUserSemester()) continue;
				$allocArr[] = $allocation;
			}
			return $allocArr;
		}
		
		public function getAllocationsByModuleId($id) {
			$res = $this->query("SELECT allocation.request_id,allocation.period,allocation.room_id FROM `allocation`,`request` WHERE allocation.request_id = request.request_id AND request.module_id = '%s'", $id);
			$allocArr = array();
			while ($row = mysql_fetch_assoc($res)) {
				$allocation = new Allocation($this->getRequestById($row["request_id"]), $this->getRoomById($row["room_id"]), $row["period"]);
				if ($allocation->getRequest()->getRound()->getSemester() != $this->parent->auth->getUserSemester()) continue;
				$allocArr[] = $allocation;
			}
			return $allocArr;
		}
		
		public function getRoundById($id) {
			if (!isset($this->rounds)) $this->getRounds();
			foreach ($this->getRounds() as $round) {
				if ($round->getId() == $id) return $round;
			}
		}
		
		public function getStatusById($id) {
			return $this->statuses[$id];
		}
		
		public function getRoomTypeById($id) {
			return $this->roomTypes[$id];
		}
		
		public function getRequestWeeks($id) {
			$res = $this->query("SELECT * FROM `requested_week` WHERE request_id = '%s'", $id);
			$weeks = array_fill(0, 15, false);
			while ($row = mysql_fetch_assoc($res)) $weeks[$row["week"]] = true;
			return $weeks;
		}
        
        public function getRequests() {
            $res = $this->query("SELECT * FROM `request`,`module` WHERE request.module_id = module.module_id AND module.department_id = '%s'", $this->parent->auth->getUserId());
            $requests = array();
			while ($row = mysql_fetch_assoc($res)) {
                $request = new Request();
                $request->setId($row["request_id"]);
                $request->setRound($this->getRoundById($row["round_id"]));
				if ($request->getRound()->getSemester() != $this->parent->auth->getUserSemester()) continue;
                $request->setStatus($this->getStatusById($row["status_id"]));
                $request->setModule($this->getModuleById($row["module_id"]));
                if ($row["roomtype_id"] > 0) $request->setRoomType($this->getRoomTypeById($row["roomtype_id"]));
                $request->setRooms($this->getRoomsByRequestId($row["request_id"]));
                if ($row["park_id"] > 0) $request->setPark($this->getParkById($row["park_id"]));
                $request->setPeriod($row["period"]);
                $request->setDay($row["day"]);
                $request->setWeeks($this->getRequestWeeks($row["request_id"]));
                $request->setLength($row["length"]);
                if (strlen($row["num_students"]) > 0) $request->setNumStudents($row["num_students"]);
				$request->setNumRooms($row['num_rooms']);
                $request->setPriority(str_replace(array(0,1), array(false,true), $row["priority"]));
                $request->setSpecReq($row["spec_req"]);
				//Get request facilities
				$res2 = $this->query("SELECT * FROM `request_facility` WHERE request_id = '%s'", $row["request_id"]);
				$facs = array();
				while ($row2 = mysql_fetch_assoc($res2)) $facs[] = $this->getFacilityById($row2["facility_id"]);
				if (count($facs) > 0) $request->setFacilities($facs);
				$requests[] = $request;
			}
            return $requests;
        }
		
        public function getPendingRequestsByModuleId($id) {
            $res = $this->query("SELECT * FROM `request`,`module` WHERE request.module_id = '%s' AND request.module_id = module.module_id AND module.department_id = '%s' AND request.status_id = '%s'", $id, $this->parent->auth->getUserId(), $this->getStatusByName("Pending")->getId());
            $requests = array();
			while ($row = mysql_fetch_assoc($res)) {
                $request = new Request();
                $request->setId($row["request_id"]);
                $request->setRound($this->getRoundById($row["round_id"]));
				if ($request->getRound()->getSemester() != $this->parent->auth->getUserSemester()) continue;
                $request->setStatus($this->getStatusById($row["status_id"]));
                $request->setModule($this->getModuleById($row["module_id"]));
                if ($row["roomtype_id"] > 0) $request->setRoomType($this->getRoomTypeById($row["roomtype_id"]));
                $request->setRooms($this->getRoomsByRequestId($row["request_id"]));
                if ($row["park_id"] > 0) $request->setPark($this->getParkById($row["park_id"]));
                $request->setPeriod($row["period"]);
                $request->setDay($row["day"]);
                $request->setWeeks($this->getRequestWeeks($row["request_id"]));
                $request->setLength($row["length"]);
                if (strlen($row["num_students"]) > 0) $request->setNumStudents($row["num_students"]);
				$request->setNumRooms($row['num_rooms']);
                $request->setPriority(str_replace(array(0,1), array(false,true), $row["priority"]));
                $request->setSpecReq($row["spec_req"]);
				//Get request facilities
				$res2 = $this->query("SELECT * FROM `request_facility` WHERE request_id = '%s'", $row["request_id"]);
				$facs = array();
				while ($row2 = mysql_fetch_assoc($res2)) $facs[] = $this->getFacilityById($row2["facility_id"]);
				if (count($facs) > 0) $request->setFacilities($facs);
				$requests[] = $request;
			}
            return $requests;
        }
		
		public function getRequestById($id) {
			$res = $this->query("SELECT * FROM `request` WHERE request_id = '%s'", $id);
			$row = mysql_fetch_assoc($res);
			if (!$row) return false;
			$request = new Request();
			$request->setId($row["request_id"]);
			$request->setRound($this->getRoundById($row["round_id"]));
			$request->setStatus($this->getStatusById($row["status_id"]));
			$request->setModule($this->getModuleById($row["module_id"]));
			if ($row["roomtype_id"] > 0) $request->setRoomType($this->getRoomTypeById($row["roomtype_id"]));
			$request->setRooms($this->getRoomsByRequestId($id));
			if ($row["park_id"] > 0) $request->setPark($this->getParkById($row["park_id"]));
			$request->setPeriod($row["period"]);
			$request->setDay($row["day"]);
			$request->setWeeks($this->getRequestWeeks($id));
			$request->setLength($row["length"]);
			if (strlen($row["num_students"]) > 0) $request->setNumStudents($row["num_students"]);
			$request->setNumRooms($row['num_rooms']);
			$request->setPriority(str_replace(array(0,1), array(false,true), $row["priority"]));
			if (strlen($row["spec_req"]) > 0) $request->setSpecReq($row["spec_req"]);
			//Get request facilities
			$res2 = $this->query("SELECT * FROM `request_facility` WHERE request_id = '%s'", $row["request_id"]);
			$facs = array();
			while ($row2 = mysql_fetch_assoc($res2)) $facs[] = $this->getFacilityById($row2["facility_id"]);
			if (count($facs) > 0) $request->setFacilities($facs);
			return $request;
		}
		
		public function getRoomsByRequestId($id) {
			$res = $this->query("SELECT * FROM `room_request` WHERE request_id = '%s'", $id);
			$rooms = array();
			while ($row = mysql_fetch_assoc($res)) {
				$rooms[] = $this->getRoomById($row["room_id"]);
			}
			return $rooms;
		}
		
		public function getParkById($id) {
			return $this->parks[$id];
		}
		
		public function getFacilityById($id) {
			return $this->facilities[$id];
		}
		
		public function getBuildingById($id) {
			return $this->buildings[$id];
		}
		
		public function getRoomById($id) {
			//Get room facilities
			$res = $this->query("SELECT * FROM `room_facility` WHERE room_id = '%s'", $id);
			$facs = array();
			while ($row = mysql_fetch_assoc($res)) $facs[] = $this->getFacilityById($row["facility_id"]);
			
			//Get room
			$res = $this->query("SELECT * FROM `room` WHERE room_id = '%s'", $id);
			$row = mysql_fetch_assoc($res);
			if (!$row) return false;
			return new Room($row["room_id"], $row["room_code"], $this->getBuildingById($row["building_id"]), $this->getRoomTypeById($row["room_type_id"]), $facs, $row["capacity"]);
		}
		
		public function getRoomByCode($code) {
			//Get room
			$res = $this->query("SELECT * FROM `room` WHERE room_code = '%s'", $code);
			$row = mysql_fetch_assoc($res);
			if (!$row) return false;
			
			//Get room facilities
			$res = $this->query("SELECT * FROM `room_facility` WHERE room_id = '%s'", $row["room_id"]);
			$facs = array();
			while ($row2 = mysql_fetch_assoc($res)) $facs[] = $this->getFacilityById($row2["facility_id"]);

			return new Room($row["room_id"], $row["room_code"], $this->getBuildingById($row["building_id"]), $this->getRoomTypeById($row["room_type_id"]), $facs, $row["capacity"]);
		}
		
		/**
		 * Delete a module by its code
		 */
		public function deleteModuleByCode($code) {
			$module = $this->getModuleByCode($code);
			$this->query("DELETE FROM `module` WHERE module_id = '%s'", $module->getId());
			$this->query("DELETE FROM `module_lecturer` WHERE module_id = '%s'", $module->getId());
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
			$res = $this->query("SELECT * FROM `status` WHERE LOWER(`status_name`) = LOWER('%s')", $name);
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
			$res = $this->query("SELECT * FROM `module` WHERE department_id = '%s'", $this->parent->auth->getUserId());
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
			return $this->departments[$id];
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
            if (isset($this->departments)) return $this->departments;
            else {
                $res = $this->query("SELECT * FROM `department`");
                $departments = array();
                while ($row = mysql_fetch_assoc($res)) {
                    $departments[$row["department_id"]] = new Entity($row["department_id"], $row["department_name"]);
                }
                $this->departments = $departments;
                return $departments;
            }
		}
		
		/**
		 * Returns entity list of parks
		 */  
		public function getRoomTypes() {
            if (isset($this->roomTypes)) return $this->roomTypes;
            else {
                $res = $this->query("SELECT * FROM `room_type`");
                $types = array();
                while($row = mysql_fetch_assoc($res)) {
                    $type = new Entity($row["type_id"], $row["type"]);
                    $types[$row["type_id"]] = $type;
                }
                $this->roomTypes = $types;
                return $types;
            }
		}
		
		/**
		 * Returns entity list of parks
		 */  
		public function getParks() {
            if (isset($this->parks)) return $this->parks;
            else {
                $res = $this->query("SELECT * FROM `park`");
                $parks = array();
                while($row = mysql_fetch_assoc($res)) {
                    $park = new Entity($row["park_id"], $row["park_name"]);
                    $parks[$row["park_id"]] = $park;
                }
                $this->parks = $parks;
                return $parks;
            }
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
			if (mysql_error() != "") {
				echo "<b>Failed SQL:</b> " . vsprintf($sql, $args) . "<br />";
				throw new Exception(mysql_error());
			}
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
		public function clean($string) {
			return mysql_real_escape_string(trim($string));
		}
		
		
	}
	
?>