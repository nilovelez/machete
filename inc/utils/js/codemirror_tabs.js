(function($, wp){

	var machete_utils = {
		editor: {},
		tabs: [],
		current_tab: '#machete-tabs-tracking'
	};

	$( '#machete-tabs a' ).each(
		function() {
			machete_utils.tabs[ $( this ).attr( 'href' ) ] = {};
		}
	);

	if ( window.location.hash && ( machete_utils.tabs[ window.location.hash ] ) ) {
		machete_utils.current_tab = window.location.hash;
	}

	updateTabs = function() {
		// console.log(machete_utils.current_tab);
		window.location.hash = machete_utils.current_tab;
		$( '#machete-tabs a' ).removeClass( 'nav-tab-active' );
		$( '.nav-tab[href="' + machete_utils.current_tab + '"]' ).addClass( 'nav-tab-active' );

		$( '.machete-tabs-content:not(' + machete_utils.current_tab + ')' ).hide();
		$( machete_utils.current_tab + '.machete-tabs-content' ).show();
		// machete-tabs-header > header_content
		textarea_id = machete_utils.current_tab.replace( '#machete-tabs-', '' ) + '_content';
		// console.log('textarea_id: ' + textarea_id );
		if ( machete_utils.editor && machete_utils.editor.codemirror ) {
			machete_utils.editor.codemirror.toTextArea();
		}
		if ( textarea_id != 'tracking_content' ) {
			// la pestaña de analytics no tiene editor
			machete_utils.editor = wp.codeEditor.initialize( textarea_id );
			// machete_utils.editor.codemirror.doc.getValue();
		}
	}
	$( '.machete-tabs-content h2' ).hide();
	$( '#machete-tabs' ).show().find( 'a' ).click(
		function ( e ) {
			e.preventDefault();
			if ( $( this ).attr( 'href' ) == machete_utils.current_tab) {
				return; // clicked already active tab
			}
			machete_utils.current_tab = $( this ).attr( 'href' );
			updateTabs();
		}
	);
	updateTabs();
})( window.jQuery, window.wp );
