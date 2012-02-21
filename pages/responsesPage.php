<?php 

	class responses {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			$parent->title = "Responses";
			$parent->displayHeader();
			
			?>
			<div id="top">
				<div id="ModSearch">
					<p class="heading">Find Module:</p>
					<textarea id="modulesearch" rows="5" cols="100" maxLength="50"></textarea>
				</div>
							
				<div id="filters">
					<p class="heading">Additional Filters:</p>
					<div id="Round">
						<p>Round</p>
							<ul id="list">
								<div id="roundcheckbox1">
									<li id="roundp"><input name="roundp" type="checkbox" value="A">P<br></li>
									<li id="round1"><input name="round1" type="checkbox" value="B">1<br></li>
								</div>
								<div id="roundcheckbox2">
									<li id="round2"><input name="round2" type="checkbox" value="C">2<br></li>
									<li id="round3"><input name="round3" type="checkbox" value="A">3<br></li>
								</div>
								<div id="roundcheckbox3">
									<li id="round4"><input name="round4" type="checkbox" value="B">4<br></li>
									<li id="round5"><input name="round5" type="checkbox" value="C">5<br></li>
								</div>
							</ul>
						
					</div>
								
					<div id="Part">
						<p>Part</p>
						<ul id="partlist">
							<div id="checkboxcol1">
								<li id="partA"><input name="partA" type="checkbox" value="A" >A<br></li>
								<li id="partB"><input name="partB" type="checkbox" value="B">B<br></li>
							</div>
							<div id="checkboxcol2">
								<li id="partC"><input name="partC" type="checkbox" value="C">C<br></li>
								<li id="partD"><input name="partD" type="checkbox" value="D">D<br></li>
							</div>
							<div id="checkboxcol3">
								<li id="partP"><input name="partP" type="checkbox" value="P">P<br></li>
								<li id="partZ"><input name="partZ" type="checkbox" value="Z">Z<br></li>
							</div>
						</ul>				
					</div>
								
					<div id="Day">
						<p>Day</p>
						<form name="dayform">
						<ul id="daylist">
							<li id="Mon"><input name="day" type="checkbox" value="Mon" onclick='dayfilter()'>Mon<br></li>
							<li id="Tue"><input name="day" type="checkbox" value="Tue" onclick='dayfilter()'>Tue<br></li>
							<li id="Wed"><input name="day" type="checkbox" value="Wed" onclick='dayfilter()'>Wed<br></li>
							<li id="Thur"><input name="day" type="checkbox" value="Thur" onclick='dayfilter()'>Thur<br></li>
							<li id="Fri"><input name="day" type="checkbox" value="Fri" onclick='dayfilter()'>Fri<br></li>
						</ul>
						</form>
					</div>
							
				</div>
						
				<div id="type">
					<p class="heading">Type:</p>
					<ul id="list">
						<li id="pending"><input type="checkbox" name="Pending" checked="checked" class="type_check"  value="op1">Pending<br></li>
						<li id="allocated"><input name="Allocated" type="checkbox" class="type_check" value="op2">Allocated<br></li>
						<li id="declined"><input name="Declined" type="checkbox" class="type_check" value="op3">Declined<br></li>
						<li id="failed"><input name="Failed" type="checkbox" class="type_check" value="op4">Failed<br></li>
					</ul>
				</div>
					
			</div>
			<div id="Results">
				<div id="divContainer">
						<div id="header" class="divfixedHeader">
							<div class="round" id="r" value="round">Round</div>
							<div class="modcode" id="ModCode" value="modcode">Module Code</div>
							<div class="modtitle" id="ModTitle" value="modtitle">Module Title</div>
							<div class="day" id="d" value="day">Day</div>
							<div class="time" id="Time" value="time">Time</div>
							<div class="length" id="Length" value="length">Length</div>
							<div class="numstudents" id="NumStudents" value="numstudents">No. Of Students</div>
							<div class="numrooms" id="NumRooms" value="numrooms">No. Of Rooms</div>
							<div class="roompreference" id="Roompreference" value="roompreference">Room Preference</div>
							<div class="roomallocation" id="Roomallocation" value="roomallocation">Room Allocation</div>	
						</div>
						<div id="scrollablearea">
							<!--Div row 1.-->
							<div id="row1" class="scrollContent Pending" onMouseDown='showonlyone("newboxes1");'>
								
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
								<div class="toolbar">
									<span class="edit">Edit </span><img src="images\tools.png" class="edit_btn" height="20" width="20" />
									<span class="delete">Delete </span><img src="images\delete.png" class="del_btn" id="tool2" height="20" width="20" />
								</div>
							</div>
							<!--Hidden Div for Div Row 1 which will display all the information of the request/repsonse clicked.-->
							<div class="hidden" name="newboxes" id="newboxes1">
								<div class="info1">
									<br><br>
									<span class="title">Park:</span><p class="normal"> No Preference</p>
									<br><br>
									<span class="title">Room Type:</span><p class="normal"> Lecture</p>
									<br><br>
									<span class="title">Weeks:</span><p class="normal"> 1-4 and 6-10</p>
									<br>
								</div>
								<div class="info2">
									<br>
									<span class="title"><center>Facilities</center></span>
									<div class="facilities">
										<ul>
											<li>Wheelchair Access</li>
											<li>Data Projector</li>
											<li>Wolf Vision</li>
										</ul>
									</div>
									<br>					
								</div>
								<div class="info3">
									<br>
									<span class="title"><center>Special Requirements</center></span>
									<br>
									<div class="requirementstextarea">
										<form>
											<textarea name="specialrequirements" cols="25" rows="6" readonly>Special Requirements Displayed Here - Non Edittable</textarea>
										</form>
									</div>
								</div>
							</div>	
							<!--Div Row 2.-->				
							<div id="row2" class="scrollContent Allocated" onMouseDown='showonlyone("newboxes2");'>
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Essential Skills In Computing</div>
								<div class="daycontent">Monday</div>
								<div class="timecontent">10:00</div>
								<div class="lengthcontent">1</div>
								<div class="numstudentscontent">25</div>
								<div class="numroomscontent">1</div>
								<div class="roompreferencecontent">A001</div>
								<div class="roomallocationcontent">A001</div>
								<div class="toolbar">
									<span class="edit">Edit </span><img src="images\tools.png" class="edit_btn" height="20" width="20" />
									<span class="delete">Delete </span><img src="images\delete.png" class="del_btn" id="tool2" height="20" width="20" />
								</div>
							</div>
							<!--Hidden Div for Div Row 2 which will display all the information of the request/repsonse clicked.-->
							<div class="hidden" name="newboxes" id="newboxes2">
								<div class="info1">
									<br><br>
									<span class="title">Park:</span><p class="normal"> No Preference</p>
									<br><br>
									<span class="title">Room Type:</span><p class="normal"> Lecture</p>
									<br><br>
									<span class="title">Weeks:</span><p class="normal"> 1-4 and 6-10</p>
									<br>
								</div>
								<div class="info2">
									<br>
									<span class="title"><center>Facilities</center></span>
									<div class="facilities">
										<ul>
											<li>Wheelchair Access</li>
											<li>Data Projector</li>
											<li>Wolf Vision</li>
										</ul>
									</div>
									<br>					
								</div>
								<div class="info3">
									<br>
									<span class="title"><center>Special Requirements</center></span>
									<br>
									<div class="requirementstextarea">
										<form>
											<textarea name="specialrequirements" cols="25" rows="6" readonly>Special Requirements Displayed Here - Non Edittable</textarea>
										</form>
									</div>
								</div>			
							</div>
							
							<div id="row3" class="scrollContent Declined" onMouseDown='showonlyone("newboxes3");'>
								<div class="roundcontent">2</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
								<div class="toolbar">
									<span class="edit">Edit </span><img src="images\tools.png" class="edit_btn" height="20" width="20" />
									<span class="delete">Delete </span><img src="images\delete.png" class="del_btn" id="tool2" height="20" width="20" />
								</div>
							</div>
							<!--Hidden Div for Div Row 3 which will display all the information of the request/repsonse clicked.-->
							<div class="hidden" name="newboxes" id="newboxes3">
								<div class="info1">
									<br><br>
									<span class="title">Park:</span><p class="normal"> No Preference</p>
									<br><br>
									<span class="title">Room Type:</span><p class="normal"> Lecture</p>
									<br><br>
									<span class="title">Weeks:</span><p class="normal"> 1-4 and 6-10</p>
									<br>
								</div>
								<div class="info2">
									<br>
									<span class="title"><center>Facilities</center></span>
									<div class="facilities">
										<ul>
											<li>Wheelchair Access</li>
											<li>Data Projector</li>
											<li>Wolf Vision</li>
										</ul>
									</div>
									<br>					
								</div>
								<div class="info3">
									<br>
									<span class="title"><center>Special Requirements</center></span>
									<br>
									<div class="requirementstextarea">
										<form>
											<textarea name="specialrequirements" cols="25" rows="6" readonly>Special Requirements Displayed Here - Non Edittable</textarea>
										</form>
									</div>
								</div>			
							</div>
							<div id="row4" class="scrollContent Failed" onMouseDown='showonlyone("newboxes4");'>
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
								<div class="toolbar">
									<span class="edit">Edit </span><img src="images\tools.png" class="edit_btn" height="20" width="20" />
									<span class="delete">Delete </span><img src="images\delete.png" class="del_btn" id="tool2" height="20" width="20" />
								</div>
							</div>
							<!--Hidden Div for Div Row 4 which will display all the information of the request/repsonse clicked.-->
							<div class="hidden" name="newboxes" id="newboxes4">
								<div class="info1">
									<br><br>
									<span class="title">Park:</span><p class="normal"> No Preference</p>
									<br><br>
									<span class="title">Room Type:</span><p class="normal"> Lecture</p>
									<br><br>
									<span class="title">Weeks:</span><p class="normal"> 1-4 and 6-10</p>
									<br>
								</div>
								<div class="info2">
									<br>
									<span class="title"><center>Facilities</center></span>
									<div class="facilities">
										<ul>
											<li>Wheelchair Access</li>
											<li>Data Projector</li>
											<li>Wolf Vision</li>
										</ul>
									</div>
									<br>					
								</div>
								<div class="info3">
									<br>
									<span class="title"><center>Special Requirements</center></span>
									<br>
									<div class="requirementstextarea">
										<form>
											<textarea name="specialrequirements" cols="25" rows="6" readonly>Special Requirements Displayed Here - Non Edittable</textarea>
										</form>
									</div>
								</div>			
							</div>
							<div id="row5" class="scrollContent Pending" onMouseDown='showonlyone("newboxes5");'>
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
								<div class="toolbar">
									<span class="edit">Edit </span><img src="images\tools.png" class="edit_btn" height="20" width="20" />
									<span class="delete">Delete </span><img src="images\delete.png" class="del_btn" id="tool2" height="20" width="20" />
								</div>
							</div>
							<!--Hidden Div for Div Row 5 which will display all the information of the request/repsonse clicked.-->
							<div class="hidden" name="newboxes" id="newboxes5">
								<div class="info1">
									<br><br>
									<span class="title">Park:</span><p class="normal"> No Preference</p>
									<br><br>
									<span class="title">Room Type:</span><p class="normal"> Lecture</p>
									<br><br>
									<span class="title">Weeks:</span><p class="normal"> 1-4 and 6-10</p>
									<br>
								</div>
								<div class="info2">
									<br>
									<span class="title"><center>Facilities</center></span>
									<div class="facilities">
										<ul>
											<li>Wheelchair Access</li>
											<li>Data Projector</li>
											<li>Wolf Vision</li>
										</ul>
									</div>
									<br>					
								</div>
								<div class="info3">
									<br>
									<span class="title"><center>Special Requirements</center></span>
									<br>
									<div class="requirementstextarea">
										<form>
											<textarea name="specialrequirements" cols="25" rows="6" readonly>Special Requirements Displayed Here - Non Edittable</textarea>
										</form>
									</div>
								</div>			
							</div>
							<div class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
							<div  class="scrollContent">
								<div class="roundcontent">1</div>
								<div class="modcodecontent">C0B101</div>
								<div class="modtitlecontent">Logic and Functional Programming</div>
								<div class="daycontent">Wednesday</div>
								<div class="timecontent">12:00</div>
								<div class="lengthcontent">3</div>
								<div class="numstudentscontent">800</div>
								<div class="numroomscontent">2</div>
								<div class="roompreferencecontent">JJ004</div>
								<div class="roomallocationcontent">JJ004</div>
							</div>
						</div>
					</div>			
			</div>
			
			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>
