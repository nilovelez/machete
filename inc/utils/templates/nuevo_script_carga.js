var machete_tracking = (function(){

	var get_cookie = function(name) {
		var nameEQ = name + "=";
		var ca     = document.cookie.split( ';' );
		for ( var i = 0, n = ca.length, c; i < n; i++ ) {
			c = ca[i];
			while ( c.charAt( 0 ) == ' ') {
				c = c.substring( 1, c.length );
			}
			if ( c.indexOf( nameEQ ) == 0 ) {
				return c.substring( nameEQ.length, c.length );
			}
		}
		return null;
	}

	var get_status = function(){
		return get_cookie( 'machete_accepted_cookies' ) || 'no';
	}

	var load_tracking = function( script_url ){

		a = document.createElement( 'script' );
		m = document.getElementsByTagName( 'script' )[0];
		a.async = 1;
		a.src = script_url;
		m.parentNode.insertBefore(a,m);

	}

	return {
		init: function( script_url ){

			if ( ! script_url ) {
				return false;
			}

			if ( 'yes' === get_status() ){
				load_tracking( script_url );
			}else{
				addEventListener('machete_accepted_cookies', function(e){
					load_tracking( machete_tracking.script_url );		
				}, false);
			}
		}
	}
})();

if ( window.script_url ) {
	machete_tracking.init( window.script_url );	
}
