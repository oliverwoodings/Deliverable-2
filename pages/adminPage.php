<?php 

	class admin {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			if (isset($parent->get)) {
				
				switch($parent->get){
					case "update":
						$results = $parent->db->getRequests();
						$requests = array();
						foreach($results as $request){
							if($request->getStatus()->getId() == $parent->db->getStatusByName("pending")->getId()){
								$requests[] = $request;
							}
						}
						$returnData = array();
						$i =0;
						foreach($requests as $request){
							$returnData[$i]['requestId'] = $request->getId();
								$returnData[$i]['priority'] = $request->getPriority();
								$returnData[$i]['module_code'] = $request->getModule()->getCode();
								$returnData[$i]['module_title'] = $request->getModule()->getName();
								$returnData[$i]['day'] = $request->getDay();
								$returnData[$i]['time'] = $request->getPeriod();
								$returnData[$i]['length'] = $request->getLength();
								$returnData[$i]['numStudents'] = $request->getNumStudents();
								$returnData[$i]['numRooms'] = $request->getNumRooms();
								$returnData[$i]['roomPrefs'] = array();
								//if(count($request->getRooms())==0) $returnData[$i]['roomPrefs'] = NULL;
								foreach($request->getRooms() as $room){
									array_push($returnData[$i]['roomPrefs'],$room->getCode());	
								}
								$park = $request->getPark();
								if(isset($park)){
									$returnData[$i]['park'] = $request->getPark()->getName();
								}else {
									$returnData[$i]['park'] = "";
								}
								$roomType = $request->getRoomType();
								if(isset($roomType)){
									$returnData[$i]['roomtype'] = $request->getRoomType()->getName();	
								}else{
									$returnData[$i]['roomtype'] = "";
								}
							$i++;
						}
						
						echo json_encode($returnData);
						
						break;
					case "fail":
						if(isset($_GET['id'])){
							$request = $parent->db->getRequestById($_GET['id']);
							$request->setStatus($parent->db->getStatusByName('failed'));
							$parent->db->saveRequest($request);
							//update history
							$parent->db->addHistory("Request Failed", $request->getModule()->getCode() . ", " . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . ", Period " . $request->getPeriod());
						}else{
							header("HTTP/1.0 500 Internal Server Error");
        					header('Content-Type: application/json');
        					die('ERROR');
						}
						
						break;
					case "allocate":
						if(isset($_GET['id']) && isset($_GET['rooms']) && isset($_GET['period'])){
							$rooms = json_decode($_GET['rooms']);
							$request = $parent->db->getRequestById($_GET['id']);
							if((int) $request->getNumRooms() !== count($_GET['rooms'])){
								$request->setStatus($parent->db->getStatusByName('reallocated'));
							}else{
								$room_codes = array();
								foreach($request->getRooms() as $room){
									$room_codes[] = $room->getCode();
								}
								$count = 0;
								for($out=0;$out<count($room_codes);$out++){
									for($in=0;$in<count($rooms);$in++){
										if($room_codes[$out] == $rooms[$in]) $count++;
									}
								}
								//if arrays are the same then allocated
								if($count == count($rooms)){
									$request->setStatus($parent->db->getStatusByName('allocated'));
								}else{
									$request->setStatus($parent->db->getStatusByName('reallocated'));
								}
							}
							$parent->db->saveRequest($request);
							foreach($rooms as $roomcode){
								$allocation = new Allocation($parent->db->getRequestById($_GET['id']),$parent->db->getRoomByCode($roomcode),$_GET['period']);
								$parent->db->addAllocation($allocation);
							}
							
							$parent->db->addHistory("Allocated Request", $request->getModule()->getCode() . ", " . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . ", Period " . $request->getPeriod());
						}else{
							header("HTTP/1.0 500 Internal Server Error");
        					header('Content-Type: application/json');
        					die('ERROR');
						}
						break;
				}
                
                return;
			}
			
			$parent->title = "Central Admin";
			$parent->displayHeader();
			
			?>

	<!--Button which initiates next round and next semester-->
			<div id="round_num_display">
				<div id="round_div">
					<button id="next_round"><h1>GO TO NEXT ROUND</h1></button>
				</div>
				<div id="semester_div">
					<button id="next_semester"><h1>GO TO NEXT SEMESTER</h1></button>
				</div>
			</div>
			
			<!--Div which Displays Next Round-->
			<div id="round_display">
				<h1 id="round" class="und_line">Current Round: <?php echo $parent->db->getActiveRound()->getName(); ?></h1>
			</div>
			
	<!--Create table--> 
		<table class="head">
			<thead class="header">
				<th>Round</th>
				<th>Module Code</th>
				<th>Module Title</th>
				<th class="day">Day</th>
				<th>Time</th>
				<th>Length</th>
				<th>Num Of Students</th>
				<th>Num Of Rooms</th>
				<th>Room Preference</th>
				<th>Room Allocated</th>
				<th></th>
			</thead>
			<tbody id="tbl_body">
				
			</tbody>
		</table>
		<?php
			$parent->displayFooter();
			
		}
		
	}

?>