/**
 *	File: js/pages/home.js
 *  Author: Oliver Woodings
 *  For: Home page
 */


$(document).ready(function() {
	$(".round").tooltip({ offset: [50, -80], relative: true });
	
	$("#timeline .round:first").addClass("cleft");
	$("#timeline .round:last").addClass("cright");	
	
	//oli size needs to be corrected (height) but ill leave all datatables stuff down to you
	$('#req_att_table').dataTable( {
		"bJQueryUI": true,
		"bSort": false,
		"sDom": 't<"F">S',
		"bPaginate": false, 
	} );
	
	$("#req_att_table tbody tr").click(function() { window.location = "index.php?page=responses&id=" + $(this).attr('id') + "&status=" + ($(this).hasClass("failed")?"failed":"reallocated"); });
	
});