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

	var my_createElement = function( tag, htmlid = null, htmlclass = null, html = null ){
		element = document.createElement( tag );
		if ( !! htmlid ) {
			element.id = htmlid;
		}
		if ( !! htmlclass ) {
			element.className = htmlclass;
		}
		if ( !! html ) {
			element.innerHTML = html;
		}
		return element;
	}

	var get_status = function(){
		return get_cookie( 'machete_accepted_cookies' ) || 'no';
	}

	var cookiebar = (function(){
		var cookiebar = my_createElement(
			'div',
			'machete_cookie_bar',
			'machete_cookie_bar',
			 machete_cookies_bar_html
		);
		cookiebar.querySelector("#machete_accept_cookie_btn").addEventListener(
			'click', function(){
					machete_cookie_bar.accept('yes');
				} , false );
		cookiebar.querySelector("#machete_accept_cookie_btn_partial").addEventListener(
			'click', function(){
					machete_cookie_bar.accept('partial');
				} , false );
		return {
			add : function(){
				document.getElementById('machete_cookie_container').appendChild( cookiebar );
			},
			remove : function(){
				document.getElementById('machete_cookie_bar').remove();
			}
		}
	})();

	var configbar = (function(){
		var configbar = my_createElement(
			'div',
			'machete_cookie_configbar',
			'machete_cookie_configbar',
			machete_cookies_configbar_html
		);
		configbar.querySelector("#machete_cookie_config_btn").addEventListener(
			'click', function(){
				machete_cookie_bar.config();
			}, false );

		return {
			add : function(){
				document.getElementById('machete_cookie_container').appendChild( configbar );
			},
			remove : function(){
				document.getElementById('machete_cookie_configbar').remove();
			}
		}
	})();

	return {
		init: function(){

			var container = my_createElement(
				'div',
				'machete_cookie_container',
				'machete_cookie_container'
			);
			Object.assign(container.style,{
				position: 'fixed',
				zIndex: 99999,
				bottom: 0,
				width: '100%'
			});
			/*
			var cookies_css = my_createElement(
				'style',
				null,
				null,
				machete_cookies_css
			);
			container.appendChild( cookies_css );
			*/
			var body = document.getElementsByTagName( 'body' )[0];
			body.appendChild( container );
			
			if ( 'no' === get_status() ) {
				cookiebar.add();	
			} else {
				configbar.add()
			}
		},
		accept: function( cookies = 'yes' ){
			// saves the cookie settings an closes the cookie bar
			set_cookie( 'machete_accepted_cookies', cookies, 365 );
			cookiebar.remove();
			configbar.add();
		},
		config: function(){
			// reopens the cookie bar
			configbar.remove();
			cookiebar.add();
		},
		status: function(){
			return get_status();
		}
	}
})();
machete_cookie_bar.init();
