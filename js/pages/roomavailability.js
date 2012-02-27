/**
 *	File: js/pages/rooomavailability.js
 *  Author: Jono Brogan, Alex Shea and Oliver Woodings
 *  For: Room availability
 */

var tabState = 0;
var day;
var period;
var roomTable;

function drawTable() {
	
    //Clear table
    $(".td_content").removeAttr('href');
	$(".td_content").removeClass('tableCell_red');
	$(".td_content").removeClass('tableCell_blue');
	$(".td_content").removeClass('tableCell_yellow');
	
	matches = searchRooms();
	
	//Define storage arrays
	var bkAmnt = [[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0]];
	var avAmnt = [[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0,0]];
	for (i = 0; i < 5; i++) {
		for (k = 0; k < 9; k++) {
			avAmnt[i][k] = matches.length;
		}
	}
	
	//Loop through bookings filling out amount arrays
	for (var i = 0; i < bookings.length; i++) {
		booking = bookings[i];
        //Check room matches filters
        if (matchFilters(getRoom(booking.room))) {
            bkAmnt[booking.day-1][booking.period-1]++;
        }
		//Loop through matches for clashes
		for (var k = 0; k < matches.length; k++) {
            if (matches[k].name == booking.room) {
                avAmnt[booking.day-1][booking.period-1]--;
            }
		}

	}
	
    //Main drawing loop//
	//Loop through days
	for (var i = 0; i < 5; i++) {
		var day = bkAmnt[i];
		//Loop through periods
		for (var k = 0; k < 9; k++) {
			amnt = day[k];
			obj = $("#cell" + i.toString() + k.toString());
            if (avAmnt[i][k] < 1) {
                obj.addClass("tableCell_red");
                obj.html("None available");
            }
            else if (amnt > 0) {
                obj.html('<div class="clickDiv" href="#av_room_model">' + amnt + " booked<br />" + avAmnt[i][k] + " available</a>");
                obj.addClass("tableCell_yellow");
            }
            else {
            	obj.html('<div class="clickDiv" href="#av_room_model">' + avAmnt[i][k] + " available</a>");
				obj.addClass("tableCell_blue");
            }
		}
	}
    
    //On cell click update the overlay
    $(".clickDiv").click(function(event) { updateOverlay($(event.target).parent().attr('id')); setTimeout('roomTable.fnAdjustColumnSizing();', 200); });
    
    //Fancybox stuff
    $(".clickDiv").fancybox({ padding: 20, hideOnContentClick: false });
	
}

//Returns true if the inputted room matches the selected filters
function matchFilters(room) {
    if (tabState == 0) {
        if ($("#park_combobox").val() != "Any" && $("#park_combobox").val() != room.park) return false;
        if ($("#room_type_combobox").val() != "Any" && $("#room_type_combobox").val() != room.type) return false;
        if ($("#capacity_combobox").val() != "Any" && $("#capacity_combobox").val() > room.capacity) return false;
        if ($('#proj_check').attr('checked') && !room.dataProj) return false;
        if ($('#ohp_check').attr('checked') && !room.ohp) return false;
        if ($('#white_check').attr('checked') && !room.whiteboard) return false;
        if ($('#chalk_check').attr('checked') && !room.chalkboard) return false;
        if ($('#wheel_check').attr('checked') && !room.wheelchair) return false;
    }
    else {
        if ($("#room_combobox").val() != "Any" && $("#room_combobox").val() != room.name) return false;
        if ($("#building_combobox").val() != "Any" && $("#building_combobox").val() != room.building) return false
    }
    return true;
}

//Returns the room object associated with the supplied name
function getRoom(name) {
	for (var i = 0; i < rooms.length; i++) {
		if (rooms[i].name == name) return rooms[i];
	}
	return null;
}

//Searches the array of rooms for entries that match the selected filters
function searchRooms() {
	var matches = [];
	for (var i = 0; i < rooms.length; i++) {
		var room = rooms[i];
        if (tabState == 0) {
            if ($("#park_combobox").val() != "Any" && $("#park_combobox").val() != room.park) continue;
            if ($("#room_type_combobox").val() != "Any" && $("#room_type_combobox").val() != room.type) continue;
            if ($("#capacity_combobox").val() != "Any" && $("#capacity_combobox").val() > room.capacity) continue;
            if ($('#proj_check').attr('checked') && !room.dataProj) continue;
            if ($('#ohp_check').attr('checked') && !room.ohp) continue;
            if ($('#white_check').attr('checked') && !room.whiteboard) continue;
            if ($('#chalk_check').attr('checked') && !room.chalkboard) continue;
            if ($('#wheel_check').attr('checked') && !room.wheelchair) continue;
        }
        else {
            if ($("#room_combobox").val() != "Any" && $("#room_combobox").val() != room.name) continue;
            if ($("#building_combobox").val() != "Any" && $("#building_combobox").val() != room.building) continue;
        }
		matches[matches.length] = room;
	}
	return matches;
}

//Updates the room combo based on the building combo
function updateRoomCombo() {

    $("#room_combobox").find('option').remove().end().append('<option value="Any">Any</option>');
    var building = $("#building_combobox").val();
    for (i = 0; i < rooms.length; i++) {
        var room = rooms[i];
        if (building == "Any" || building.length < 1 || room.building == building)
            $("#room_combobox").append('<option value="' + room.name + '">' + room.name + '</option>');
	}
    $("#room_combobox").val("Any");
    $("#room_input input").val("Any");
    
}

function updateOverlay(cellID) {

    //Remove old data
    $("#resultsBody").find('tr').remove().end();

    //Extract day and period
    var cellID = cellID.replace("cell", "");
    day = parseInt(cellID.substring(0,1)) + 1;
    period = parseInt(cellID.substring(1,2)) + 1;
    
    //Retrieve room matches and loop
    available = [];
    matches = searchRooms();
outer:
    for (var i = 0; i < matches.length; i++) {
        var room = matches[i];
        //Loop through bookings looking for clashes
        for (var k = 0; k < bookings.length; k++) {
            var booking = bookings[k];
            if (booking.room == room.name && booking.day == day && booking.period == period) continue outer;
        }
        available[available.length] = room;
    }
    
    //Add all available to list
	roomTable.fnClearTable();
    for (var i = 0; i < available.length; i++) {
        var rm = available[i];
		var fac = [];
		if (rm.dataProj) fac[fac.length] = "Projector";
		if (rm.ohp) fac[fac.length] = "OHP";
		if (rm.whiteboard) fac[fac.length] = "Whiteboard";
		if (rm.chalkboard) fac[fac.length] = "Chalkboard";
		if (rm.wheelchair) fac[fac.length] = "Wheelchair Access";
		if (fac.length < 1) fac[fac.length] = "None";
		var img = '<div class="picture" style="background-image: url(http://www.lboro.ac.uk/service/fm/services/ts/roompics/'+rm.name+'.jpg)"><br /></div>';
		roomTable.fnAddData(new Array(img, rm.name, rm.building, rm.park, rm.capacity, rm.type, fac.join(", ")));
    }
	
	//On click of results row
	$(".resultsRow").click(function(event) {
		$(this).find("td").toggleClass("resultsHighlighted");
        $(this).toggleClass("resultsHighlighted");
	});
    
}

var rooms = new Array();
var bookings = new Array();

var getRooms = function(){
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
				url: "index.php?page=roomavailability",
				data: {"get" : "rooms" },
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				//async: false,
									
				success: function(data){
					rooms = data;
					getBookings();
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});
};

var getBookings = function(){
	
			$.ajax({
				type: "GET",
				url: "index.php?page=roomavailability",
				data: {"get" : "bookings" },
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				//async: false,
									
				success: function(data){
					bookings = data;
					fillPage();
					$('#search_area').show();
					$("#loadingOverlay").overlay().close();
				},
									
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert("error: "+textStatus+" ("+errorThrown+ ")");
				}					
		});
};

$(document).ready(function(){

	roomTable = $('#resultsTable').dataTable( {
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"bAutoWidth": true,
		"bDeferRender": true,
		"sScrollY": "240px",
		"sDom": '<"H"Tfr>t<"F"i>S',
		"bPaginate": false
	} );
	
	$("#resultsTable tbody tr").live('click', function() {
		$(this).toggleClass("roomSelected");
	});
	
	$("#request_room_btn").live('click', function() {
        if ($("#resultsTable .roomSelected").length == 0) {
            alert('Please select a room to book!');
			return;
        }
		var codes = new Array();
		$("#resultsTable .roomSelected td:nth-child(2)").each(function() {
			codes[codes.length] = $(this).html();
		});
		window.location = "index.php?page=requests&arooms=" + codes.join(",");
	});
	
	$(".ui-autocomplete").live("click", function() { setTimeout("drawTable();", 200); });
	
	$('#search_area').hide();
	getRooms(); //-> get bookings -> fill page
	
});

var fillPage = function(){



	//Register change events with the drawTable function
	$("#proj_check").change(function() { drawTable(); });
	$("#ohp_check").change(function() { drawTable(); });
	$("#white_check").change(function() { drawTable(); });
	$("#chalk_check").change(function() { drawTable(); });
	$("#wheel_check").change(function() { drawTable(); });

    //Initial table draw
	drawTable();
	
    //Set up tabs
	$('#search_area').tabs();
    $("#search_area").bind("tabsselect", function(event, ui) {
        tabState = ui.index;
		drawTable();
    });
    
    //Fill room and building combos
    $("#building_combobox").append('<option value="Any">Any</option>');
    for (i = 0; i < rooms.length; i++) {
        //Building combobox
        var exists = false;
        $('#building_combobox option').each(function(){
            if (this.value == rooms[i].building) exists = true;
        });
        if (!exists) $("#building_combobox").append('<option value="' + rooms[i].building + '">' + rooms[i].building + '</option>');
	}
    updateRoomCombo();
	
    //Set up standard boxes
	$( "#building_combobox" ).combobox();
	$( "#room_combobox" ).combobox();
	$('#building_input > input.ui-autocomplete-input').css('width', '120px');
	$('#room_input > input.ui-autocomplete-input').css('width', '80px');
	$("#room_type_combobox").combobox();
	$('#room_type_input > input.ui-autocomplete-input').css('width', '90px');
	$("#park_combobox").combobox();
	$('#park_input > input.ui-autocomplete-input').css('width', '90px');
	$( "#capacity_combobox" ).combobox();
	$('#capacity_input > input.ui-autocomplete-input').css('width', '90px');
	
    
	//Request button - redirects to request page and passes args through
	$('#request_room_btn').button();
	
	
};

(function( $ ) {
		$.widget( "ui.combobox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
                            
                            //Custom update shit for combo boxes
                            if (tabState == 1 && $(select).attr('id') == "building_combobox") {
                                updateRoomCombo();
                            }
                            drawTable();
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All Items" )
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus();
					});
			},

			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
	})( jQuery );