( function () {
	'use strict';

	// Mobile menu.
	var toggle = document.querySelector( '.menu-toggle' );
	var menu = document.getElementById( 'site-menu' );

	function setMenu( open ) {
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		menu.classList.toggle( 'is-open', open );
	}

	toggle.addEventListener( 'click', function () {
		setMenu( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
	} );

	menu.addEventListener( 'click', function ( event ) {
		if ( event.target.closest( 'a' ) ) {
			setMenu( false );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
			setMenu( false );
			toggle.focus();
		}
	} );

	// Install form: sends the visitor to the plugin installer of their own site.
	var form = document.getElementById( 'install' );
	var input = document.getElementById( 'install-url' );
	var error = document.getElementById( 'install-error' );

	function installerUrl( value ) {
		var url;
		value = value.trim();
		if ( ! value ) {
			return null;
		}
		if ( ! /^https?:\/\//i.test( value ) ) {
			value = 'https://' + value;
		}
		try {
			url = new URL( value );
		} catch ( e ) {
			return null;
		}
		if ( ! url.hostname || url.hostname.indexOf( '.' ) === -1 && url.hostname !== 'localhost' ) {
			return null;
		}
		// Keep the path of a site installed in a subfolder, but drop anything from wp-admin or wp-login.php on.
		var path = url.pathname.replace( /\/(wp-admin|wp-login\.php).*$/, '' ).replace( /\/+$/, '' );
		return url.origin + path + '/wp-admin/plugin-install.php?s=machete&tab=search&type=term';
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		var target = installerUrl( input.value );
		if ( ! target ) {
			error.textContent = 'Please enter the address of your WordPress site, e.g. https://yoursite.com';
			error.hidden = false;
			input.focus();
			return;
		}
		error.hidden = true;
		window.location.href = target;
	} );
}() );
