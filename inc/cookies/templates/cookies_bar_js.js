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
	var get_status = function(){
		return get_cookie( 'machete_accepted_cookies' ) || 'no';
	}

	var container = (function(){
		var container       = document.createElement( 'div' );
		container.id        = 'machete_cookie_container';
		container.className = 'machete_cookie_container';
		container.innerHTML = machete_cookies_bar_html;
		Object.assign(container.style,{
			position: 'fixed',
			zIndex: 99999,
			bottom: 0,
			width: '100%'
		});
		var body = document.getElementsByTagName( 'body' )[0];
		return {
			add : function(){
				body.appendChild( container );
				document.getElementById( 'machete_accept_cookie_btn' ).addEventListener(
					'click', machete_cookie_bar.accept, false );
			},
			remove : function(){
				var container = document.getElementById('machete_cookie_container');
				container.remove();
			}
		}
	})();

	var configbar = (function(){
		var configbar       = document.createElement( 'div' );
		configbar.id        = 'machete_cookie_configbar';
		configbar.className = 'machete_cookie_configbar';
		configbar.innerHTML = machete_cookies_configbar_html;
		Object.assign(configbar.style,{
			position: 'fixed',
			zIndex: 99999,
			bottom: '10%'
		});
		var body = document.getElementsByTagName( 'body' )[0];
		return {
			add : function(){
				body.appendChild( configbar );
				document.getElementById( 'machete_cookie_config_btn' ).addEventListener(
					'click', machete_cookie_bar.config, false );
			},
			remove : function(){
				var configbar = document.getElementById('machete_cookie_configbar');
				configbar.remove();
			}
		}
	})();

	var body = document.getElementsByTagName( 'body' )[0];

	return {
		init: function(){
			
			if ( 'no' === get_status() ) {
				container.add();	
			} else {
				configbar.add()
			}
		},
		addCookieConfig:  function(){
			body.appendChild( config );
			document.getElementById( 'machete_cookie_config_btn' ).addEventListener( 'click', machete_cookie_bar.config, false );
		},
		accept: function( cookies = 'yes' ){
			// saves the cookie settings an closes the cookie bar
			set_cookie( 'machete_accepted_cookies', cookies, 365 );
			container.remove();
			configbar.add();
		},
		config: function(){
			// reopens the cookie bar
			configbar.remove();
			container.add();
		},
		status: function(){
			return get_status();
		}


	}
})();
machete_cookie_bar.init();
