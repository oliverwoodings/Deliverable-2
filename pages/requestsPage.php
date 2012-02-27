<?php 

	class requests {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			if (isset($parent->get)) {
			
				switch ($parent->get) {
					//Update available request data
					case "update":
					
						//Create search object and fill with data from get headers
						$search = new Search();
						if (isset($_GET["module_id"]) && strlen($_GET["module_id"]) > 0) $search->module = $parent->db->getModuleById($_GET["module_id"]);
						if (isset($_GET["num_students"]) && strlen($_GET["num_students"]) > 0) $search->numStudents = $_GET["num_students"];
						if (isset($_GET["facilities"]) && strlen($_GET["facilities"]) > 0) {
							$arr = json_decode($_GET["facilities"]);
							$facArr = array();
							foreach ($arr as $fac) $facArr[] = $parent->db->getFacilityById($fac);
							$search->facilities = $facArr;
						}
						if (isset($_GET["park"]) && strlen($_GET["park"]) > 0) $search->park = $parent->db->getParkById($_GET["park"]);
						if (isset($_GET["rooms"]) && strlen($_GET["rooms"]) > 0) {
							$arr = json_decode($_GET["rooms"]);
							$rmArr = array();
							foreach ($arr as $room) $rmArr[] = $parent->db->getRoomByCode($room);
							$search->rooms = $rmArr;
						}
						if (isset($_GET["length"]) && strlen($_GET["length"]) > 0) $search->length = $_GET["length"];
						if (isset($_GET["room_type"]) && strlen($_GET["room_type"]) > 0) $search->roomType = $parent->db->getRoomTypeById($_GET["room_type"]);
						if (isset($_GET["weeks"]) && strlen($_GET["weeks"]) > 0) $search->weeks = json_decode($_GET["weeks"]);
						if (isset($_GET["day"]) && strlen($_GET["day"]) > 0) $search->day = $_GET["day"];
						if (isset($_GET["period"]) && strlen($_GET["period"]) > 0) $search->period = $_GET["period"];
						if (isset($_GET["num_rooms"]) && strlen($_GET["num_rooms"]) > 0) $search->numRooms = $_GET["num_rooms"];
						else $search->numRooms = 1;
						
						//Retrieve matching data
						$data = $search->getAvailableRequestData($parent->db);
						
                        //Format into JSON-parsable array
						$returnData = array();
						$returnData["modules"] = array();
						foreach ($data["modules"] as $module) {
							$returnData["modules"][] = $module->getAsArray(); //Modules
						}
                        $returnData["numStudents"] = (isset($_GET["num_students"])?$_GET["num_students"]:0);
						$returnData["capacity"] = 0;
                        $returnData["rooms"] = array(); 
                        $returnData["parks"] = array();
                        $returnData["roomTypes"] = array();
                        $returnData["facilities"] = array();
                        foreach ($data["rooms"] as $room) {
                            $returnData["rooms"][] = $room->getAsArray(); //Rooms
                            if (!in_array(get_object_vars($room->getBuilding()->getPark()), $returnData["parks"]))
                                $returnData["parks"][] = get_object_vars($room->getBuilding()->getPark()); //Parks
                            if (!in_array(get_object_vars($room->getType()), $returnData["roomTypes"]))
                                $returnData["roomTypes"][] = get_object_vars($room->getType()); //Room types
                            foreach ($room->getFacilities() as $facility) {
                                if (!in_array(get_object_vars($facility), $returnData["facilities"]))
                                    $returnData["facilities"][$facility->getId()] = get_object_vars($facility); //Facilities
                            }
							if ($room->getCapacity() > $returnData["capacity"]) $returnData["capacity"] = $room->getCapacity();
                        }
						$returnData["time"] = $data["time"];
						
                        //Echo JSON
                        echo json_encode($returnData);
                        
						break;
						
					case "submit":
						
						//If we are editing
						if (isset($_GET["editid"]) && $parent->db->getRequestById($_GET["editid"])) {
							$request = $parent->db->getRequestById($_GET["editid"]);
							
						} else {
							$request = new Request();
						}						
						
						//Set request data
						$request->setRound($parent->db->getActiveRound());
						$request->setStatus($parent->db->getStatusByName("pending"));
						$request->setModule($parent->db->getModuleById($_GET["module_id"]));
						if (isset($_GET["room_type"])) $request->setRoomType($parent->db->getRoomTypeById($_GET["room_type"]));
						$request->setRooms(array());
						if (isset($_GET["rooms"])) foreach (json_decode($_GET["rooms"]) as $room) $request->addRoom($parent->db->getRoomByCode($room));
						if (isset($_GET["park"])) $request->setPark($parent->db->getParkById($_GET["park"]));
						$request->setPeriod($_GET["period"]);
						$request->setDay($_GET["day"]);
						$weeks = array_fill(0, 15, false);
						foreach (json_decode($_GET["weeks"]) as $week) $weeks[$week -1] = true;
						$request->setWeeks($weeks);
						$request->setLength($_GET["length"]);
						$request->setNumStudents($_GET["num_students"]);
						if (isset($_GET["num_rooms"])) $request->setNumRooms($_GET["num_rooms"]);
						$request->setPriority(($request->getRound()->getName() == "P"?true:str_replace(array(0,1), array(false, true), $_GET["priority"])));
						if (isset($_GET["spec_req"])) $request->setSpecReq($_GET["spec_req"]);
						if (isset($_GET["facilities"]) && strlen($_GET["facilities"]) > 0) {
							$arr = json_decode($_GET["facilities"]);
							$facArr = array();
							foreach ($arr as $fac) $facArr[] = $parent->db->getFacilityById($fac);
							$request->setFacilities($facArr);
						}
						
						//If adhoc, allocate straight away
						if ($request->getRound()->getName() == "A") {
							
							//Save request
							$request->setStatus($parent->db->getStatusByName("Allocated"));
							$request->setId($parent->db->saveRequest($request));
						
							//Sort out rooms
							$search = new Search();
							$search->module = $request->getModule();
							$search->numStudents = $request->getNumStudents();
							if ($request->getFacilities() != null) $search->facilities = $request->getFacilities();
							if ($request->getPark() != null) $search->park = $request->getPark();
							$search->length = $request->getLength();
							if ($request->getRoomType() != null) $search->roomType = $request->getRoomType();
							$search->weeks = $request->getWeeks();
							$search->day = $request->getDay();
							$search->period = $request->getPeriod();
							$search->numRooms = $request->getNumRooms();
							$data = $search->getAvailableRequestData($parent->db);
							for ($i = count($request->getRooms()); $i < $request->getNumRooms(); $i++) $request->addRoom($data["rooms"][$i]);
							
							//Add allocations
							$rooms = $request->getRooms();
							for ($i = 0; $i < $request->getLength(); $i++) {
								for ($j = 0; $j < $request->getNumRooms(); $j++) {
									$parent->db->addAllocation(new Allocation($request, $rooms[$j], $request->getPeriod() + $i));
								}
							}
							
						}
						//Otherwise just save the request
						else {
							$parent->db->saveRequest($request);
						}
						
						$parent->db->addHistory("Request Added", $request->getModule()->getCode() . ", " . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . ", Period " . $request->getPeriod());
	
						break;
						
					case "loadid":
						
						//Validation check
						if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) return $parent->errorJSON(array(), "Invalid ID!");
						$request = $parent->db->getRequestById($_GET["id"]);
						if (!$request || ($request->getStatus()->getName() != "Pending" && $request->getStatus()->getName() != "Declined" && $request->getStatus()->getName() != "Failed")) return $parent->errorJSON(array(), "Invalid ID!");
						
						//Form array out of request
						$facs = array();
						foreach ($request->getFacilities() as $fac) $facs[] = $fac->getId();
						$rooms = array();
						foreach ($request->getRooms() as $room) $rooms[] = $room->getAsArray();
						$return = array();
						$return["id"] = $request->getId();
						$return["day"] = $request->getDay();
						$return["period"] = $request->getPeriod();
						$return["module"] = $request->getModule()->getAsArray();
						$return["priority"] = $request->getPriority();
						$return["numRooms"] = $request->getNumRooms();
						$return["numStudents"] = $request->getNumStudents();
						$return["weeks"] = $request->getWeeks();
						$return["length"] = $request->getLength();
						if (count($facs) > 0) $return["facilities"] = $facs;
						if ($request->getPark() != null) $return["park"] = $request->getPark()->getAsArray();
						if ($request->getRoomType() != null) $return["roomType"] = $request->getRoomType()->getAsArray();
						if (count($rooms) > 0) $return["rooms"] = $rooms;
						echo json_encode($return);
						
						break;
						
				}
                
                return;
			
			}
			
			$parent->title = "Requests";
			$parent->displayHeader();
			
			?>
			<div id="loadingOverlay">
				<img src="images/loading-bar.gif" />
				Loading Data...
			</div>
			<div id="row1">
				<div>
					<div id="module_sect" class="mand">
						<div id="modline">
							<div id="mod_tag"><h1>Module: </h1></div>
							<div id="module_input" contenteditable="true" class="req_input" /></div>
						</div>
						<div id="mod_expand"/>
						</div>
					</div>
					<div id="noStudents_sect">
						<div id="nostud_tag"><h1>No. Students: </h1></div>
						<div class="ui-widget" id="noStud_input">
							<select id="noStud_select" class="req_select" >
							</select>
						</div>
					</div>
					<div id="priority_sect">
						<div id="priority_tag"><h1>Priority: <h1></div>
						<div id="pri_div">
							<p>
								<input type="checkbox" id="priority_check" />
								<label for="priority_check"></label>
							</p>
						</div>
					</div>
				</div>
				
			</div>
				
			<!-- Facilities, Special Requirments -->
			<div id="row2">
				<div id="facilities_sect">
					<h1>Facilities: </h1>
					<div id="fac_area"> 
						<div class="fac_option">
							<p>
								<input type="checkbox" id="2_check" />
								<label for="2_check">Data Projector</label>
							</p>
						</div>
						<div class="fac_option">
							<p>
								<input type="checkbox" id="1_check" />
								<label for="1_check">OHP</label>
							</p>
						</div>
						<div class="fac_option">
							<p>
								<input type="checkbox" id="5_check" />
								<label for="5_check">Whiteboard</label>
							</p>
						</div>
						<div class="fac_option">
							<p>
								<input type="checkbox" id="4_check" />
								<label for="4check">Chalkboard</label>
							</p>
						</div>
						<div class="fac_option">
							<p>
								<input type="checkbox" id="3_check" />
								<label for="3_check">Wheelchair Access</label>
							</p>
						</div>
					</div>
				</div>
				<div id="special_sect">
					<div id="req_tag"><h1>Special Requirements: </h1></div>
					<textarea id="spec_input" rows="5"></textarea>
				</div>
			</div>
				

			<!-- Park, Room Type, Room Preference, no.Rooms -->
			<div id="row3">
				<div id="parkrtype_sect">
					<div id="park_sect">
						<div id="park_tag"><h1>Park: </h1></div>
						<div class="ui-widget" id="park_input">
							<select id="park_select" class="req_select" >
							</select>
						</div>
					</div>
					<div id="roomty_sect">
						<div id="type_tag"><h1>Room Type: </h1></div>
						<div class="ui-widget" id="room_type_input">
							<select id="roomty_select" class="req_select" >
							</select>
						</div>
					</div>
				</div>
				<div id="roomprefno_sect">
					<div id="roompref_sect">
						<div id="room_tag"><h1>Room Preference: </h1></div>
						<div id="roompref_input" contenteditable="true" class="req_input" /></div>
					</div>
					<div id="noRooms_sect">
						<div id="numroom_tag"><h1>No. Rooms: </h1></div>
						<input type="text" id="noRooms_input" class="req_input" value='1' />
					</div>
				</div>
				<div id="roompref_expand" >
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="rooms">
						<thead>
							<tr>
								<th>Picture</th>
								<th>Room</th>
								<th>Building</th>
								<th>Park</th>
								<th>Capacity</th>
								<th>Room Type</th>
								<th>Facilities</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
                    <input type="button" id="room_done" class="std_but" value="Close" style="float:right;"/>
				</div>
			</div>
			<!-- Length, Day, Time -->
			<div id="row4">
				<div id="length_sect" class="mand">
					<div id="time_tag"><h1>Time: </h1></div>
					<div class="ui-widget" id="length_input">
						<select id="length_select" class="req_select">
							<option value="1" selected="selected">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<!-- More options here -->
						</select>
					</div>
					<div id="length_tag">Length: </div>
				</div>
				<div id="daytime_sect" class="mand">
					<!-- needs changing to request page shit -->
					<table id="daytime_sel_table" summary="">
						<colgroup id="days" span="1"></colgroup>
						<thead>
							<tr>
								<th><h1></h1></th>
								<th>9:00</th>
								<th>10:00</th>
								<th>11:00</th>
								<th>12:00</th>
								<th>13:00</th>
								<th>14:00</th>
								<th>15:00</th>
								<th>16:00</th>
								<th>17:00</th>
						</thead>
						<tbody id="contentt">
							<tr>
								<th>Monday</th>
								<td><div class="td_content" id="cell0"></div></td>
								<td><div class="td_content" id="cell1"></div></td>
								<td><div class="td_content" id="cell2"></div></td>
								<td><div class="td_content" id="cell3"></div></td>
								<td><div class="td_content" id="cell4"></div></td>
								<td><div class="td_content" id="cell5"></div></td>
								<td><div class="td_content" id="cell6"></div></td>
								<td><div class="td_content" id="cell7"></div></td>
								<td><div class="td_content" id="cell8"></div></td>
							</tr>
							<tr>
								<th>Tuesday</th>
								<td><div class="td_content" id="cell10"></div></td>
								<td><div class="td_content" id="cell11"></div></td>
								<td><div class="td_content" id="cell12"></div></td>
								<td><div class="td_content" id="cell13"></div></td>
								<td><div class="td_content" id="cell14"></div></td>
								<td><div class="td_content" id="cell15"></div></td>
								<td><div class="td_content" id="cell16"></div></td>
								<td><div class="td_content" id="cell17"></div></td>
								<td><div class="td_content" id="cell18"></div></td>
							</tr>
							<tr>
								<th>Wednesday</th>
								<td><div class="td_content" id="cell20"></div></td>
								<td><div class="td_content" id="cell21"></div></td>
								<td><div class="td_content" id="cell22"></div></td>
								<td><div class="td_content" id="cell23"></div></td>
								<td><div class="td_content" id="cell24"></div></td>
								<td><div class="td_content" id="cell25"></div></td>
								<td><div class="td_content" id="cell26"></div></td>
								<td><div class="td_content" id="cell27"></div></td>
								<td><div class="td_content" id="cell28"></div></td>
							</tr>
							<tr>
								<th>Thursday</th>
								<td><div class="td_content" id="cell30"></div></td>
								<td><div class="td_content" id="cell31"></div></td>
								<td><div class="td_content" id="cell32"></div></td>
								<td><div class="td_content" id="cell33"></div></td>
								<td><div class="td_content" id="cell34"></div></td>
								<td><div class="td_content" id="cell35"></div></td>
								<td><div class="td_content" id="cell36"></div></td>
								<td><div class="td_content" id="cell37"></div></td>
								<td><div class="td_content" id="cell38"></div></td>
							</tr>
							<tr>
								<th>Friday</th>
								<td><div class="td_content" id="cell40"></div></td>
								<td><div class="td_content" id="cell41"></div></td>
								<td><div class="td_content" id="cell42"></div></td>
								<td><div class="td_content" id="cell43"></div></td>
								<td><div class="td_content" id="cell44"></div></td>
								<td><div class="td_content" id="cell45"></div></td>
								<td><div class="td_content" id="cell46"></div></td>
								<td><div class="td_content" id="cell47"></div></td>
								<td><div class="td_content" id="cell48"></div></td>
							</tr>
					  </tbody>
					</table>
				</div>
			</div>
			<!-- Weeks -->
			<div id="row5">
				<div id="weeks_sect">
					<h1>Weeks:</h1>
					<div id="wks">
						<ul id="week_boxes">
							<li><p><input id="wkcheck_1" type="checkbox" name="week" value="1" checked="checked"/><label for="wkcheck_1">1</label></p></li>
							<li><p><input id="wkcheck_2" type="checkbox" name="week" value="2" checked="checked"/><label for="wkcheck_2">2</label></p></li>
							<li><p><input id="wkcheck_3" type="checkbox" name="week" value="3" checked="checked"/><label for="wkcheck_3">3</label></p></li>
							<li><p><input id="wkcheck_4" type="checkbox" name="week" value="4" checked="checked"/><label for="wkcheck_4">4</label></p></li>
							<li><p><input id="wkcheck_5" type="checkbox" name="week" value="5" checked="checked"/><label for="wkcheck_5">5</label></p></li>
							<li><p><input id="wkcheck_6" type="checkbox" name="week" value="6" checked="checked"/><label for="wkcheck_6">6</label></p></li>
							<li><p><input id="wkcheck_7" type="checkbox" name="week" value="7" checked="checked"/><label for="wkcheck_7">7</label></p></li>
							<li><p><input id="wkcheck_8" type="checkbox" name="week" value="8" checked="checked"/><label for="wkcheck_8">8</label></p></li>
							<li><p><input id="wkcheck_9" type="checkbox" name="week" value="9" checked="checked"/><label for="wkcheck_9">9</label></p></li>
							<li><p><input id="wkcheck_10" type="checkbox" name="week" value="10" checked="checked"/><label for="wkcheck_10">10</label></p></li>
							<li><p><input id="wkcheck_11" type="checkbox" name="week" value="11" checked="checked"/><label for="wkcheck_11">11</label></p></li>
							<li><p><input id="wkcheck_12" type="checkbox" name="week" value="12" checked="checked"/><label for="wkcheck_12">12</label></p></li>
							<li><p><input id="wkcheck_13" type="checkbox" name="week" value="13" /><label for="wkcheck_13">13</label></p></li>
							<li><p><input id="wkcheck_14" type="checkbox" name="week" value="14" /><label for="wkcheck_14">14</label></p></li>
							<li><p><input id="wkcheck_15" type="checkbox" name="week" value="15" /><label for="wkcheck_15">15</label></p></li>
						</ul>
					</div>
					<div id="week_sel_sect">
						<input type="button" id="week_all" class="std_but" value="All" />
						<input type="button" id="week_term" class="std_but" value="1 - 12" />
						<input type="button" id="week_odd" class="std_but" value="Odd" />
						<input type="button" id="week_even" class="std_but" value="Even" />
						<input type="button" id="week_none" class="std_but" value="None" />
					</div>
				</div>
			</div>
			<!--SUBMISSION -->
			<div id="submit_sect">
				<div id="subwrap">
				<input type="button" id="submit" class="std_but" value="<?php if (isset($_GET["edit"])) echo "Edit"; else echo "Submit"; ?>" />
				</div>
			</div>
			

			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>
