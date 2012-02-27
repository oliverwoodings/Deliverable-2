<?php

	class Search {
		
		public $module;
		public $numStudents;
		public $facilities;
		public $park;
		public $rooms;
		public $length;
		public $roomType;
		public $weeks;
		public $day;
		public $period;
		public $numRooms;
		
		public function getAvailableRequestData($db) {
		
			/**
				3 affecting conditions:
				1. only care if at least one of the selected weeks matches
				2. all available rooms are booked at specific time
				3. all available modules are booked at specific time
				Reverse of all 3 also applies
			**/
			
			
			//Set up day-period array
			if (isset($this->period) && isset($this->day) && isset($this->length)) {
				$availableTime = array_fill(0, 5, array_fill(0, 9, false));
				for ($i = 0; $i < $this->length; $i++) $availableTime[$this->day -1][$this->period+$i-1] = true;
			}
			else $availableTime = array_fill(0, 5, array_fill(0, 9, true));
		
			//Calculate available rooms according to user selection
			$rms = $db->getRooms();
			foreach ($rms as $room) {
				if (isset($this->numStudents) && $this->numStudents > $room->getCapacity()) continue;
				if (isset($this->facilities)) {
					foreach ($this->facilities as $searchFac) {
						foreach ($room->getFacilities() as $rmFac) {
							if ($searchFac->getId() == $rmFac->getId()) continue 2;
						}
						continue 2;
					}
				}
				if (isset($this->park) && $room->getBuilding()->getPark()->getId() != $this->park->getId()) continue;
				if (isset($this->roomType) && $room->getType()->getId() != $this->roomType->getId()) continue;
				$roomArray[] = $room;
			}
           
			//Retrieve allocations with the same room
			$roomAllocations = array();
			foreach ($roomArray as $room) {
				$allocs = $db->getAllocationsByRoomId($room->getId());
				//1. Check if allocation has a matching week
                foreach ($allocs as $alloc) {
					if (strtolower($alloc->getRequest()->getStatus()->getName()) == "declined") continue;
                    foreach ($this->weeks as $week) {
						foreach ($alloc->getRequest()->getWeeks() as $wk) {
							if ($wk) {
								$roomAllocations[] = $alloc;
								continue 3;
							}
						}
                    }
                }
			}
			
			//Remove times that collide with selected rooms
			if (isset($this->rooms)) {
				foreach ($roomAllocations as $alloc) {
					foreach ($this->rooms as $room) {
						if ($room->getId() == $alloc->getRoom()->getId())
							$availableTime[$alloc->getDay() -1][$alloc->getPeriod() -1] = false;
					}
				}
			}
			
			//Calculate available modules according to user selection
			if (isset($this->module)) $moduleArray = array($this->module);
			else $moduleArray = $db->getModules();
            
			//Retrieve allocations with the same module
			$modRoomAllocations = array();
			foreach ($moduleArray as $module) {
				$allocs = $db->getAllocationsByModuleId($module->getId());
				//1. Check if allocation has a matching week
                foreach ($allocs as $alloc) {
                    foreach ($this->weeks as $week) {
						foreach ($alloc->getRequest()->getWeeks() as $wk) {
							if ($wk) {
								$modRoomAllocations[] = $alloc;
								continue 3;
							}
						}
                    }
                }
			}
			
			//Get pending requests with the same module
			$modRoomRequests = array();
			foreach ($moduleArray as $module) {
				$requests = $db->getPendingRequestsByModuleId($module->getId());
				//1. Check if allocation has a matching week
                foreach ($requests as $request) {
                    foreach ($this->weeks as $week) {
						foreach ($request->getWeeks() as $wk) {
							if ($wk) {
								$modRoomRequests[] = $request;
								continue 3;
							}
						}
                    }
                }
			}
			
			//Loop until time is no longer being adjusted
			$timeState= array();
			while ($timeState !== $availableTime) {

				//2. If a room is already booked for all available times, remove it
				$availableRooms = array();
				foreach ($roomArray as $room) {
					for ($i = 1; $i <= 5; $i++) {
						for ($j = 1; $j <= 9; $j++) {
							$match = false;
							foreach ($roomAllocations as $allocation) {
								if ($allocation->getRoom()->getId() == $room->getId() && $availableTime[$i-1][$j-1] && $allocation->getDay() == $i && $allocation->getPeriod() == $j)
									$match = true;
							}
							if (!$match) {
								$availableRooms[] = $room;
								continue 3;
							}
						}
					}
				}
				$roomArray = $availableRooms;
				
				//2. If all available rooms are booked at a specific time, remove that time
				//Loop days
				for ($i = 1; $i <= 5; $i++) {
					//Loop periods
					for ($j = 1; $j <= 9; $j++) {
						//Loop rooms
						foreach ($availableRooms as $room) {
							$rmBk = false;
							//Loop room allocs
							foreach ($roomAllocations as $alloc) {
								if ($alloc->getRoom()->getId() == $room->getId() && $alloc->getDay() == $i && $alloc->getPeriod() == $j) {
									$rmBk = true;
									break;
								}
							}
							if (!$rmBk) continue 2;
						}
						$availableTime[$i-1][$j-1] = false;
					}
				}
	
				
				//3. If a module is already booked for all available times, remove it
				$availableModules = array();
				foreach ($moduleArray as $module) {
					for ($i = 1; $i <= 5; $i++) {
						for ($j = 1; $j <= 9; $j++) {
							$match = false;
							foreach ($modRoomAllocations as $allocation) {
								if ($allocation->getRequest()->getModule()->getId() == $module->getId() && $availableTime[$i-1][$j-1] && $allocation->getDay() == $i && $allocation->getPeriod() == $j) {
									$match = true;
								}
							}
							if (!$match) {
								$availableModules[] = $module;
								continue 3;
							}
						}
					}
				}
				$moduleArray = $availableModules;
				
				//3. If all available modules are booked at a specific time, remove that time
				//Loop days
				for ($i = 1; $i <= 5; $i++) {
					//Loop periods
					for ($j = 1; $j <= 9; $j++) {
						//Loop rooms
						foreach ($availableModules as $module) {
							$rmBk = false;
							//Loop room allocs
							foreach ($modRoomAllocations as $alloc) {
								if ($alloc->getRequest()->getModule()->getId() == $module->getId() && $alloc->getDay() == $i && $alloc->getPeriod() == $j) {
									$rmBk = true;
									break;
								}
							}
							if (!$rmBk) continue 2;
						}
						$availableTime[$i-1][$j-1] = false;
					}
				}
				
				//3. If all available modules have pending requests as a specific time, remove that time
				//Loop days
				for ($i = 1; $i <= 5; $i++) {
					//Loop periods
					for ($j = 1; $j <= 9; $j++) {
						//Loop rooms
						foreach ($availableModules as $module) {
							$rmBk = false;
							//Loop room allocs
							foreach ($modRoomRequests as $request) {
								$prds = array();
								for ($k = 0; $k < $request->getLength(); $k++) $prds[] = $request->getPeriod() + $k;
								if ($request->getModule()->getId() == $module->getId() && $request->getDay() == $i && in_array($j, $prds)) {
									$rmBk = true;
									break;
								}
							}
							if (!$rmBk) continue 2;
						}
						$availableTime[$i-1][$j-1] = false;
					}
				}
				
				$timeState = $availableTime;
				
			}
			
			$returnData = array();
			$returnData["modules"] = $availableModules;
			$returnData["rooms"] = $availableRooms;
			$returnData["time"] = $availableTime;
            
            return $returnData;
			
		}

		public function getRequestsResponses($db,$types)
		{
			$results = array();
			$requests = $db->getRequests();
			
			for($i=0;$i < count($types); $i++){
				foreach($requests as $request){
					if($request->getStatus()->getId() == $types[$i])
						array_push($results,$request);
				}
			}
			
			return $results;
			
		}
	
	}

?>