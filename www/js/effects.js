
var mouseAlertTimeout;

function Effects(){
	this.Effects = function(){
		alert("construct");
	}
}


Effects.mouseAlert = function(click, str, time){

	$(document).bind('mousemove click mouseover', function(e){
		if(!$('#mouseAlertBlock').length)
			$('html').append('<div id="mouseAlertBlock"></div>');
		$('#mouseAlertBlock').css({position: "absolute", left: (e.pageX + 15)+"px", top: (e.pageY+15)+"px", "pointer-events": "none"});			
	});

	if(time == undefined) time = 2300;

	if(!$('#mouseAlertBlock').length)
		$('html').append('<div id="mouseAlertBlock"></div>');

	$('#mouseAlertBlock').css({position: "absolute", left: (click.pageX + 15)+"px", top: (click.pageY+15)+"px", "pointer-events": "none"});			

	clearTimeout(mouseAlertTimeout);
	$('#mouseAlertBlock').clearQueue().css({opacity: 1});

	$('#mouseAlertBlock').html(str);
	 $('#mouseAlertBlock').animate({opacity: 0}, time);
	  $('#mouseAlertBlock *').css({"position": "relative"});
	  $('#mouseAlertBlock *').animate({"top": -80, "font-size": "200%"}, time);

	 
	 mouseAlertTimeout  =	setTimeout(function(){
			$(document).unbind('mouseover');
			 	$('#mouseAlertBlock').remove();			

		}, time);
}