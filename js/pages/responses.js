/**
 *	File: js/pages/responses.js
 *  Author: Jono Brogan and Matthew Swift
 *  For: Responses page
 */


var respTypeIn = {};
var initial_load = true;

$(document).ready(function(){
	initial_load = true;
	//iniialate respTypeIn
	$('.type_check').each(function(){
		respTypeIn[$(this).attr('value')] = false;
	});
	//get pending/allocated
	var filter = new Array();
	//get checked boxes
	$('.type_check').each(function(){
		if($(this).is(':checked')) filter.push($(this).attr('value'));
	});
	if(initial_load){
		if (pageArgs["id"] != null && pageArgs["status"] !=null) {
			if(pageArgs["status"] == "failed"){
				filter.push($('#failed_check').val());
				document.getElementById('failed_check').checked = true;
			}
			//$('#e'+pageArgs["id"]).find('.hidden').show();
		}
	}
	getResponses(filter);
	
	$.ajax({
				type: "GET",
				url: "index.php?page=responses",
				data: {"get" : "modules"},
				contentType: "application/json; charset=utf-8",
				dataType: "json",
									
				success: function(data){
					var modules = data;
					$( "#modulesearch" ).autocomplete({
						source: modules,
						minLength: 2,
						select: function(event,ui){
							var module = ui.item.value
							var code = module.substr(0,module.indexOf("-")-1);
							//var title = module.substr(module.indexOf('-')+1);
							$('#scrollablearea').find('.scrollContent').each(function() {
								if($(this).find('.modcodecontent').text() !== code){
									hideAdditional();
									$(this).hide();
								}
							});
						}
					});
					
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});
	
	//Button to clear Search text
	$('#clear').button();
	
	$('#clear').click(function(){
		$('#modulesearch').val("");
		hideAdditional();
		fillTable();
		sort();
	});
	
	// display Edit and Delete text and image toolbar
	$('.scrollContent').live("mouseenter",function(){
		$(this).find('.toolbar').show();
	});
	
	//Hide Edit and Delete text and image toolbar
	$('.scrollContent').live("mouseleave",function(){
		$(this).find('.toolbar').hide();
	});
	
	//on hover underline for edit text
	$('.edit').live("mouseenter",function() {
			$(this).addClass("edit_ul");
	});
	
	//remove underline for edit text
	$('.edit').live("mouseleave",function() {
		$(this).removeClass("edit_ul");
		
	});
	
	//on hover underline for edit text
	$('.decline').live("mouseenter",function() {
			$(this).addClass("decline_ul");
	});
	
	//remove underline for edit text
	$('.decline').live("mouseleave",function() {
		$(this).removeClass("decline_ul");
		
	});
	
	$('.release').live("mouseenter",function() {
			$(this).addClass("release_ul");
	});
	
	//remove underline for edit text
	$('.release').live("mouseleave",function() {
		$(this).removeClass("release_ul");
		
	});
	
	//on hover underline for delete text	
	$('.delete').live("mouseenter",function() {
			$(this).addClass("delete_ul");
	});
	
	//remove underline for delete text	
	$('.delete').live("mouseleave",function() {
			$(this).removeClass("delete_ul");	
	});
	
	//on hover underline for resubmit text	
	$('.resubmit').live("mouseenter",function() {
			$(this).addClass("resubmit_ul");
	});
	
	//remove underline for resubmit text	
	$('.resubmit').live("mouseleave",function() {
			$(this).removeClass("resubmit_ul");	
	});
	
	//Deltes row if 'Delete' text is clicked
	$('.delete, .del_btn').live("click",function(){
		if (confirm("Are you sure you want to CANCEL the Request/Repsonse?")) {
			//get request id
			var id = parseInt($(this).parents('.entity').attr('id').substr(1));
			requestResponseAction(id,"remove","Successfully Cancelled");
		}	
	});

	//Shows Edit confirmation if 'Edit' TEXT is clicked
	$('.edit, .edit_btn').live("click",function() {
		if (confirm("Are you sure you want to EDIT the Request/Repsonse?")) {
			var id = parseInt($(this).parents('.entity').attr('id').substr(1));
			window.location.replace("index.php?page=requests&edit="+id);
		}
	});
	
	$('.decline, .decline_btn').live("click",function(){
		if (confirm("Are you sure you want to DECLINE the Allocation?")) {
			//get request id
			var id = parseInt($(this).parents('.entity').attr('id').substr(1));
			requestResponseAction(id,"decline","Successfully Declined");
		}
	});
	
	$('.release, .release_btn').live("click",function(){
		if (confirm("Are you sure you want to RELEASE the Allocation?")) {
			//get request id
			var id = parseInt($(this).parents('.entity').attr('id').substr(1));
			requestResponseAction(id,"decline","Successfully Released");
		}
	});
	
	$('.resubmit, .resubmit_btn').live("click",function(){
		if (confirm("Are you sure you want to RESUBMIT the Request/Repsonse?")) {
			var id = parseInt($(this).parents('.entity').attr('id').substr(1));
			window.location.replace("index.php?page=requests&repeat="+id);
		}
	});
	
	var hideAdditional = function(){
		$('.hidden').hide();
	};
	
	//When row is clicked, makes corresponding div visible. If already visible, will make invisible. 
	$('.scrollContent').live("click",function() {
		var row_id_num = $(this).attr('id').substr(3);
		$('#scrollablearea').find('.hidden').each(function() {
			var hidden_row_id_num = $(this).attr('id').substr(8);
			if (row_id_num == hidden_row_id_num) {
				var element = "#newboxes" + hidden_row_id_num;
				if ($(element).is(":visible")) {
					$(this).slideUp();
				}
				else {
					$(this).slideDown();
				}
			}
			else {
				var elm = "#newboxes" + hidden_row_id_num;
				$(elm).slideUp();
			}
		});
	});
	
	var desc = new Array(false,false,false,false,false,false,false,false,false,false);
	
	$('.mainheader').click(function() {
		var header_val = $(this).attr('value');
		re_sort_table(header_val);
	});
	
	function re_sort_table(headerval) {
	
		var val_attr = headerval;
		var rowArray = [];
				
		$('#scrollablearea').find('.scrollContent').each(function() {
			
			var id = $(this).attr('id');
			var data = 0;
						
			if (val_attr == 0) {
				var data = $(this).find('.roundcontent').text();
				if(data == "P") data = 0;
				if(data == "A") data = 5;
			}
			else if (val_attr == 1) {
				var data = $(this).find('.modcodecontent').text();
			}
			else if (val_attr == 2) {
				var data = $(this).find('.modtitlecontent').text();
			}
			else if (val_attr == 3) {
				var data = $(this).find('.daycontent').text();
				if(data == "Monday"){ data = 1 ;}
				else if(data == "Tuesday"){ data = 2 ;}
				else if(data == "Wednesday"){ data = 3;}
				else if(data == "Thursday"){ data = 4;}
				else if(data == "Friday"){ data = 5;}
			}
			else if (val_attr == 4) {
				var data = $(this).find('.timecontent').text();
			}
			else if (val_attr == 5) {
				var data = parseInt($(this).find('.lengthcontent').text());
			}
			else if (val_attr == 6) {
				var data = parseInt($(this).find('.numstudentscontent').text());
			}
			else if (val_attr == 7) {
				var data = parseInt($(this).find('.numroomscontent').text());
			}
			else if (val_attr == 8) {
				var data = $(this).find('.roompreferencecontent').text();
			}
			else if(val_attr == 9){
				var data = $(this).find('.roomallocationcontent').text();
			}
			
			rowArray.push({"id":id,"data":data});
						
		});
					
		var swapped;
		do {
			swapped = false;
			for (var i=0; i < rowArray.length-1; i++) {
				if (rowArray[i]["data"] > rowArray[i+1]["data"]) {
					var temp = rowArray[i];
					rowArray[i] = rowArray[i+1];
					rowArray[i+1] = temp;
					swapped = true;
				}
			}
		} while (swapped);
		
		
		var p_rowArray = new Array();
		for(var op=0;op<rowArray.length;op++){
			var id = "#"+rowArray[op]['id'];
			p_rowArray.push($(id).parent());
		}
		
		if(desc[val_attr]){
			p_rowArray.reverse()
			desc[val_attr] = false;
		}else{desc[val_attr] = true;}
		
		$('#scrollablearea').empty();
		for(var q=0;q<p_rowArray.length;q++){
			$('#scrollablearea').append(p_rowArray[q]);
		}

	}
	
	$('#top :checkbox').change(function(){
		hideAdditional(); //close open addtional info
		
		//need to get all if all boxes deselected!!
		if($(this).hasClass('type_check')){
			var val = $(this).attr('value');
			if($(this).is(':checked') && !respTypeIn[val]){ getResponses([val]); }
			//if all boxes deselected and they are not loaded
			var count = 0;
			var valarr = new Array();
			$('.type_check').each(function(){
				if(!$(this).is(':checked')) count++;
				var v = $(this).attr('value');
				if(!respTypeIn[v]) valarr.push(v);
			});
			if((count == 5) && (valarr.length != 0)){
				getResponses(valarr);
			}
		}
		sort();
		//moved sort into getResponses - TODO
	});
		
});

var sort = function(){
		$('#scrollablearea').find('.scrollContent').each(function() {
			var rShow = false;
			var pShow = false;
			var dShow = false;
			var tShow = false;
			
			var round = $(this).find('.roundcontent').text().toLowerCase();
			var part = $(this).find('.modcodecontent').text().substr(2, 1);
			var day = $(this).find('.daycontent').attr('name');
			var type = $(this).attr('value');
			
			//get checked rounds
			var checked_rounds = new Array();
			$('.round_check').each(function(){
				if($(this).is(':checked')) checked_rounds.push($(this).attr('value'));
			});
			if(checked_rounds.length == 0) rShow = true;
			for(var r=0;r<checked_rounds.length;r++){
				if(round == checked_rounds[r]){
					rShow = true;
					break;
				}
			}
			//get checked parts
			var checked_parts = new Array();
			$('.part_check').each(function(){
				if($(this).is(":checked")) checked_parts.push($(this).attr('value'));
			});
			if(checked_parts.length == 0) pShow = true;
			for(var p=0;p<checked_parts.length;p++){
				if(part == checked_parts[p]){
					pShow = true;
					break;
				}
			}
			//get checked days
			var checked_days = new Array();
			$('.day_check').each(function(){
				if($(this).is(":checked")) checked_days.push($(this).attr('value'));
			});
			if(checked_days.length == 0) dShow = true;
			for(var d=0;d<checked_days.length;d++){
				if(day == checked_days[d]){
					dShow = true;
					break;
				}
			}
			//get checked status
			var checked_statuses = new Array();
			$('.type_check').each(function(){
				if($(this).is(':checked')) checked_statuses.push($(this).attr('value'));
			});
			if(checked_statuses.length == 0) tShow = true;
			for(var t=0;t<checked_statuses.length;t++){
				if(type == checked_statuses[t]){
					tShow = true;
					break;
				}
			}
			
			if(rShow && pShow && dShow && tShow){
				$(this).parent().show();
			}else{
				$(this).parent().hide();
			}
			
		});
		if(initial_load){
			if (pageArgs["id"] != null && pageArgs["status"] !=null) {
				$('#e'+pageArgs["id"]).find('.hidden').show();
				 var rowpos = $('#e'+pageArgs["id"]).position();
				 $('#scrollablearea').scrollTop(rowpos.top);
				 $('html,body').animate({ scrollTop: $("#scrollablearea").offset().top }, { duration: 'slow', easing: 'swing'});
			}
			initial_load = false;
		}
};

var requestResponseAction = function(id,action,message){
	$.ajax({
		type: "GET",
		url: "index.php?page=responses",
		data: {"get" : action, "id" : id},
		contentType: "application/json; charset=utf-8",
		dataType: "json",
							
		success: function(data){
			$("#e"+id).remove();
			makePopup(message);
		},
							
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("error: "+textStatus+" ("+errorThrown+ ")");
		}					
	});
};

var getResponses = function(types){
	$("#loadingOverlay").overlay({ 
		top: 400,
		mask: {
			color: '#fff',
			loadSpeed: 200,
			opacity: 0.5
		},
		closeOnClick: false
	});
	$("#loadingOverlay").overlay().load();
	
			$.ajax({
				type: "GET",
				url: "index.php?page=responses",
				data: {"get" : "update","types": "["+types+"]"},
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				//async: false,
									
				success: function(data){
					updateResponses(data);
					fillTable();
					updateRespTypeIn(types);
					$("#loadingOverlay").overlay().close();
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});
		//updateRespTypeIn(types); //WRONG;
};
	
var response = new Array(); 

var updateResponses = function(data){
	
	for(var i=0;i<data.length;i++){
		response.push(data[i]);
	}
	//console.log(dump(response));
	
};

var updateRespTypeIn = function(array){
	for(var i =0; i<array.length;i++){
		respTypeIn[array[i]] = true;
	}
};
	
var fillTable = function(){
	//empty table
	$('#scrollablearea').empty();
	//fill table
	for(var i=0;i<response.length;i++){
		
		var row = "<div class='entity' id='e"+response[i]["requestId"]+"'><div id='row"+i+"' class='scrollContent "+response[i]["status_code"]+"' value='"+response[i]["status_id"]+"'>";
		if(response[i]["priority"]){
			row += "<div class='priority'><img src='images/plogogrey.png' id='tool2' height='35' width='35' /></div>";
		}
		row += "<div class='roundcontent'>"+response[i]["round"]+"</div>";
		row += "<div class='modcodecontent'>"+response[i]["module_code"]+"</div>";
		row += "<div class='modtitlecontent'>"+response[i]["module_title"]+"</div>";
		var day;
		switch(parseInt(response[i]["day"])){
			case 1: day="Monday"; break;
			case 2: day="Tuesday"; break;
			case 3: day="Wednesday"; break;
			case 4: day="Thursday"; break;
			case 5: day="Friday"; break;
		}
		row += "<div class='daycontent' name='"+response[i]["day"]+"' >"+day+"</div>";
		var period = parseInt(response[i]["time"])+8;
		row +="<div class='timecontent'>"+period+":00</div>";
		row +="<div class='lengthcontent'>"+response[i]["length"]+"</div>";
		row +="<div class='numstudentscontent'>"+response[i]["numStudents"]+"</div>";
		row +="<div class='numroomscontent'>"+response[i]["numRooms"]+"</div>";
		row +="<div class='roompreferencecontent'>";
		for(var p=0;p<response[i]["roomPrefs"].length;p++){
			row += response[i]["roomPrefs"][p]+" ";
		}
		row += "</div>";
		row += "<div class='roomallocationcontent'>";
		for(var a=0;a<response[i]["roomAllocations"].length;a++){
			row += response[i]["roomAllocations"][a]+" ";
		}
		row += "</div>";
		row += "<div class='toolbar'>";
		if(response[i]["status_code"] == "Pending"){
			row += "<span class='edit'>Edit </span><img src='images/tools.png' class='edit_btn' height='20' width='20' />";
			row += "<span class='delete'>Cancel </span><img src='images/delete.png' class='del_btn' id='tool2' height='20' width='20' />";
		}else if(response[i]["status_code"] == "Allocated"){
			row += "<span class='release'>Release </span><img src='images/delete.png' class='release_btn' height='20' width='20' />";
		}else if(response[i]["status_code"] == "Reallocated"){
			row += "<span class='decline'>Decline </span><img src='images/delete.png' class='decline_btn' height='20' width='20' />";
		}else if(response[i]["status_code"] == "Declined" || response[i]["status_code"] == "Failed"){
			row += "<span class='resubmit'>Resubmit </span><img src='images/repeat.png' class='resubmit_btn' height='20' width='20' />";
		}
		row += "</div></div>";
		row += "<div class='hidden' name='newboxes' id='newboxes"+i+"'> ";
		row += "<div class='info1'><br><br>";
		row += "<span class='title'>Park:</span><p class='normal'> "+response[i]["park"]+"</p><br><br>";
		row += "<span class='title'>Room Type:</span><p class='normal'> "+response[i]["roomtype"]+"</p><br><br>";
		row += "<span class='title'>Weeks:</span><p class='normal'> ";
		for(var w=0;w<response[i]["weeks"].length;w++){
			if(response[i]["weeks"][w]){
				row += w+1+",";
			}
		}
		row = row.substring(0,row.lastIndexOf(',')); //remove last comma
		row+="</p><br></div>";
		row += "<div class='info2'><br>";
		row += "<span class='title'><center>Facilities</center></span><div class='facilities'><ul>";
		for(var f= 0;f<response[i]["facilities"].length;f++){
			row += "<li>"+response[i]["facilities"][f]+"</li>";
		}
		row += "</ul></div><br></div>";
		row += "<div class='info3'><br> <span class='title'><center>Special Requirements</center></span><br><div class='requirementstextarea'><form>";
		row += "<textarea name='specialrequirements' cols='25' rows='6' readonly>"+response[i]["specReq"]+"</textarea>";
		row += "</form></div></div></div></div>";
		$('#scrollablearea').append(row);
	}
	sort();

};

