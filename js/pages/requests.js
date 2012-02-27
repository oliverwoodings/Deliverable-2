var roomTable;

$(document).ready(function(){
	
	// Module expand
	$("#module_input").click(function() {
		$('#mod_expand').slideDown('slow', function() {
    		// Animation complete.
  		});
	});
	
	/* Testing mouse position for module */
	var mouse_in_module = false;
	
	$('#module_input, #mod_expand').hover(function(){ 
        mouse_in_module=true; 
    }, function(){ 
        mouse_in_module=false; 
    });
    
    $("body").mouseup(function(){ 
        if(! mouse_in_module){
        	$('#mod_expand').slideUp('slow', function(){
			// Animation complete.
			});	
        }
    });
    
    //Room expand
    $("#roompref_input").click(function() {
		$('#roompref_expand').slideDown('slow', function() {
			roomTable.fnAdjustColumnSizing();
  		});
	});
	$("#room_done").click(function() {
		$('#roompref_expand').slideUp('slow', function() {
    		// Animation complete.
  		});
	});

	
	/*Day-Time selection */
	var dt_select = false;
	
	$('.td_content').hover(function(){
		//mousein
		//check none selected - don't apply no more
		$(this).parents('#contentt').find('.td_content').each(function(){
			if($(this).hasClass('selectedtd')){
				dt_select = true;
				return;
			}
		});
		//if(!dt_select){
			//get lenght
			var length = $('#length_select option:selected').val();
			var this_id = parseInt($(this).attr('id').substr(4));
			var row = parseInt(this_id/10);
			var limit = (row*10)+9-length;
			//check if blocked
			var blocked = false;
			for(var i = 0;i<length;i++){
				var cell = "#cell"+this_id;
					if($(cell).hasClass("blocked")){
						blocked = true;
						break;
					}
					this_id++;
			}
			//reset the elements id
			this_id = parseInt($(this).attr('id').substr(4));
			if(!blocked){
				//show selection
				if(this_id<=limit){
					for(var i = 0;i<length;i++){
						var cell = "#cell"+this_id;
							$(cell).addClass("hovertd");
							this_id++;
					}
				}
			}
		//}
	},function(){
		//mouseout
		var length = $('#length_select option:selected').val();
		var this_id = parseInt($(this).attr('id').substr(4));
		//show selection
		for(var i = 0;i<length;i++){
			var cell = "#cell"+this_id;
			$(cell).removeClass("hovertd");
			this_id++;
		}
	});
	
	$('.td_content').click(function(){
		var length = $('#length_select option:selected').val();
		var this_id = parseInt($(this).attr('id').substr(4));
		var row = parseInt(this_id/10);
		var limit = (row*10)+9-length;
		//check if blocked
		var blocked = false;
		for(var i = 0;i<length;i++){
			var cell = "#cell"+this_id;
				if($(cell).hasClass("blocked")){
					blocked = true;
					break;
				}
				this_id++;
		}
		this_id = parseInt($(this).attr('id').substr(4));
		if(!blocked){
			if(this_id<=limit){
			$(this).parents('#contentt').find('.td_content').each(function(){
				if($(this).hasClass('selectedtd')){
					$(this).removeClass('selectedtd');
					dt_select = false;
				}
			});
			$(".hovertd").addClass('selectedtd');
			}
			$('#length_sect').removeClass('mand');
			$('#length_sect').addClass('completed');
		}
	});
	
	
	/* Week slection */
	// All weeks selected
	$('#week_all').click(function(){
		$('#week_boxes li').each(function(index) {
    		//alert(index + ': ' + $(this).text());
    		$(this).find('input:checkbox').prop("checked", true);
		});
	});
	// No weeks selected
	$('#week_none').click(function(){
		$('#week_boxes li').each(function(index) {
    		//alert(index + ': ' + $(this).text());
    		$(this).find('input:checkbox').prop("checked", false);
		});
	});
	
	//1- 12 weeks selected
	$('#week_term').click(function(){
		$('#week_boxes li').each(function(index) {
			if(index < 12)
    			$(this).find('input:checkbox').prop("checked", true);
    		else
    			$(this).find('input:checkbox').prop("checked", false);
		});
	});
	// odd weeks selected 
	$('#week_odd').click(function(){
		$('#week_boxes li').each(function(index) {
			if(index%2 == 0)
    			$(this).find('input:checkbox').prop("checked", true);
    		else
    			$(this).find('input:checkbox').prop("checked", false);
		});
	});
	
	// even weeks selected
	$('#week_even').click(function(){
		$('#week_boxes li').each(function(index) {
			if(index%2 !== 0)
    			$(this).find('input:checkbox').prop("checked", true);
    		else
    			$(this).find('input:checkbox').prop("checked", false);
		});
	});
	
	/* Jquery UI */
	
	/* Buttons */
	$('.std_but').button();
	
	/* Combobox's */
	$('#park_select').combobox();
	$('#park_input > input.ui-autocomplete-input').css('width', '90px');
	$('#noStud_select').combobox();
	$('#noStud_input > input.ui-autocomplete-input').css('width', '50px');
	$("#roomty_select").combobox();
	$('#room_type_input > input.ui-autocomplete-input').css('width', '90px');
	$("#length_select").combobox();
	$('#length_input > input.ui-autocomplete-input').css('width', '90px');
	$("#search_by_select").combobox();
	$('#search_by_input > input.ui-autocomplete-input').css('width', '100px');
	
	roomTable = $('#rooms').dataTable( {
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"bAutoWidth": true,
		"bDeferRender": true,
		"sScrollY": "240px",
		"sDom": '<"H"Tfr>t<"F"i>S',
		"bPaginate": false
	} );
	
	
	$('#modline .deletebutton').live('click',function(){ 
		$(this).parent().remove();
		$('#module_input').show();
		$('#module_sect').addClass('mand');
		$('#module_sect').removeClass('completed');
		updatePageData();
	});
	$('#roompref_input .deletebutton').live('click', function() {
		$(this).parent().remove();
		updatePageData();
	});

    $("#submit").click(function() { submitRequest(); });
	
	//Clear form
	$("#clear").click(function() {
		$("#park_select").empty();
		$("#roomty_select").empty();
		$("#noStud_select").empty();
		$("#priorty_check").attr('checked', false);
		$("#1_check").attr('checked', false);
		$("#2_check").attr('checked', false);
		$("#3_check").attr('checked', false);
		$("#4_check").attr('checked', false);
		$("#5_check").attr('checked', false);
		$('#week_boxes li').each(function(index) {
			if(index < 12)
    			$(this).find('input:checkbox').prop("checked", true);
    		else
    			$(this).find('input:checkbox').prop("checked", false);
		});
		$("#spec_input").val("");
		$(".selectedtd").removeClass("selectedtd");
		$(".completed").removeClass("completed").addClass("mand");
		$("#module_id").remove();
		$('#module_input').show();
		$("#roompref_input .tag").remove();
		updatePageData();
	});
	
	$("#loadingOverlay").overlay({ 
		top: 400,
		mask: {
			color: '#fff',
			loadSpeed: 200,
			opacity: 0.5
		},
		closeOnClick: false
	});
	
	$("#module_input").keyup(function() {
		$("#mod_expand .mod_elm").css('display', 'block');
		$("#mod_expand .mod_elm p").each(function() {
			if ($(this).html().toLowerCase().indexOf($("#module_input").html().replace(/(<([^>]+)>)/ig,"").toLowerCase()) == -1) $(this).parent().css('display', 'none');
		});
	});

	
	//Repeat request
	if (pageArgs["repeat"] != null) {
		loadRepeatData(pageArgs["repeat"]);
	}
	//Edit request
	else if (pageArgs["edit"] != null) {
		loadEditData(pageArgs["edit"]);
	}
	//Add rooms
	else if (pageArgs["arooms"] != null) {
		var rms = pageArgs["arooms"].split(",");
		for (var i = 0; i < rms.length; i++) {
			$('#roompref_input').append('<div class="tag" contenteditable="false"><r>'+rms[i]+'</r><a class="deletebutton moddlt"></a></div>');
		}
		updatePageData();
	}
	//Normal
	else {
		updatePageData();
	}
	
});

function loadEditData(id) {
	$("#loadingOverlay").overlay().load();
	$.get(
		"index.php?page=requests&get=loadid&id=" + id,
		function (data) {
		    if (data == null || data.error != null) {
				updatePageData();
                return;
            }
			$('#module_input').hide();
			$('#mod_expand').hide();
			$("#module_sect").addClass("completed");
			$("#module_sect").removeClass("mand");
			$('#modline').append('<div id="module_id" class="tag" contenteditable="false"><r id="' + data.module.id + '">'+data.module.code + ' - ' + data.module.name +'</r><a class="deletebutton moddlt"></a></div>');
			if (data.priority == "1") $("#priority_check").attr('checked', true);
			if (data.park != null) {
				$("#park_select").append('<option selected="selected" value="' + data.park.id + '">' + data.park.name + '</option>');
				$("#park_input input").val(data.park.name);
			}
			if (data.roomType != null) {
				$("#roomty_select").append('<option selected="selected" value="' + data.roomType.id + '">' + data.roomType.name + '</option>');
				$("#room_type_input input").val(data.roomType.name);
			}
			$("#noRooms_input").val(data.numRooms);
			if (data.rooms != null) {
				for (var i = 0; i < data.rooms.length; i++) {
					$('#roompref_input').append('<div class="tag" contenteditable="false"><r>'+data.rooms[i].code+'</r><a class="deletebutton moddlt"></a></div>');
				}
			}
			$("#noStud_select").append('<option selected="selected" value="' + data.numStudents + '">' + data.numStudents + '</option>');
			$("#noStud_input input").val(data.numStudents);
			if (data.facilities != null) {
				for (var i = 0; i < data.facilities.length; i++) $("#" + data.facilities[i] + "_check").attr("checked", true);
			}
			for (var i = 0; i < data.weeks.length; i++) {
				if (data.weeks[i]) $("#wkcheck_" + i).attr('checked', true);
				else $("#wkcheck_" + i).attr('checked', false);
			}
			$("#length_select option").attr("selected", false);
			$("#length_select option:nth-child(" + data.length + ")").attr("selected", "selected");
			$("#length_input input").val(data.length);
			for (var i = 0; i < data.length; i++) {
				var day = parseInt(data.day) -1;
				$("#cell" + day.toString().replace("0", "") + "" + (parseInt(data.period) + i -1)).addClass("selectedtd");
			}
			$("#length_sect").removeClass("mand").addClass("completed");
			updatePageData();
		},
		"json");
}

function loadRepeatData(id) {
	$("#loadingOverlay").overlay().load();
	$.get(
		"index.php?page=requests&get=loadid&id=" + id,
		function (data) {
		    if (data == null || data.error != null) {
				updatePageData();
                return;
            }
			$('#module_input').hide();
			$('#mod_expand').hide();
			$("#module_sect").addClass("completed");
			$("#module_sect").removeClass("mand");
			$('#modline').append('<div id="module_id" class="tag" contenteditable="false"><r id="' + data.module.id + '">'+data.module.code + ' - ' + data.module.name +'</r><a class="deletebutton moddlt"></a></div>');
			if (data.priority == "1") $("#priority_check").attr('checked', true);
			if (data.park != null) {
				$("#park_select").append('<option selected="selected" value="' + data.park.id + '">' + data.park.name + '</option>');
				$("#park_input input").val(data.park.name);
			}
			if (data.roomType != null) {
				$("#roomty_select").append('<option selected="selected" value="' + data.roomType.id + '">' + data.roomType.name + '</option>');
				$("#room_type_input input").val(data.roomType.name);
			}
			$("#noRooms_input").val(data.numRooms);
			if (data.rooms != null) {
				for (var i = 0; i < data.rooms.length; i++) {
					$('#roompref_input').append('<div class="tag" contenteditable="false"><r>'+data.rooms[i].code+'</r><a class="deletebutton moddlt"></a></div>');
				}
			}
			$("#noStud_select").append('<option selected="selected" value="' + data.numStudents + '">' + data.numStudents + '</option>');
			$("#noStud_input input").val(data.numStudents);
			if (data.facilities != null) {
				for (var i = 0; i < data.facilities.length; i++) $("#" + data.facilities[i] + "_check").attr("checked", true);
			}
			for (var i = 0; i < data.weeks.length; i++) {
				if (data.weeks[i]) $("#wkcheck_" + i).attr('checked', true);
				else $("#wkcheck_" + i).attr('checked', false);
			}
			$("#length_select option").attr("selected", false);
			$("#length_select option:nth-child(" + data.length + ")").attr("selected", "selected");
			$("#length_input input").val(data.length);
			updatePageData();
		},
		"json");
		

}

function updatePageData() {

	$("#loadingOverlay").overlay().load();
	
    var params = "";
    if ($('#module_id r').length > 0 && $('#module_id r').attr('id').length > 0) params += "&module_id=" + $('#module_id r').attr('id')
	if ($("#roompref_input r").length != 0) {
		var rooms = new Array();
		$("#roompref_input r").each(function() {
			rooms[rooms.length] = '"' + $(this).html() + '"';
		});
		params += "&rooms=[" + rooms.join(",") + "]";
	}
    if ($("#roomty_select option:selected").val() != undefined && $("#roomty_select option:selected").val() != 0) params += "&room_type=" + $("#roomty_select option:selected").val();
    var facilities = new Array();
    $('.fac_option :checked').each(function(){
        facilities.push($(this).attr('id').substr(0,1));
    });
    if (facilities.length > 0) params += "&facilities=[" + facilities.join(",") + "]";
	var weeks = new Array();
	for (var i = 1; i < 16; i++) {
		if ($('#wkcheck_' + i).prop("checked")) weeks[weeks.length] = i;
	}
	params += "&weeks=[" + weeks.join(",") + "]";
    if ($("#noStud_select option:selected").val() != undefined && $("#noStud_select option:selected").val() != 0) params += "&num_students=" + $("#noStud_select option:selected").val();
    if ($("#park_select option:selected").val() != undefined && $("#park_select option:selected").val() != 0) params += "&park=" + $("#park_select option:selected").val();
    params += "&length=" + $('#length_select option:selected').val();
	if ($("#noRooms_input").val().length > 0) params += "&num_rooms=" + $("#noRooms_input").val();
	else params += "&=num_rooms=1";
	
    $.get(
        "index.php?page=requests&get=update" + params,
        function(data) {
            //If error
            if (data != null && data.error != null) {
                alert(data.error);
                return;
            }
            
			//Modules
			$("#mod_expand").empty();
			for (var i = 0; i < data.modules.length; i++) {
				$("#mod_expand").append('<div id="' + data.modules[i].id + '" class="mod_elm" ><p>' + data.modules[i].code + ' - ' + data.modules[i].name + '</p></div>');
			}
			//Parks
			$("#park_select").empty();
			$("#park_select").append('<option value="0" selected="selected">Any</option>');
			for (var i = 0; i < data.parks.length; i++) {
				$("#park_select").append('<option value="' + data.parks[i].id + '">' + data.parks[i].name + '</option>');
			}
			if (data.parks.length == 1) {
				$("#park_input input").val(data.parks[0].name);
				$("#park_select option:nth-child(2)").attr('selected', 'selected');
			} else {
				$("#park_input input").val("Any");
				$("#park_select option:nth-child(1)").attr('selected', 'selected');
			}
			//Room types
			$("#roomty_select").empty();
			$("#roomty_select").append('<option value="0" selected="selected">Any</option>');
			for (var i = 0; i < data.roomTypes.length; i++) {
				$("#roomty_select").append('<option value="' + data.roomTypes[i].id + '">' + data.roomTypes[i].name + '</option>');
			}
			if (data.roomTypes.length == 1) {
				$("#room_type_input input").val(data.roomTypes[0].name);
				$("#roomty_select option:nth-child(2)").attr('selected', 'selected');
			} else {
				$("#room_type_input input").val("Any");
				$("#roomty_select option:nth-child(1)").attr('selected', 'selected');
			}
			//Capacity
			$("#noStud_select").empty();
			$("#noStud_select").append('<option value="10">10</option>');
			for (var i = 25; i <= data.capacity; i += 25) {
				$("#noStud_select").append('<option value="' + i + '">' + i + '</option>');
			}
			if (data.numStudents > 0) {
				$("#noStud_input input").val(data.numStudents);
				$("#noStud_select option").each(function() { if ($(this).val() == data.numStudents) $(this).attr('selected', 'selected'); });
			} else {
				$("#noStud_input input").val(10);
				$("#noStud_select option:nth-child(1)").attr('selected', 'selected');
			}
			
			//Facilities
			for (var i = 1; i <= 5; i++) {
				if (data.facilities[i] == null) {
					$("#" + i + "_check").attr("checked", false);
					$("#" + i + "_check").attr("disabled", true);
				}
				else {
					$("#" + i + "_check").attr("disabled", false);
				}
			}
			//Rooms
			roomTable.fnClearTable();
			for (var i = 0; i < data.rooms.length; i++) {
				if ($("#roompref_input r").length != 0) {
					var cont = false;
					$("#roompref_input r").each(function() {
						if (data.rooms[i].code.toLowerCase() == $(this).html().toLowerCase()) cont = true;
					});
					if (cont) continue;
				}
				var room = data.rooms[i];
				var facArr = new Array();
				for (var j = 0; j < room.facilities.length; j++) {
					facArr[j] = room.facilities[j].name;
				}
				var img = '<div class="picture" style="background-image: url(http://www.lboro.ac.uk/service/fm/services/ts/roompics/'+room.code+'.jpg)"><br /></div>';
				roomTable.fnAddData(new Array(img, room.code, room.building.name, room.building.park.name, room.capacity, room.type.name, facArr.join(", ")));
			}
			if ($("#roompref_input r").length != 0) {
				$("#roompref_input r").each(function() {
					var found = false;
					for (var i = 0; i < data.rooms.length; i++) {
						if (data.rooms[i].code.toLowerCase() == $(this).html().toLowerCase()) found = true;
					}
					if (!found) $(this).parent().remove();
				});
			}
			//Time
			$(".blocked").removeClass("blocked");
			for (var i = 0; i < 5; i++) {
				for (var j = 0; j < 9; j++) {
					if (data.time[i][j] == false) {
						$("#cell" + i.toString().replace("0", "") + "" + j).addClass("blocked");
						if ($("#cell" + i.toString().replace("0", "") + "" + j).hasClass("selectedtd"))
							$(".selectedtd").removeClass("selectedtd");
					}
				}
			}
			
			applyBinds();
			
			$("#loadingOverlay").overlay().close();
			
            
        },
        "json"
    );

}

function submitRequest() {

	if ($("#module_sect").hasClass("mand")) {
		$('html,body').animate({ scrollTop: $("#module_sect").offset().top }, { duration: 'slow', easing: 'swing'});
		makePopup("You must select a module!");
		return;
	}
	if ($("#length_sect").hasClass("mand")) {
		$('html,body').animate({ scrollTop: $("#length_sect").offset().top }, { duration: 'slow', easing: 'swing'});
		makePopup("You must select a time!");
		return;
	}

	$("#loadingOverlay").overlay().load();

	var params = "";
    if ($('#module_id r').length > 0 && $('#module_id r').attr('id').length > 0) params += "&module_id=" + $('#module_id r').attr('id');
	if ($("#roompref_input r").length != 0) {
		var rooms = new Array();
		$("#roompref_input r").each(function() {
			rooms[rooms.length] = '"' + $(this).html() + '"';
		});
		params += "&rooms=[" + rooms.join(",") + "]";
	}
    if ($("#roomty_select option:selected").val() != undefined && $("#roomty_select option:selected").val() != 0) params += "&room_type=" + $("#roomty_select option:selected").val();
    var facilities = new Array();
    $('.fac_option :checked').each(function(){
        facilities.push($(this).attr('id').substr(0,1));
    });
    if (facilities.length > 0) params += "&facilities=[" + facilities.join(",") + "]";
    if ($("#noStud_select option:selected").val() != undefined && $("#noStud_select option:selected").val() != 0) params += "&num_students=" + $("#noStud_select option:selected").val();
    if ($("#park_select option:selected").val() != undefined && $("#park_select option:selected").val() != 0) params += "&park=" + $("#park_select option:selected").val();
    params += "&length=" + $('#length_select option:selected').val();
	if ($("#contentt").find(".selectedtd").length > 0) {
		var num = parseInt($("#contentt .selectedtd:nth-child(1)").attr('id').substr(4));
		if (num < 10) {
			params += "&day=" + 1;
			params += "&period=" + (num + 1);
		} else {
			params += "&day=" + (parseInt(num.toString().substr(0, 1)) + 1);
			params += "&period=" + (parseInt(num.toString().substr(1,2)) + 1);
		}
	}
	var weeks = new Array();
	for (var i = 1; i < 16; i++) {
		if ($('#wkcheck_' + i).prop("checked")) weeks[weeks.length] = i;
	}
	params += "&weeks=[" + weeks.join(",") + "]";
	if ($("#noRooms_input").val().length == 0) params += "&num_rooms=" + 1;
	else params += "&num_rooms=" + $("#noRooms_input").val();
	params += "&priority=" + ($("#priority_check").attr("checked") == "checked"?true:false);
	params += "&spec_req=" + $("#spec_input").val();
	
	//If editing
	if (pageArgs["edit"] != null) params += "&editid=" + pageArgs["edit"];
	
    $.get(
        "index.php?page=requests&get=submit" + params,
        function(data) {
            //If error
            if (data != null && data.error != null) {
                alert(data.error);
                return;
            }
			$(".selectedtd").removeClass("selectedtd");
			$('#length_sect').addClass('mand');
			$('#length_sect').removeClass('completed');
			alert('Request Submitted');
			$("#loadingOverlay").overlay().close();
			updatePageData();
		},
		"json"
	);
	

}


function applyBinds() {

	$('.mod_elm').bind('click',function(){
		$('#module_input').hide();
		$('#mod_expand').hide();
		var module = $(this).find('p').text();
		$('#modline').append('<div id="module_id" class="tag" contenteditable="false"><r id="' + $(this).attr('id') + '">'+module+'</r><a class="deletebutton moddlt"></a></div>');
		$('#module_sect').removeClass('mand');
		$('#module_sect').addClass('completed');
		updatePageData();
	});
	
	$("#rooms tbody tr").click(function() {
        $(this).find('td').effect("highlight", { color: "#BCADCA" }, 1000);
		setTimeout("roomTable.fnDeleteRow(" + roomTable.fnGetPosition(this) + ")", 1000);
		var data = roomTable.fnGetData(roomTable.fnGetPosition(this));
		$('#roompref_input').append('<div class="tag" contenteditable="false"><r>'+data[1]+'</r><a class="deletebutton moddlt"></a></div>');
		updatePageData();
	});
	
	$(".fac_option input").click(function() { updatePageData(); });
	
	$(".ui-autocomplete").click(function() { setTimeout("updatePageData();", 200); });

}
