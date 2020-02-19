//wp.codeEditor.initialize( "header_content" );

(function($, wp){

	var existing_tabs = [];
	$('#machete-tabs a').each(function(){
		existing_tabs.push( $(this).attr('data-tab') )
	});
	console.log(existing_tabs);

	current_tab = window.current_tab || 'machete-tabs-tracking';
	
	if ( window.location.hash && ( existing_tabs.indexOf( window.location.hash.substr(1) ) > -1 ) ) {
		current_tab = window.location.hash.substr(1);
	}

	var machete_editor;

	updateTabs = function() {

		$('#machete-tabs a').each(function(){
			if ( current_tab == $(this).attr('data-tab')) {
				$(this).addClass('nav-tab-active');
			} else {
				$(this).removeClass('nav-tab-active');
			}
		});

		$('.machete-tabs-content:not(#'+current_tab+')').hide();
		$('#'+current_tab+'.machete-tabs-content').show();
		// machete-tabs-header > header_content
		textarea_id = current_tab.replace('machete-tabs-','') + '_content';
		//console.log( textarea_id );
		if ( machete_editor && machete_editor.codemirror ){
			machete_editor.codemirror.toTextArea();
		}
		if (textarea_id != 'tracking_content'){
			// la pestaña de analytics no tiene editor
			machete_editor = wp.codeEditor.initialize( textarea_id );
		}
		
		//console.log (machete_editor);
		
	}

	updateTabs();

	$('.machete-tabs-content h2').hide();

	$('#machete-tabs').show().find('a').click( function ( e ) {
		//e.preventDefault();
		current_tab = $(this).attr('data-tab');

		$('html, body').animate({
			scrollTop: $('#machete-tabs').offset().top - 120
		}, 500 );

		//console.log( current_tab );
		updateTabs();
	});

})( window.jQuery, window.wp );