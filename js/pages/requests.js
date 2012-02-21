$(document).ready(function(){
	
	// Module expand
	$("#module_input").click(function() {
		$('#mod_expand').slideDown('slow', function() {
    		// Animation complete.
  		});
	});
	
	/* Testing mouse position */
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
    		// Animation complete.
  		});
	});
	$("#room_done").click(function() {
		$('#roompref_expand').slideUp('slow', function() {
    		// Animation complete.
  		});
	});
	
	//Tokenizer
	/*$('#room_pref').keydown(function(e){ //keyup for comma
		if(e.which == 13 || e.which == 188){ //188 - comma
			var value = $(this).contents().filter(function() {
			  return this.nodeType == 3;
			}).last();
			var editvalue = value.text().substring(0,value.text().length); //-1
			value.replaceWith('<div class="tag" contenteditable="false"><r>'+editvalue+'</r><a class="deletebutton"></a></div>');
			
			//$(this).focus();
		}
	});*/
	
	$('.mod_elm').bind('click',function(){
		//$('#module_input').empty();
		$('#module_input').hide();
		$('#mod_expand').hide();
		var module = $(this).find('p').text();
		$('#modline').append('<div class="tag" contenteditable="false"><r>'+module+'</r><a class="deletebutton moddlt"></a></div>');
		//var code = $(this).attr('id').toUpperCase();
		//$('#module_input').append('<div class="tag" contenteditable="false"><r>'+code+'</r><a class="deletebutton"></a></div>');
		$('#module_sect').removeClass('mand');
		$('#module_sect').addClass('completed');
	});
						
	$('.moddlt').live('click',function(){ 
		$(this).parent().remove(); 
		$('#module_input').show();
		$('#mod_expand').show();
	});
	
	/* Facilities stuff */
	var facilities = {proj: false, ohp: false, white: false, chalk: false, wheel: false};
	var fac_names = new Array("Data Projector","OHP","WhiteBoard","Chalkboard","Wheelchair Access");
	
	$('#fac_edit').click(function(){
		//hide fac_detials;
		$('#fac_details').hide();
		//check correct boxes
		for(var i in facilities) {
			var value = facilities[i];
			var elm = "#"+i+"_check";
			$(elm).prop("checked", value);
		}
		//show fac_expand
		$('#fac_expand').show();
		//change edit button to done button
		$('#fac_edit').hide();
		$('#fac_done').show();
	});
	
	$('#fac_done').click(function(){
		//hide fac_expand
		$('#fac_expand').hide();
		//show correct tags
		$('#fac_details').empty();
		var count = 0;
		for(var i in facilities){
			var elm = "#"+i+"_check";
			facilities[i] = $(elm).is(':checked');
			var value = facilities[i];
			if(value){
				$('#fac_details').append('<div id="fac_'+i+'" class="tag" contenteditable="false"><r>'+fac_names[count]+'</r><a class="deletebutton facdlt"></a></div>');
			}
			count++;
		}
		//show fac_details
		$('#fac_details').show();
		//change done button to edit button
		$('#fac_done').hide();
		$('#fac_edit').show();
	});
	
	$('.facdlt').live('click',function(){  
		var elm = $(this).parent().attr('id');
		var fac = elm.substr(elm.indexOf("_")+1,elm.length);
		facilities[fac]= false;
		$(this).parent().remove();
	});
	
	/*Day-Time selection */
	var dt_select = false;
	
	$('.td_content').hover(function(){
		//mousein
		//check none selected
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
			//show selection
			if(this_id<=limit){
			for(var i = 0;i<length;i++){
				var cell = "#cell"+this_id;
					$(cell).addClass("hovertd");
					this_id++;
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
		$(this).parents('#contentt').find('.td_content').each(function(){
			if($(this).hasClass('selectedtd')){
				$(this).removeClass('selectedtd');
				dt_select = false;
			}
		});
		$(".hovertd").addClass('selectedtd');
	});
	
	/*$('.selectedtd').live("click",function(){
		$(this).parents('#contentt').find('.td_content').each(function(){
			if($(this).hasClass('selectedtd')){
				$(this).removeClass('selectedtd');
				dt_select = false;
			}
		});
	});*/
	
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
	$( "#priority_check" ).button();
	$('.std_but').button();
	
	/* Combobox's */
	$('#park_select').combobox();
	$('#park_input > input.ui-autocomplete-input').css('width', '90px');
	$("#roomty_select").combobox();
	$('#room_type_input > input.ui-autocomplete-input').css('width', '90px');
	$("#length_select").combobox();
	$('#length_input > input.ui-autocomplete-input').css('width', '90px');
	$("#search_by_select").combobox();
	$('#search_by_input > input.ui-autocomplete-input').css('width', '100px');
	
});
