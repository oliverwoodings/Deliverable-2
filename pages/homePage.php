<?php 

	class home {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			$parent->title = "Home";
			$parent->displayHeader();
			
			?>
			<div id="timeline_container">
				<div id="timeline_float">
					<div id="timeline">
						<?php
						
							$rounds = $parent->db->getRounds();
							$i = 1;
							foreach ($rounds as $round) {
								if ($round->getSemester() == $parent->auth->getUserSemester()) {
									echo '<div id="r0" class="' . ($round->getActive()?'circle':'circle_small') . ' round"><h3>' . $round->getName() . '</h3></div>
										<div class="tooltip">
											<div>Start Date: <span id="sdate">' . date("d-m-Y", strtotime($round->getStartDate())) . '</span> </div>
											<div>End Date: <span id="edate">' . date("d-m-Y", strtotime($round->getEndDate())) . '</span> </div>
										</div>';
								}
								$i++;
							}
						
						
						?>
						<hr id="line"/>
					</div>
				</div>
			</div>
			<div id="left_side">
				<div id="activity_feed">
					<h2>Recent Activity</h2>
					<div id="stream">
					<ul>
						<?php
							foreach ($parent->db->getHistory() as $history) {
								echo '<li class="item' . (($history["action"] == "Round Begins" || $history["action"] == "Round Ends")?" begin":"") . '"><p><span class="date">' . date("d/m/Y", strtotime($history["date"])) . '</span> <i>' . $history["action"] . ':</i> ' . $history["msg"] . '</p></li>';
							}
						?>
					</ul>
					</div>
				</div>
			</div>
			<div id="right_side">
				<div id="request_attention">
					<h2>Urgent Notifications</h2>
					<p>The following requests require your attention:</p>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="req_att_table">
						<thead>
							<tr>
								<th>Module</th>
								<th>Day</th>
								<th>Period</th>
								<th>Room Preference</th>
								<th>Room Allocation</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($parent->db->getUrgentRequests(8) as $request) {
									$rms = array();
									foreach ($request->getRooms() as $room) $rms[] = $room->getCode();
									if ($request->getStatus()->getName() == "Reallocated") {
										$allocs = $parent->db->getAllocationsByRequestId($request->getId());
										$allrooms = array();
										foreach ($allocs as $alloc) $allrooms[] = $alloc->getRoom()->getCode();
										echo '<tr id="' . $request->getId() . '"><td class="center">' . $request->getModule()->getCode() . '</td>
											<td class="center">' . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . '</td>
											<td class="center">' . $request->getPeriod() . '</td>
											<td class="center">' . implode(" ", $rms) . '</td>
											<td class="center">' . implode(" ", $allrooms) . "</td></tr>";
									} else {
										echo '<tr id="' . $request->getId() . '" class="failed"><td class="center">' . $request->getModule()->getCode() . '</td>
											<td class="center">' . str_replace(array(1,2,3,4,5), array("Mon", "Tue", "Wed", "Thur", "Fri"), $request->getDay()) . '</td>
											<td class="center">' . $request->getPeriod() . '</td>
											<td class="center">' . implode(" ", $rms) . '</td>
											<td class="center">FAILED</td></tr>';
									}
								}
							
							?>
						</tbody>
					</table>
				</div>
			</div>           

			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>