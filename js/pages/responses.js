$(document).ready(function(){

	// display Edit and Delete text and image toolbar
	$('.scrollContent').mouseenter(function(){
		$(this).find('.toolbar').show();
	});
	
	//Hide Edit and Delete text and image toolbar
	$('.scrollContent').mouseleave(function(){
		$(this).find('.toolbar').hide();
	});
	
	//on hover underline for edit text
	$('.edit').mouseenter(function() {
			$(this).addClass("edit_ul");
		
		});
	
	//remove underline for edit text
	$('.edit').mouseleave(function() {
		$(this).removeClass("edit_ul");
		
	});
	
	//on hover underline for delete text	
	$('.delete').mouseenter(function() {
			$(this).addClass("delete_ul");
		
	});
	
	//remove underline for delete text	
	$('.delete').mouseleave(function() {
			$(this).removeClass("delete_ul");
		
	});
	
	//Deltes row if 'Delete' text is clicked
	$('.delete').click(function(){
		if (confirm("Are you sure you want to DELETE the Request/Repsonse?")) {
			var id = $(this).parents('.scrollContent').attr('id').substr(3);
			var newbox = "#newboxes"+id;
			$(newbox).remove();
			$(this).parents('.scrollContent').remove();
		}	
	});
	
	//Deltes row if Delete IMAGE is clicked
	$('.del_btn').click(function(){
		if (confirm("Are you sure you want to DELETE the Request/Repsonse?")) {
			var id = $(this).parents('.scrollContent').attr('id').substr(3);
			var newbox = "#newboxes"+id;
			$(newbox).remove();
			$(this).parents('.scrollContent').remove();
		}	
	});

	//Shows Edit confirmation if 'Edit' TEXT is clicked
	$('.edit').click(function() {
		if (confirm("Are you sure you want to EDIT the Request/Repsonse")) {
			
		}
	});
	
	//Shows Edit confirmation if 'Edit' IMAGE is clicked
	$('.edit_btn').click(function() {
		if (confirm("Are you sure you want to EDIT the Request/Repsonse")) {
		
		}	
	});
	
	//creates array of checkboxes for 'pending', 'allocated', 'declined' and 'failed' checkboxes.
	var status_array = {pending: true, allocated: false, declined: false, failed: false};
	
	//If a status checkbox is changed, this function is performed. 
	$('.type_check').change(function() {
		//gts each checkbox element and finds whether or not it is checked.
		for(var i in status_array){
			var elm = "#"+i;
			status_array[i] = $(elm).find('.type_check').is(':checked');
		}
		//Finds each row 'id' and if checked, will show, else will hide.
		$('#scrollablearea').find('.scrollContent').each(function(){
			if($(this).hasClass('Pending')){
				if(status_array['pending']) 
					$(this).show();
				else
					$(this).hide();
			}
			if($(this).hasClass('Allocated')){
				if(status_array['allocated']) 
					$(this).show();
				else
					$(this).hide();
			}
			if($(this).hasClass('Declined')){
				if(status_array['declined']) 
					$(this).show();
				else
					$(this).hide();
			}
			if($(this).hasClass('Failed')){
				if(status_array['failed']) 
					$(this).show();
				else
					$(this).hide();
			}
		});
	});
	
	//ASK JONO!!!!!!!!!!!!!!!!!!!!!!
	
	/*$('#scrollablearea').click(function() {
		$(this).find('.scrollContent').each(function() {
			var row_id_number = $(this).attr('id').substr(3);
			alert(row_id_number + "ROW");
				$('#scrollablearea').find('.hidden').each(function() {
					var hidden_row_id_number = $(this).attr('id').substr(8);
						if(row_id_number == hidden_row_id_number) {
							var elm = "#newboxes"+hidden_row_id_number;
							$(elm).show();
								alert("match found");
								return false;
							}
						else {
							alert("Match not found");
						}
							
						
					//alert(hidden_row_id_number + "HIDDEN");
				});
		
		});
	});*/
	
	
	
	
	
	
		
		//$('#scrollablearea').find('.scrollContent').each(function() {
			//if ($(this).hasClass('Pending')) {
				//var y = $(this).attr('id').substr(3);
				//alert(y);
				
				/*}
			else if ($(this).hasClass('Allocated')) {
				var z = $(this).attr('id').substr(3);
				alert(z);
				}
			else if ($(this).hasClass('Declined')) {
				var y = $(this).attr('id').substr(3);
				alert(y);
				}
			else if ($(this).hasClass('Failed')) {
				var y = $(this).attr('id').substr(3);
				alert(y);
				}*/
			
			
		//});
		
		/*if ($(this).hasClass('Pending')) {
			var x = $(this).attr('id');
			$('#scrollablearea').find('.hidden').each(function() {
				var 
			});
			alert(x);
		}
		else
		{
		}
		
		});*/
		
		/*$(this).find('.scrollContent').each(function() {
			if($(this).hasClass('Pending')){
				var id = $(this).parents('.scrollContent').attr('id').substr(3);
				alert(id);
			}*/
				
			//var id = $(this).parents('.scrollContent').attr('id').substr(3);
			//alert(id);
			/*$('#scrollablearea').find('').each(function() {
					
			});*/
				
		/*});*/
			
			//var x = $(this).child('.scrollContent').attr('id');
	
	
	/*$('#scrollablearea').find('.scrollContent').each(function() {
		var x = $(this).attr('id');
	});*/
	
});

//Function allows table rows to be expanded to show additional info and collapsed again, only allows 1 expanded row at a time
function expandrows(hiddenrowid, currentrowid)
{
	var hiddenrow = hiddenrowid;
	var currentrow = currentrowid;
	
	var row = document.getElementById(hiddenrow).style.display;
	
	if (row == "none")
	{
		document.getElementById(hiddenrow).style.display = "block";
	}
	else
	{
		document.getElementById(hiddenrow).style.display = "none";
	}
		
}

//This function will display the hidden div below it and all the information within it. Will allow for only a maximum of 1 or 0 to be visible at a time.
function showonlyone(thechosenone) {
      var newboxes = document.getElementsByTagName("div");
      for(var x=0; x<newboxes.length; x++) {
            name = newboxes[x].getAttribute("name");
            if (name == 'newboxes') {
                  if (newboxes[x].id == thechosenone) {
                        if (newboxes[x].style.display == 'block') {
                              newboxes[x].style.display = 'none';
                        }
                        else {
                              newboxes[x].style.display = 'block';
                        }
                  }else {
                        newboxes[x].style.display = 'none';
                  }
            }
      }
}	








