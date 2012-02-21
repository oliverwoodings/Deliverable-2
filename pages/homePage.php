<?php 

	class home {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			$parent->title = "Home";
			$parent->displayHeader();
			
			?>
			
			<div id="timeline">
				<div id="r0" class="circle_small round"><h3>P</h3></div>
				<div class="tooltip">
					<div>Start Date: <span id="sdate">wegew</span> </div>
					<div>End Date: <span id="edate">wrgwe</span> </div>
				</div>
				<div id="r1" class="circle round"><h3 style="margin-top:21px;">Round<br/>1</h3></div>
				<div class="tooltip">
					<div>Start Date: <span id="sdate">wegew</span> </div>
					<div>End Date: <span id="edate">wrgwe</span> </div>
				</div>
				<div id="r2" class="circle_small round"><h3>2</h3></div>
				<div class="tooltip">
					<div>Start Date: <span id="sdate">wegew</span> </div>
					<div>End Date: <span id="edate">wrgwe</span> </div>
				</div>
				<div id="r3" class="circle_small round"><h3>3</h3></div>
				<div class="tooltip">
					<div>Start Date: <span id="sdate">wegew</span> </div>
					<div>End Date: <span id="edate">wrgwe</span> </div>
				</div>
				<div id="r4" class="circle_small round"><h3>4</h3></div>
				<div class="tooltip">
					<div>Start Date: <span id="sdate">wegew</span> </div>
					<div>End Date: <span id="edate">wrgwe</span> </div>
				</div>
				<div id="r5" class="circle_small round"><h3>5</h3></div>
				<div class="tooltip">
					<div>Start Date: <span id="sdate">wegew</span> </div>
					<div>End Date: <span id="edate">wrgwe</span> </div>
				</div>
				<hr id="line"/>
			</div>
			<div id="left_side">
				<div id="activity_feed">
					<h2>Recent Activity</h2>
					<div id="stream">
					<ul>
						<li class="item">
							<p>
								<span class="date">15/08/2011</span>
								<i>Rooms Added:</i> C0D400, Mon, Period 4
							</p>
						</li>
						<li class="item">
							<p>
								<span class="date">15/08/2011</span>
								<i>Rooms Added:</i> C0C111, Tue, Period 9
							</p>
						</li>
						<li class="item">
							<p>
								<span class="date">15/08/2011</span>
								<i>Rooms Added:</i> C0B130, Fri, Period 7
							</p>
						</li>
						<li class="item">
							<p>
								<span class="date">15/08/2011</span>
								<i>Rooms Added:</i> C0B145, Thur, Period 1
							</p>
						</li>
						<li class="item">
							<p>
								<span class="date">14/08/2011</span>
								<i>Rooms Edited:</i> C0A123, Wed, Period 5
							</p>
						</li>
						<li class="item cancel">
							<p>
								<span class="date">14/08/2011</span>
								<i>Request Cancelled:</i> C0B190, Fri, Period 7
							</p>
						</li>
						<li class="item add">
							<p>
								<span class="date">14/08/2011</span>
								 <i>Request Added:</i> COB190, Fri, Period 7
							</p>
						</li>
						<li class="item add">
							<p>
								<span class="date">14/08/2011</span>
								 <i>Request Added:</i> COA123, Wed, Period 6
							</p>
						</li>
						<li class="item add">
							<p>
								<span class="date">14/08/2011</span>
								 <i>Request Added:</i> COA101, Tue, Period 2
							</p>
						</li>
						<li class="item decline">
							<p>
								<span class="date">12/08/2011</span>
								<i>Allocation Declined:</i> COA123, Mon, Period 2
							</p>
						</li>
						<li class="item decline">
							<p>
								<span class="date">12/08/2011</span>
								<i>Allocation Declined:</i> COA123, Tue, Period 1
							</p>
						</li>
						<li class="item begin">
							<p>
								<span class="date">11/08/2011</span>
								<i>Round Begins:</i> Round 1
							</p>
						</li>
						<li class="item">
							<p>
								<span class="date">10/08/2011</span>
								<i>Rooms Allocated:</i> 5 Fails, 3 Reallocations
							</p>
						</li>
						<li class="item begin">
							<p>
								<span class="date">08/08/2011</span>
								<i>Round Ends:</i> Priority Round
							</p>
						</li>
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
							<tr>
								<td>COA123</td>
								<td>Mon</td>
								<td class="center" >2</td>
								<td class="center" >CC013</td>
								<td class="center" >CC012</td>
							</tr>
							<tr style="background-color:#ff6666;">
								<td style="background-color:#ff6666;">COB190</td>
								<td>Fri</td>
								<td class="center" >6</td>
								<td class="center" >J004</td>
								<td class="center" >FAILED</td>
							</tr>
							<tr>
								<td>COA111</td>
								<td>Wed</td>
								<td class="center" >3</td>
								<td class="center" >N003</td>
								<td class="center" >Cope</td>
							</tr>
							<tr>
								<td>COB145</td>
								<td>Thur</td>
								<td class="center" >1</td>
								<td class="center" >T005</td>
								<td class="center" >CC012</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>           

			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>