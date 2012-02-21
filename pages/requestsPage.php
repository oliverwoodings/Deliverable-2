<?php 

	class requests {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			$parent->title = "Requests";
			$parent->displayHeader();
			
			?>
			<div id="row1">
				<div id="module_sect" class="mand">
					<div id="modline"><span class="req_label" >Module: </span>
					<div id="module_input" contenteditable="true" class="req_input" /></div>
					</div>
					<div id="mod_expand"/>
						<div id="coa123" class="mod_elm" >
							<p>C0A123 - Essential Skills for Computing</p>
						</div>
						<div id="coa124" class="mod_elm" >
							<p>C0A123 - Essential Skills for Computing</p>
						</div>
						<div id="coa125" class="mod_elm" >
							<p>C0A123 - Essential Skills for Computing</p>
						</div>
						<div id="coa126" class="mod_elm" >
							<p>C0A123 - Essential Skills for Computing</p>
						</div>
						<div id="coa127" class="mod_elm" >
							<p>C0A123 - Essential Skills for Computing</p>
						</div>
						<div id="coa128" class="mod_elm" >
							<p>C0A123 - Essential Skills for Computing</p>
						</div>
					</div>
				</div>
				<div id="noStudents_sect" class="mand">
					<label for="noStud_input" class="req_label" >No. Students: </label>
					<input type="text" id="noStud_input" class="req_input"/>
				</div>
				<div id="priority_sect">
					<label class="req_label">Priority</label>
					<input type="checkbox" id="priority_check" /><label for="priority_check">P</label>
				</div>
			</div>
			<!-- Facilities, Special Requirments -->
			<div id="row2">
				<div id="facilities_sect">
					<label for="fac_edit" class="req_label">Facilities</label>
					<input type="button" id="fac_edit" class="std_but" value="Edit" />
					<input type="button" id="fac_done" class="std_but" value="Done" />
					<div id="fac_area">
					<div id="fac_details"> 
						
					</div>
					<div id="fac_expand"> 
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
				</div>
				<div id="special_sect">
					<label for="spec_input" class="req_label">Sepecial Requirments</label>
					<textarea id="spec_input" rows="7"></textarea>
				</div>
			</div>
			<!-- Park, Room Type, Room Preference, no.Rooms -->
			<div id="row3">
				<div id="parkrtype_sect">
					<div id="park_sect">
						<label class="req_label">Park: </label>
						<div class="ui-widget" id="park_input">
						<select id="park_select" class="req_select" >
							<option value="Any">Any</option>
							<option value="Central">Central</option>
							<option value="East">East</option>
							<option value="West">West</option>	
						</select>
						</div>
					</div>
					<div id="roomty_sect">
						<label class="req_label">Room Type: </label>
						<div class="ui-widget" id="room_type_input">
							<select id="roomty_select" class="req_select" >
								<option value="Any">Any</option>
								<option value="Traditional">Traditional</option>
								<option value="Seminar">Seminar</option>
								<!--<option value="Lab">Lab</option>-->
							</select>
						</div>
					</div>
				</div>
				<div id="roomprefno_sect">
					<div id="roompref_sect">
						<label for="roompref_input" class="req_label">Room Preference</label>
						<div id="roompref_input" contenteditable="true" class="req_input" /></div>
					</div>
					<div id="noRooms_sect" class="mand">
						<label for="noRooms_input" class="req_label">No. Rooms</label>
						<input type="text" id="noRooms_input" class="req_input" />
					</div>
				</div>
				<div id="roompref_expand" >
					<!-- Room Preference will expand in here -->
						<div id="search_area">
							<span style="float:left;">Search: </span><input type="text" id="search_input" class="req_input" style="float:left;"/>
							<span style="float:left;margin-left:10px;">By: </span> 
							<div class="ui-widget" id="search_by_input">
							<select id="search_by_select" class="req_select" style="float:left;">
								<option value="allFields">All Fields</option>
								<option value="Room">Room</option>
								<option value="Building">Building</option>
								<option value="Park">Park</option>
								<option value="Capacity">Capacity</option>
								<option value="Room Type">Room Type</option>
								<option value="Facilities">Facilities</option>
							</select>
							<input type="button" id="adv_search" class="std_but" value="Advanced Search" />
						</div>
						</div>
                        <div class="resultsContainer">
                            <table class="resultsTable">
                                <thead>
                                    <tr>
										<th>Picture</th>
                                        <th>Room</th>
                                        <th>Building</th>
                                        <th width="50px">Park</th>
                                        <th width="60px">Capacity</th>
                                        <th width="80px">Room Type</th>
                                        <th>Facilities</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsBody">
                                <tr class="resultsRow" id="even"><td>picture</td><td>JJ017</td><td>Ann Packer</td><td>East</td><td>16</td><td>Seminar</td><td>OHP, Whiteboard, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>K103</td><td>Herbert Manzoni</td><td>Central</td><td>13</td><td>Seminar</td><td>Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>S175</td><td>S Building</td><td>West</td><td>10</td><td>Seminar</td><td>OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>W05E</td><td>Sir David Davies</td><td>West</td><td>10</td><td>Seminar</td><td>Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>W05F</td><td>Sir David Davies</td><td>West</td><td>8</td><td>Seminar</td><td>Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>CC011</td><td>James France</td><td>Central</td><td>256</td><td>Traditional</td><td>Projector, OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>Cope</td><td>Cope Auditorium</td><td>East</td><td>226</td><td>Traditional</td><td>Projector, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>U020</td><td>Brockington Extension</td><td>Central</td><td>218</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>CC012</td><td>James France</td><td>Central</td><td>218</td><td>Traditional</td><td>Projector, OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>CC013</td><td>James France</td><td>Central</td><td>176</td><td>Traditional</td><td>Projector, OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>J104</td><td>EHB</td><td>Central</td><td>153</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>RT008</td><td>Sir Frank Gibb</td><td>West</td><td>148</td><td>Traditional</td><td>Projector, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>W001</td><td>Sir David Davies</td><td>West</td><td>134</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>B009</td><td>Brockington</td><td>Central</td><td>110</td><td>Traditional</td><td>OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>J002</td><td>EHB</td><td>Central</td><td>105</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>A001</td><td>Schofield</td><td>Central</td><td>101</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>JB021</td><td>Sir John Beckwith Centre for Sport</td><td>East</td><td>100</td><td>Traditional</td><td>Projector, OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>XX019</td><td>Bridgeman Centre</td><td>Central</td><td>100</td><td>Traditional</td><td>Projector, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>S004</td><td>S Building</td><td>West</td><td>98</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>A201</td><td>Schofield</td><td>Central</td><td>95</td><td>Traditional</td><td>Projector, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>JJ004</td><td>Ann Packer</td><td>East</td><td>90</td><td>Traditional</td><td>Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>W004</td><td>Sir David Davies</td><td>West</td><td>84</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>B111</td><td>Brockington</td><td>Central</td><td>80</td><td>Traditional</td><td>Projector, OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>RT005</td><td>Sir Frank Gibb</td><td>West</td><td>75</td><td>Traditional</td><td>OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>RT011</td><td>Sir Frank Gibb</td><td>West</td><td>72</td><td>Traditional</td><td>Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>RT023</td><td>Sir Frank Gibb</td><td>West</td><td>72</td><td>Traditional</td><td>Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>W003</td><td>Sir David Davies</td><td>West</td><td>69</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>ZZ106</td><td>Matthew Arnold</td><td>East</td><td>64</td><td>Traditional</td><td>OHP, Whiteboard, Chalkboard</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>U011</td><td>Brockington Extension</td><td>Central</td><td>60</td><td>Traditional</td><td>OHP, Whiteboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>X401</td><td>Pilkington Library</td><td>West</td><td>60</td><td>Traditional</td><td>Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>S174</td><td>S Building</td><td>West</td><td>53</td><td>Traditional</td><td>OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>A203</td><td>Schofield</td><td>Central</td><td>50</td><td>Traditional</td><td>Projector, OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>D002</td><td>James France</td><td>Central</td><td>50</td><td>Traditional</td><td>Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>D102</td><td>James France</td><td>Central</td><td>50</td><td>Traditional</td><td>OHP, Whiteboard, Chalkboard</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>D109</td><td>James France</td><td>Central</td><td>50</td><td>Traditional</td><td>OHP, Whiteboard, Chalkboard</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>D201</td><td>James France</td><td>Central</td><td>50</td><td>Traditional</td><td>OHP, Whiteboard, Chalkboard</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>D202</td><td>James France</td><td>Central</td><td>50</td><td>Traditional</td><td>OHP, Whiteboard, Chalkboard</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>JJ010</td><td>Ann Packer</td><td>East</td><td>50</td><td>Traditional</td><td>OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>S173</td><td>S Building</td><td>West</td><td>50</td><td>Traditional</td><td>OHP, Chalkboard, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>N004</td><td>Haslegrave</td><td>Central</td><td>50</td><td>Lab</td><td>Projector, Wheelchair Access</td></tr><tr class="resultsRow" id="even"><td>picture</td><td>N006</td><td>Haslegrave</td><td>Central</td><td>60</td><td>Lab</td><td>Projector, Wheelchair Access</td></tr><tr class="resultsRow" id="odd"><td>picture</td><td>N005</td><td>Haslegrave</td><td>Central</td><td>90</td><td>Lab</td><td>Projector, Wheelchair Access</td></tr></tbody>
                            </table>
                        </div>
                        <input type="button" id="room_done" class="std_but" value="Done" style="float:right;"/>
				</div>
			</div>
			<!-- Length, Day, Time -->
			<div id="row4">
				<div id="length_sect">
					<label for="length_select" class="req_label">Length: </label>
					<div class="ui-widget" id="length_input">
						<select id="length_select" class="req_select">
							<option value="1" selected="selected">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<!-- More options here -->
						</select>
					</div>
				</div>
				<div id="daytime_sect" class="mand">
					<!-- needs changing to request page shit -->
					<table id="daytime_sel_table" summary="">
						<colgroup id="days" span="1"></colgroup>
						<thead>
							<tr>
								<th><h1>Day/Period</h1></th>
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
					<label class="req_label">Weeks</label>
					<ul id="week_boxes">
						<li>1<input type="checkbox" name="week" value="1" checked="checked"/></li>
						<li>2<input type="checkbox" name="week" value="2" checked="checked"/></li>
						<li>3<input type="checkbox" name="week" value="3" checked="checked"/></li>
						<li>4<input type="checkbox" name="week" value="4" checked="checked"/></li>
						<li>5<input type="checkbox" name="week" value="5" checked="checked"/></li>
						<li>6<input type="checkbox" name="week" value="6" checked="checked"/></li>
						<li>7<input type="checkbox" name="week" value="7" checked="checked"/></li>
						<li>8<input type="checkbox" name="week" value="8" checked="checked"/></li>
						<li>9<input type="checkbox" name="week" value="9" checked="checked"/></li>
						<li>10<input type="checkbox" name="week" value="10" checked="checked"/></li>
						<li>11<input type="checkbox" name="week" value="11" checked="checked"/></li>
						<li>12<input type="checkbox" name="week" value="12" checked="checked"/></li>
						<li>13<input type="checkbox" name="week" value="13" /></li>
						<li>14<input type="checkbox" name="week" value="14" /></li>
						<li>15<input type="checkbox" name="week" value="15" /></li>
					</ul>
					<div id="week_sel_sect">
					<label class="req_label">Select</label>
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
				<input type="button" id="submit" class="std_but" value="Submit" />
				</div>
			</div>
			

			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>
