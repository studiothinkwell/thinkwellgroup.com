$(document).ready(function(){

	// Correct formatting for help status message
	$("#statusbar .help p:last").css({'margin-bottom':'0'});

	// Posititions icons correctly for status bar messages
	$("#statusbar > div").each( function() {
		if ($(this).height()>=45) {
			$(this).addClass("pinned");
		};
	});

	// Restyles "Input Format" fieldsets
	$("legend").each(function() {
		if ($(this).children().text() == "Input format") {
			$(this).parent().addClass("input-selector");
		}
	});

	// Table cell highlighting for permissions
	$('#permissions td.checkbox').hover(
	function () {
		var $tr = $(this).parent();
		var trindex = $('#permissions tr').index($tr);
		var tdindex = $('td','#permissions tr:eq('+trindex+')').index(this);
		$('td:eq('+tdindex+')','#permissions tr').addClass('hovered');		
   	}, 
    function () {
    	var $tr = $(this).parent();
		var trindex = $('#permissions tr').index($tr);
		var tdindex = $('td','#permissions tr:eq('+trindex+')').index(this);
		$('td:eq('+tdindex+')','#permissions tr').removeClass('hovered');
    });
    
});
