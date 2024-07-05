var machete_tracking_script_url = machete_tracking_script_url || '';
var machete_tracking = (function( script_url ){

	if ( ! script_url ) {
		return false;
	}

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

	if ( 'yes' === get_status() ){
		gtag('consent', 'update', {
			'analytics_storage': 'granted'
		});
	}

	a = document.createElement( 'script' );
	m = document.getElementsByTagName( 'script' )[0];
	a.src = script_url;
	m.parentNode.insertBefore(a,m);


})( machete_tracking_script_url );
