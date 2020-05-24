"use strict";
(function() {
	if ( ! Element.prototype.querySelectorAll ) {
		return;
	}
	var elements    = document.querySelectorAll( '.mct-share-buttons a' );
	var numElements = elements.length;
	for ( var i = 0; i < numElements; i++ ) {
		elements[i].addEventListener(
			'click',
			function( e ){
				e.preventDefault();
					window.open(
						this.getAttribute( "href" ),
						this.getAttribute( "data-network" ),
						"toolbar=yes, top=50, left=50, width=500, height=500"
					);
			},
			false
		);
	}
}());
