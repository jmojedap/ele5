$(function() {
	tallest = 0;
	$('.columna').each(function(){
		thisHeight = $(this).height();
		if( thisHeight > tallest)
			tallest = thisHeight;
	});

  $('.columna').each(function(){
	 $(this).height(tallest);
 });
});