/* 
* Обслуживание кнопок для рейтинга пользователей
*/

$(document).ready(function(){

	$('button.rateButton[rate_type="post"]').click(function(e){		
		var direction = ($(this).attr('direction') == 'positive')?true:false;
		 var post_id = parseInt($(this).attr('id'));
		  var post_rating_el = $(this).parent().parent().find('.postRatingValue');
			$.post("/rate/post/", {id: post_id, direction: direction}, function(json){	
					var data = JSON.parse(json);					 
					 if(!data.error){
					 	post_rating_el.html("↑↓"+feelNumber(  parseFloat(data.full).toFixed(3)  ));
					 	 Effects.mouseAlert(e, feelNumber(  parseFloat(data.update).toFixed(3)  ));
					 }else{
					 	Effects.mouseAlert(e, "<div class='error'>"+data.error+"</div>");
					 }				
			});
	});

	$('button.rateButton[rate_type="comment"]').click(function(e){
		var direction = ($(this).attr('direction') == 'positive')?true:false;
		 var comment_id = parseInt($(this).attr('id'));
		  var comment_rating_el = $(this).parent().parent().find('.commentRatingValue');
			$.post("/rate/comment/", {id: comment_id, direction: direction}, function(json){	
					var data = JSON.parse(json);
					  if(!data.error){
					 	 comment_rating_el.html("↑↓"+feelNumber(  parseFloat(data.full).toFixed(3)  ));
					 	  Effects.mouseAlert(e, feelNumber(  parseFloat(data.update).toFixed(3)  ));
					  }else{
					 	Effects.mouseAlert(e, "<div class='error'>"+data.error+"</div>");
					  }
				
			});
	});

});


function feelNumber(num){
	if(num < 0)
        return '<span class="num_negative">'+num+'</span>';
    else if(num > 0 )
        return '<span class="num_positive">+'+num+'</span>';
    else
        return '<span class="num_neutral">0</span>';
}