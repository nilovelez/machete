var machete_cookie_bar = (function(){
	var set_cookie = function(name,value,days) {
		var expires = '';
		if (days) {
			var date = new Date();
			date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
			var expires = "; expires=" + date.toGMTString();
		}
		document.cookie = name + "=" + value + expires + "; path=/";
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
	return {
		init: function(cookie_bar_html){
			if ( ! get_cookie( 'machete_accepted_cookies' ) ) {
				var container       = document.createElement( 'div' );
				container.id        = 'machete_cookie_container';
				container.className = 'machete_cookie_container';
				container.innerHTML = cookie_bar_html;
				Object.assign(container.style,{
					position: 'fixed',
					zIndex: 99999,
					bottom: 0,
					width: '100%'
				});
				var body = document.getElementsByTagName( 'body' )[0];
				body.appendChild( container );

				document.getElementById( 'machete_accept_cookie_btn' ).addEventListener( 'click', machete_cookie_bar.accept, false );
			}
		},
		accept: function(){
			set_cookie( 'machete_accepted_cookies', 'yes', 365 );
			document.getElementById( 'machete_cookie_container' ).style.display = 'none';
		}
	}
})();
machete_cookie_bar.init( machete_cookies_bar_html );
