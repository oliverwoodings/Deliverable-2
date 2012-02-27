<?php 

	class roomavailability {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			if (isset($parent->get)) {
				
				switch($parent->get){
					case "rooms":
							$results = $parent->db->getRooms();
							//Format into JSON-parsable array
							$returnData = array();
							foreach($results as $key => $room){
								$returnData[$key]['name'] = $room->getCode();
								$building = $room->getBuilding();
								$returnData[$key]['building'] = $building->getName();
								$returnData[$key]['park'] = $building->getPark()->getName();
								$returnData[$key]['type'] = $room->getType()->getName();
								$returnData[$key]['capacity'] = $room->getCapacity();
								$facilities = $room->getFacilities();
								$dataProj = false;
								$ohp = false;
								$whiteboard = false;
								$chalkboard = false;
								$wheelchair = false;
								foreach($facilities as $fac){
									switch($fac->getName()){
										case "Data Projector":
											$dataProj = true; break;
										case "OHP":
											$ohp = true; break;
										case "Whiteboard":
											$whiteboard = true; break;
										case "Chalkboard":
											$chalkboard = true; break;
										case "Wheelchair Access":
											$wheelchair = true; break;
									}
								}
								$returnData[$key]['dataProj'] = $dataProj;
								$returnData[$key]['ohp'] = $ohp;
								$returnData[$key]['whiteboard'] = $whiteboard;
								$returnData[$key]['chalkboard'] = $chalkboard;
								$returnData[$key]['wheelchair'] = $wheelchair;
								
							}
	                        //Echo JSON
	                        echo json_encode($returnData);
	                        
							break;
							
					case "bookings":
							$results = $parent->db->getAllocations();
							$returnData = array();
							$i = 0;
							foreach($results as $allocation){
								if($allocation->getRequest()->getStatus()->getId() != $parent->db->getStatusByName("declined")->getId()){
									$returnData[$i]['period'] = $allocation->getPeriod();
									$returnData[$i]['day'] = $allocation->getDay();
									$returnData[$i]['room'] = $allocation->getRoom()->getCode();
									$i++;
								}
							}
							//Echo JSON
		                    echo json_encode($returnData);
							break;
				}
                
                return;
			
			}

			$parent->title = "Home";
			$parent->displayHeader();
		
			?>
				<div id="loadingOverlay">
					<img src="images/loading-bar.gif" />
					Loading Data...
				</div>
				<!------------Overlay Window------------->
				
				<div style="display:none;">
					<div id="av_room_model">
						<h2 id="model_head">Rooms Avaliable</h2>
                        <div class="resultsContainer">
                            <table cellpadding="0" cellspacing="0" border="0" id="resultsTable" >
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
                                <tbody id="resultsBody">
                                </tbody>
                            </table>
                        </div>
						<input id="request_room_btn" type="button" value="Request Room" />	
					</div>
				</div>
				
				
				<div id="search_area">
					<ul>
						<li><a href="#search_filters">Search By Filters</a></li>
						<li><a href="#search_room">Search By Room</a></li>
					</ul>
					<div id="search_filters">
						<div id="left">
						<div class="option">
							<label>Park</label>
							<div class="ui-widget" id="park_input">
							<select id="park_combobox">
									<option value="Any">Any</option>
									<?php
										$parks = $parent->db->getParks();
										foreach ($parks as $park) {
											echo "<option>" . $park->getName() . "</option>";
										}
									?>
							</select>
							</div>
						</div>
						<div class="option">
							<label>Room Type </label>
							<div class="ui-widget" id="room_type_input">
							<select id="room_type_combobox">
									<option value="Any">Any</option>
									<?php
										$types = $parent->db->getRoomTypes();
										foreach ($types as $type) {
											echo "<option>" . $type->getName() . "</option>";
										}
									?>
							</select>
							</div>
						</div>
						<div class="option">
							<label>Capacity </label>
							<div class="ui-widget" id="capacity_input">
							<select id="capacity_combobox">
                                    <option value="Any">Any</option>
									<option value="50">50</option>
									<option value="100">100</option>
									<option value="150">150</option>
									<option value="200">200</option>
									<option value="250">250</option>
									<option value="300">300</option>
									<option value="350">350</option>
									<option value="400">400</option>
							</select>
							</div>
						</div>
						</div>
						<div id="facilities">
							<h4 class="opt_h4">Facilities</h4>
							<div class="fac_option">
								<label>Data Projector</label>
								<input type="checkbox" id="proj_check" />
							</div>
							<div class="fac_option">
								<label>OHP</label>
								<input type="checkbox" id="ohp_check" />
							</div>
							<div class="fac_option">
								<label>Whiteboard</label>
								<input type="checkbox" id="white_check" />
							</div>
							<div class="fac_option">
								<label>Chalkboard</label>
								<input type="checkbox" id="chalk_check" />
							</div>
							<div class="fac_option">
								<label>Wheelchair Access</label>
								<input type="checkbox" id="wheel_check" />
							</div>
						</div>
					</div>	
					<div id="search_room">
						<div class="option">
							<label>Building</label>
							<div class="ui-widget" id="building_input">
							<select id="building_combobox">
							</select>
							</div>
						</div>
						<div class="option">
							<label>Room</label>
							<div class="ui-widget" id="room_input">
							<select id="room_combobox">
							</select>
							</div>
						</div>
					</div> 
				</div>
				<div id="room_av">		
				<table id="room_av_table" summary="">
					<colgroup id="days" span="1"></colgroup>
					<thead>
			            <tr>
			            	<th></th>
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
			        <tbody>
			        	<tr>
			        		<th>Monday</th>
			        		<td><div href="#av_room_model" class="td_content" id="cell00"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell01"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell02"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell03"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell04"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell05"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell06"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell07"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell08"></div></td>
			        	</tr>
			        	<tr>
			        		<th>Tuesday</th>
			        		<td><div href="#av_room_model" class="td_content" id="cell10"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell11"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell12"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell13"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell14"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell15"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell16"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell17"></div></td>
			        		<td><div href="#av_room_model" class="td_content" id="cell18"></div></td>
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
			
			<?php
			
			$parent->displayFooter();
			
		}
		
	}

?>