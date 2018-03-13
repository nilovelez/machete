/*global jQuery*/
/**
 * Self invoking function for 'Save with keyboard' plugin.
 * @version: 2.1
 */
( function( $, undefined ) {
	'use strict';
	var $Document = $( document )
		,$Button
		,$PreviewButton
	;

	/**
	 * Initialise on document ready. Checks the DOM to see which button we can use:
	 *  - post/page/custom/media: #original_post_status[value=draft|auto-draft|publish] -> #save-action #save-post | #publishing-action #publish
	 *  - comments: body.comment-php -> #save
	 *  - theme editor: body.theme-editor-php -> #submit
	 *  - plugin editor: body.plugin-editor-php -> #submit
	 *  - profile-php: body.profile-php -> #submit
	 *  - etc...
	 */
	$( function() {
		var $Body    = $(document.body)
			,$Status = $('#original_post_status')
			,sStatus = $Status.val()
		;
		if ( $Status.length ) {
			setButton( ( 'publish' === sStatus ) ? '#publish' : '#save-post' );
		} else if ( $Body.hasClass( 'link-php' ) || $Body.hasClass( 'link-add-php' ) ) {
			setButton('#publish');
		} else if ( $Body.hasClass( 'comment-php' ) || $Body.hasClass( 'wp-customizer' ) ) {
			setButton( '#save' );
		} else if ( $Body.hasClass( 'widgets-php' ) ) {
			$Body.on( 'click', handleWidgetFocus );
		} else if ( ! setButton( '#submit' ) ) {
			setButton( 'input[name=submit]' );
		}
		setPreviewButton( '.preview.button' );
	});

	/**
	 * Tries to set the $Button variable. If the selector is valid the keydown event listener is added, otherwise it is removed.
	 * @param selector
	 * @returns {boolean} Returns true for a valid selector
	 */
	function setButton(selector){
		$Button = $(selector);
		var isButton = $Button.length!==0;
		if (isButton) {
			$Document.on('keydown',handleKeydown);
			//$Button.attr('title', 'Ctrl+S or Cmd+S to click');
			$Button.attr('title', l10n_strings.save_button_tooltip);
		} else {
			$Button = undefined;
			$Document.off('keydown',handleKeydown);
		}
		//console.log('can I haz button',$Button&&$Button.length); // log
		return isButton;
	}

	/**
	 * Handles the actual CTRL-s keydown.
	 * @param e
	 */
	function handleKeydown(e){
		if ((e.ctrlKey || e.metaKey) && (e.keyCode || e.which)===83) {
			//console.log('hi');
		
			$Button.click();
			e.preventDefault();
		}
	}


	/**
	 * Tries to set the $PreviewButton variable. If the selector is valid the keydown event listener is added, otherwise it is removed.
	 * @param selector
	 * @returns {boolean} Returns true for a valid selector
	 */

	function setPreviewButton(selector){
		$PreviewButton = $(selector);
		var isButton = $PreviewButton.length!==0;
		if (isButton) {
			$Document.on('keydown',handlepreviewKeydown);
			//$Button.attr('title', 'Ctrl+S or Cmd+S to click');
			$PreviewButton.attr('title', l10n_strings.preview_button_tooltip);
		} else {
			$PreviewButton = undefined;
			$Document.off('keydown',handlepreviewKeydown);
		}
		//console.log('can I haz button',$PreviewButton&&$PreviewButton.length); // log
		return isButton;
	}
	function handlepreviewKeydown(e){
		if ((e.ctrlKey || e.metaKey) && (e.keyCode || e.which)===80) {
			$PreviewButton.click();
			e.preventDefault();
		}
	}

	/**
	 * Checks if the focused element is a widget form input and tries to set the save-button.
	 */
	function handleWidgetFocus(){
		var $Focus = $(document.activeElement);
		setButton($Focus.is(':input')?$Focus.parents('form:first').find(':submit'):undefined);
	}
})( jQuery );
