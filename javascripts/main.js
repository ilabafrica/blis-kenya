function slideswitch(){	
	var $activeslide = $('#slideshow img.active');
		if ($activeslide == 0) $activeslide = $('#slideshow img:last');	
	var $activeback = $('#background img.active');
		if ($activeback == 0) $activeback = $('#background img:last');	
	var $nextslide = $activeslide.next().length ? $activeslide.next() : $('#slideshow img:first') ;
	var $nextback = $activeback.next().length ? $activeback.next() : $('#background img:first')

	$activeslide.addClass('last-active');
	$activeback.addClass('last-active');

	$nextslide.css({opacity: 0.0})
		.addClass('active')
		.animate({opacity: 1.0}, 1000, function(){
			$activeslide.removeClass('active last-active');
	});

	$nextback.css({opacity: 0.0})
		.addClass('active')
		.animate({opacity: 1.0}, 1000, function(){
			$activeback.removeClass('active last-active');
	});
}

$(function() {
	setInterval( "slideswitch()", 3000);
});