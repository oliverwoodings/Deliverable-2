$(document).ready(function() {

	//Finds the number of rows in the table.
	var row_count = -1;
	$('.row_class').each(function() {
		row_count = row_count +1;
	});
	
	//On load Loops through each row and empties their contents
	for (i = 0; i < row_count; i++) {
		$('.txt_box_'+i).each(function() {
			$(this).empty();
		});
	}

	//Appends a round number to the round number display div
	var round_num = 1;
	//$('<h1 class="und_line">Current Round: '+round_num+'</h1>').appendTo('#round_display');
	
	//on click of any 'Allocate Room' button, gets row id, gets any text in the rows textbox. If the rows textbox is blank, will 'allocate' the same room as the room preference. If text found in the relevant textbox, will allocate this room to the request.
	$('.allocate_room').live("click",function() {
		//get id
		var row_id = $(this).parent().parent().parent().attr('id');
		//get rooms
		var rooms = new Array();
		$(this).parent().parent().parent().find('.rooms_box').each(function(){
			rooms.push('"'+$(this).val()+'"');
		});
		//get period
		var period = parseInt($(this).parent().parent().parent().find('.period').text());
		
		$.ajax({
				type: "GET",
				url: "index.php?page=admin",
				data: {"get" : "allocate", "id": row_id, "rooms": "["+rooms+"]", "period": period},
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				//async: false,
									
				success: function(data){
					//remove row from table
					$('#'+row_id).remove();
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});		
	});
	
	//If any 'Fail Request' button is clicked, asks the user if they really want to fail the request. If 'Yes', will remove request from table. If 'No', nothing will happen.
	$('.fail_req').live("click",function() {
		var row_id = $(this).parent().parent().parent().attr('id');
		$.ajax({
				type: "GET",
				url: "index.php?page=admin",
				data: {"get" : "fail", "id": row_id},
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				//async: false,
									
				success: function(data){
					//remove row from table
					$('#'+row_id).remove();
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});
	});
	
	//When the 'next_round' is clicked, will increase the number, empty the 'round number display' div and append the new round numebr to the same div.
	$('#next_round').click(function() {
		/*round_num = round_num + 1;
		$('#round_display').empty();
		//('<h1 class="und_line">Current Round: '+round_num+'</h1>').appendTo('#round_display');
		alert("You changed the Round!\nYou are now in Round: "+round_num);
		alert("Time Table System Updated!!");*/
	});
	
	var sem_num = 1;
	
	$('#next_semester').click(function() {
		/*sem_num = sem_num + 1;
		if (sem_num > 2) {
			alert("You are already in Semester 2!!");
		}
		else {
			var answer = confirm("Are you sure you want to ADVANCE to the NEXT SEMESTER??");
			if (answer) {
				alert("You have advanced from Semester 1 to SEMESTER 2!!!")
			}
			else {
				alert("You have NOT ADVANCED TO THE NEXT SEMESTER!!");
			}
		}*/
		
	});
	
	$('#next_round').mouseenter(function() {
		$(this).addClass('hover');
	});
	
	$('#next_round').mouseleave(function() {
		$(this).removeClass(' hover');
	});
	
	$('#next_semester').mouseenter(function() {
		$(this).addClass('hover');
	});
	
	$('#next_semester').mouseleave(function() {
		$(this).removeClass(' hover');
	});
	
	updateTable();
});

var updateTable = function(){
	$.ajax({
				type: "GET",
				url: "index.php?page=admin",
				data: {"get" : "update" },
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				//async: false,
									
				success: function(data){
					//build table structure
					for(var i=0;i < data.length; i++){
					var row = '<tr class="row_class" id="'+data[i]['requestId']+'">';
					row += '<td><p>'+data[i]['priority']+'</p></td>';
					row += '<td><p>'+data[i]['module_code']+'</p></td>';
					row += '<td class="mod_title"><p>'+data[i]['module_title']+'</p></td>';
					row += '<td class="day"><p>'+data[i]['day']+'</p></td>';
					row +='<td class="period">'+data[i]['time']+'</td>';
					row += '<td><p>'+data[i]['length']+'</p></td>';
					row += '<td><p>'+data[i]['numStudents']+'</p></td>';
					row += '<td><p>'+data[i]['numRooms']+'</p></td>';
					row += '<td class="room_pref">';
					for(var p=0;p<data[i]["roomPrefs"].length;p++){
						row += data[i]["roomPrefs"][p]+" ";
					}
					row += '</td>';
					for(var n=0;n<data[i]['numRooms'];n++){
						row += '<td class="row_w"><input style="text-transform: uppercase;width:50px;" type="text" class="rooms_box"></input></td>';
					}
					row += '<td class="buttns">';
					row += '<center><input type="button" class="allocate_room" value="Allocate" /></center>';
					row += '<center><input type="button" class="fail_req" value="Fail" /></center>';
					row += '</td></tr>';
					$('#tbl_body').append(row);
				}
				
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});
};
