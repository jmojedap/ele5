$(document).ready(function(){
	
	$('*', '.main_container').first().css('margin-top', '0px');
	
	$('.menu_switch').click(function(){
		$('.main_nav').slideToggle(250);
	});
	
	$('.has_submenu > a').click(function(e){
		$(this).parent().find('.sub_nav').slideToggle(250);
		return false;
	});
	
	$('.has_submenu').mouseleave(function(){
		if($('.main_nav_col').css('position') === 'absolute'){
			$(this).find('.sub_nav').slideUp(250);
		}
	});
	
	$('.info_container_title').append('<span class="collapse_box"></span>');
	$('.collapse_box').append('<i class="fa fa-angle-down"></i>');
	
	$('.collapse_box').click(function(){
		console.log('click');
		$(this).parent().next('.info_container_body').slideToggle(250);
		$(this).find('.fa').toggleClass('fa-rotate-270');
	});
	
	$('.search_box').focus(function(){
		if($.trim($(this).val()) === 'buscar'){
			$(this).val('');
		}
		$(this).css('color', '#000');
		if($('.main_nav_col').css('position') === 'absolute'){
			$('.search_container').css('width', '200px');
			$(this).css('width', '172px');
		}
	});
	
	$('.search_box').blur(function(){
		if($.trim($(this).val()) === ''){
			$(this).val('buscar');
		}
		$(this).css('color', '#CCC');
		if($('.main_nav_col').css('position') === 'absolute'){
			$('.search_container').css('width', '');
			$(this).css('width', '');
		}
	});
	
	$('.breadcrumbs a').not(':last').after('<span class="breadcrumbs_divisor">/</span>');
	
	updateScreen();
	$(window).resize(function(){
		updateScreen();
	});
	
});

function updateScreen(){
	
	$('.main_nav_col').css('height', 'auto');
	lh = $('.main_nav_col').outerHeight();
	rh = $('.main_container').outerHeight() + $('.main_footer').outerHeight();
	
	if($('.main_nav_col').css('position') === 'absolute'){
		
		$('.main_nav').slideDown(0);
		$('.sub_nav').slideUp(0);
		
		if(lh < rh){
			$('.main_nav_col').css('height', rh + 'px');
		}
		else{
			$('.main_nav_col').css('height', 'auto');
		}
	}
	else{
		$('.main_nav_col').css('height', 'auto');
		$('.main_nav').slideUp(0);
	}

}