var moduleTable;
var lecArr;
var selectedRow;
var editCode;

$(document).ready(function(){
	moduleTable = $('#modules').dataTable( {
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"bAutoWidth": true,
			"bDeferRender": false,
			"sScrollY": "300px",
			"sDom": '<"H"Tfr>t<"F"i>S',
			"bPaginate": false
		} );
	
	fillModTable();
	
	
	// module action buttons
	$('#add_module').button();
	$('#edit_module').button();
	$('#remove_module').button();
	$('#repeat_request').button();
	
	// **** ADD LECTURER OVERLAY **** //
	$('#add_add_l').button();
	$('#add_remove_l').button();
	$('#sub_addmodule').button();
	$('#add_lec_list').combobox();
	$("#add_module").overlay();
	
	//lecturer add and remove
	$('#add_add_l').click(function(){
		var value = $('#add_lec_list').attr('value');
		//Check for duplicates
		$("#add_lecturers > option").each(function() {
			if (this.value == value) throw new Error("Duplicate lecturer");
		});
		$('#add_lecturers').append('<option value="' + value + '">'+$("#add_lec_list option[value='" + value + "']").text()+'</option>');
		$('#add_lec_list').attr('value','');
	});
	$('#add_remove_l').click(function(){
		$("#add_lecturers option:selected").remove();
	});
	//message box
	$('#sub_addmodule').click(function(){
		//Get lecturer array
		var lecs = $('#add_lecturers option').map(function () { return this.value; }).get();
		$.get(
			"index.php?page=modules&get=add&module_code=" + $("#add_mod_code").val() + "&module_name=" + $("#add_mod_name").val() + "&lecturers=" + JSON.stringify(lecs),
			function(data) {
				//If error
				if (data != null && data.error.length > 0) {
					alert(data.error);
					return;
				}
				makePopup("Module Added");
				fillModTable();
			},
			"json"
		);
	});
	// *************************** //
	
	
	// **** EDIT LECTURER OVERLAY **** //
	$('#edit_add_l').button();
	$('#edit_remove_l').button();
	$('#sub_editmodule').button();
	$('#edit_lec_list').combobox();
	$("#edit_module").overlay();
	
	//lecturer add and remove
	$('#edit_add_l').click(function(){
		var value = $('#edit_lec_list').attr('value');
		//Check for duplicates
		$("#edit_lecturers > option").each(function() {
			if (this.value == value) throw new Error("Duplicate lecturer");
		});
		$('#edit_lecturers').append('<option value="' + value + '">'+$("#edit_lec_list option[value='" + value + "']").text()+'</option>');
		$('#edit_lec_list').attr('value','');
	});
	$('#edit_remove_l').click(function(){
		$("#edit_lecturers option:selected").remove();
	});
	//Submit edits to database
	$('#sub_editmodule').click(function(){
		//Get lecturer array
		var lecs = $('#edit_lecturers option').map(function () { return this.value; }).get();
		$.get(
			"index.php?page=modules&get=save&old_code=" + editCode + "&new_code=" + $("#edit_mod_code").val() + "&module_name=" + $("#edit_mod_name").val() + "&lecturers=" + JSON.stringify(lecs),
			function(data) {
				//If error
				if (data != null && data.error.length > 0) {
					alert(data.error);
					return;
				}
				makePopup("Module Edited");
				fillModTable();
			},
			"json"
		);
	});
	// *************************** //
	
	
	//Remove module
	$("#remove_module").click(function() {
		$(this).hide();
		$('#confirm').show();
	});
	$('#yes').click(function(){
		var row = moduleTable.fnGetData(fnGetSelected());
		$.get(
			"index.php?page=modules&get=delete&module_code=" + editCode,
			function(data) {
				//If error
				if (data != null && data.error.length > 0) {
					alert(data.error);
					return;
				}
				makePopup("Module Deleted");
				$('#confirm').hide();
				$('#remove_module').show();
				fillModTable();
			},
			"json"
		);
	});
	$('#no').click(function(){
		$('#confirm').hide();
		$('#remove_module').show();
	});
	
	
});

function loadModuleForEdit() {
	var row = moduleTable.fnGetData(fnGetSelected());
	editCode = row[0];
	$.get("index.php?page=modules&get=" + row[0],
		function(data) {
			if (data.code.length > 0) {
				$('#edit_lecturers').find('option').remove();
				$("#edit_mod_code").val(data.code);
				$("#edit_mod_name").val(data.name);
				for (var j = 0; j < data.lecturers.length; j++) {
					$('#edit_lecturers').append('<option value="' + data.lecturers[j].id + '">'+data.lecturers[j].name+'</option>');
				}
			}
		},
		"json");
}

function refreshTable() {
	$("#modules tbody:first").find("tr:first").addClass('moduleSelected');
	loadModuleForEdit();
	$("#modules tbody tr").click(function() {
		$("#remove_module").show();
        $('#modules tbody tr').removeClass('moduleSelected');
        $(this).addClass('moduleSelected');
		loadModuleForEdit();
	});
}

function fillModTable() {
	$.get("index.php?page=modules&get=all",
		function(data) {
			if (data.length > 0) {
				moduleTable.fnClearTable();
				for (var i = 0; i < data.length; i++) {
					var module = data[i];
					var lecArr = [];
					for (var j = 0; j < module.lecturers.length; j++) {
						lecArr[j] = module.lecturers[j].name;
					}
					moduleTable.fnAddData([ module.code, module.name, lecArr.join(", ") ]);
				}
			}
			refreshTable();
		},
		"json");
}

function fnGetSelected() {
    return $("#modules tbody:first").find("tr.moduleSelected:first")[0];
}
