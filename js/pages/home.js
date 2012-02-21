$(document).ready(function() {
	$(".round").tooltip({ offset: [90, 70] });
	
	//oli size needs to be corrected (height) but ill leave all datatables stuff down to you
	$('#req_att_table').dataTable( {
		"bJQueryUI": true,
		"bAutoWidth": true,
            "sDom": 't<"F"i>S',
		} );
});