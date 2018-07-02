(function($) {
	
	"use strict";
	
	$('.machete-social-share a').on('click', function( e ){
		e.preventDefault();
		window.open(
			$( this ).attr('href'),
			$( this ).attr('data-network'),
			"toolbar=yes, top=50, left=50, width=400, height=400");
	});

})( jQuery );