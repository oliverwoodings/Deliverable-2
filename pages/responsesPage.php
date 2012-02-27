<?php 

	/**
	 *	File: entities/responsesPage.php
	 *	Page: Responses
	 *  Author: Jono Brogan, Matthew Swift, James Wightman, Ben Faiers
	 *  Functionality: Shows the user their pending requests and allocated rooms
	 */

	class responses {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			if (isset($parent->get)) {
				
				switch($parent->get){
					case "update":
						$search = new Search();
						if(isset($_GET['types'])){
							$types = json_decode($_GET['types']);
						}
						if(count($types)> 0){
							//get request/responses
							$results = $search->getRequestsResponses($parent->db,$types);
							//print_r($results)
							//Format into JSON-parsable array
							$returnData = array();
							foreach($results as $key => $elm){
								$allocations = $parent->db->getAllocationsByRequestId($elm->getId());
								$returnData[$key]['requestId'] = $elm->getId();
								$returnData[$key]['priority'] = $elm->getPriority();
								$returnData[$key]['round'] = $elm->getRound()->getName();
								$returnData[$key]['module_code'] = $elm->getModule()->getCode();
								$returnData[$key]['module_title'] = $elm->getModule()->getName();
								$returnData[$key]['day'] = $elm->getDay();
								$returnData[$key]['time'] = $elm->getPeriod();
								$returnData[$key]['length'] = $elm->getLength();
								$returnData[$key]['numStudents'] = $elm->getNumStudents();
								$returnData[$key]['numRooms'] = $elm->getNumRooms();
								$returnData[$key]['roomPrefs'] = array();
								//if(count($elm->getRooms())==0) $returnData[$key]['roomPrefs'] = NULL;
								foreach($elm->getRooms() as $room){
									array_push($returnData[$key]['roomPrefs'],$room->getCode());	
								}
								//get room allocations
								$returnData[$key]['roomAllocations'] = array();
								foreach($allocations as $allocation){
									array_push($returnData[$key]['roomAllocations'],$allocation->getRoom()->getCode());
								}
								$park = $elm->getPark();
								if(isset($park)){
									$returnData[$key]['park'] = $elm->getPark()->getName();
								}else {
									$returnData[$key]['park'] = "No Preference";
								}
								$roomType = $elm->getRoomType();
								if(isset($roomType)){
									$returnData[$key]['roomtype'] = $elm->getRoomType()->getName();	
								}else{
									$returnData[$key]['roomtype'] = "No Preference";
								}
								$returnData[$key]['weeks'] = $elm->getWeeks();
								$returnData[$key]['facilities'] = array();
								foreach($elm->getFacilities() as $fac){
									array_push($returnData[$key]['facilities'],$fac->getName());
								}
								$returnData[$key]['specReq'] = $elm->getSpecReq();
								$returnData[$key]['status_id'] = $elm->getStatus()->getId();
								$returnData[$key]['status_code'] = $elm->getStatus()->getName();
								// need semester!!!!
							}
							
	                        //Echo JSON
	                        echo json_encode($returnData);
	                        
							break; //case break
							}
					case "modules":
						$modules = $parent->db->getModules();
						$returnData = array();
						foreach($modules as $key => $module){
							$returnData[] = $module->getCode()." - ".$module->getName();
						}
						echo json_encode($returnData);
						
						break;
					case "remove":
						if(isset($_GET['id'])){
							//Delete request
							$request = $parent->db->getRequestById($_GET['id']);
							$parent->db->deleteRequest($_GET['id']);
							// Update History
							$parent->db->addHistory("Request Cancelled", $request->getModule()->getCode() . ", " . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . ", Period " . $request->getPeriod());
						}else{
							header("HTTP/1.0 500 Internal Server Error");
        					header('Content-Type: application/json');
        					die('ERROR');
						}
						break;
					case "decline":
						if(isset($_GET['id'])){
							//Decline Allocation
							$parent->db->declineAllocation($_GET['id']);
							//Update History
							$request = $parent->db->getRequestById($_GET['id']);
							$parent->db->addHistory("Allocation Declined", $request->getModule()->getCode() . ", " . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . ", Period " . $request->getPeriod());
						}else{
							header("HTTP/1.0 500 Internal Server Error");
        					header('Content-Type: application/json');
        					die('ERROR');
						}
						break;
				}
                
                return;
			
			}
			
			$parent->title = "Responses";
			$parent->displayHeader();
			
			?>
			<div id="loadingOverlay">
				<img src="images/loading-bar.gif" />
				Loading Data...
			</div>
			<div id="top">
				<div id="ModSearch">
					<p class="heading">Find Module:</p>
					<div class="ui-widget">
						<input id="modulesearch"></input>
					</div>
					<input type="button" id="clear" value="Clear" ></input>
				</div>
							
				<div id="filters">
					<p class="heading">Additional Filters:</p>
					
					<div id="filterholder">
					<div id="Round">
						<p class="subHeading">Round</p>
							<ul id="roundlist">
								<div id="roundcheckbox1">
									<li id="roundp"><input class="round_check" type="checkbox" value="p">P<br></li>
									<li id="round1"><input class="round_check" type="checkbox" value="1">1<br></li>
								</div>
								<div id="roundcheckbox2">
									<li id="round2"><input class="round_check" type="checkbox" value="2">2<br></li>
									<li id="round3"><input class="round_check" type="checkbox" value="3">3<br></li>
								</div>
								<div id="roundcheckbox3">
									<li id="round4"><input class="round_check" type="checkbox" value="4">4<br></li>
									<li id="round5"><input class="round_check" type="checkbox" value="a">A<br></li>
								</div>
							</ul>
					</div>
								
					<div id="Part">
						<p class="subHeading">Part</p>
						<ul id="partlist">
							<div id="checkboxcol1">
								<li id="parta"><input name="partA" class="part_check" type="checkbox" value="A" >A<br></li>
								<li id="partb"><input name="partB" class="part_check" type="checkbox" value="B">B<br></li>
							</div>
							<div id="checkboxcol2">
								<li id="partc"><input name="partC" class="part_check" type="checkbox" value="C">C<br></li>
								<li id="partd"><input name="partD" class="part_check" type="checkbox" value="D">D<br></li>
							</div>
							<div id="checkboxcol3">
								<li id="partp"><input name="partP" class="part_check" type="checkbox" value="P">P<br></li>
								<li id="partz"><input name="partZ" class="part_check" type="checkbox" value="Z">Z<br></li>
							</div>
						</ul>				
					</div>
								
					<div id="Day">
						<p class="subHeading">Day</p>
						<form name="dayform">
						<ul id="daylist">
							<li id="monday"><input name="day1" class="day_check" type="checkbox" value="1"> Mon<br></li>
							<li id="tuesday"><input name="day2" class="day_check" type="checkbox" value="2"> Tue<br></li>
							<li id="wednesday"><input name="day3" class="day_check" type="checkbox" value="3"> Wed<br></li>
							<li id="thursday"><input name="day4" class="day_check" type="checkbox" value="4"> Thur<br></li>
							<li id="friday"><input name="day5" class="day_check" type="checkbox" value="5"> Fri<br></li>
						</ul>
						</form>
					</div>
					</div>		
				</div>
				
						
				<div id="type">
					<p class="heading">Type:</p>
					<ul id="typelist">
						<?php
							$statuses = array();
							$statuses["pending"] = $parent->db->getStatusByName("Pending")->getId();
							$statuses["allocated"] = $parent->db->getStatusByName("Allocated")->getId();
							$statuses["declined"] = $parent->db->getStatusByName("Declined")->getId();
							$statuses["failed"] = $parent->db->getStatusByName("Failed")->getId();
							$statuses["reallocated"] = $parent->db->getStatusByName("Reallocated")->getId();
						?>
						<li id="pending"><input name="Pending"  type="checkbox" checked="checked" class="type_check"  value="<?php echo $statuses["pending"];?>"> Pending<br></li>
						<li id="allocated"><input name="Allocated" checked="checked" type="checkbox" class="type_check" value="<?php echo $statuses["allocated"];?>"> Allocated<br></li>
						<li id="reallocated"><input name="Reallocated" checked="checked" type="checkbox" class="type_check" value="<?php echo $statuses["reallocated"];?>"> Re-Allocated<br></li>
						<li id="declined"><input name="Declined" type="checkbox" class="type_check" value="<?php echo $statuses["declined"];?>"> Declined<br></li>
						<li id="failed"><input id="failed_check" name="Failed" type="checkbox" class="type_check" value="<?php echo $statuses["failed"];?>"> Failed<br></li>
					</ul>
				</div>
				
				
					
			</div>
			
			<!--Add OVERLAY IF NEEDED-->
			<!--
			<div id="addEditOverlay" class="overlay" style="top: 65.2px; left: 780.5px; position: fixed; display: block; ">
				<a class="close"></a>
				<h2 id="model_head">Edit Request</h2>
			</div>
			-->
						
			<div id="Results">
				<div id="divContainer">
						<div id="header" class="divfixedHeader">
							<div class="mainheader" id="title_round" value="0">Round</div>
							<div class="mainheader" id="title_modcode" value="1">Module Code</div>
							<div class="mainheader" id="title_modtitle" value="2">Module Title</div>
							<div class="mainheader" id="title_day" value="3">Day</div>
							<div class="mainheader" id="title_time" value="4">Time</div>
							<div class="mainheader" id="title_length" value="5">Length</div>
							<div class="mainheader" id="title_numstudents" value="6">No. Of Students</div>
							<div class="mainheader" id="title_numrooms" value="7">No. Of Rooms</div>
							<div class="mainheader" id="title_roompreference" value="8">Room Preference</div>
							<div class="mainheader" id="title_roomallocation" value="9">Room Allocation</div>	
						</div>
						<div id="scrollablearea">
							
						</div>
					</div>			
			</div>
			
			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>
