( function () {
	const { createElement, render, useState, useEffect } = wp.element;
	const { Modal, Button } = wp.components;
	const l10n = window.macheteAboutWarning || {};

	function ModuleActivateWarningModal() {
		const [ modalState, setModalState ] = useState( null );

		useEffect( function () {
			function onActivateClick( event ) {
				const link = event.target.closest(
					'a.machete-module-activate-warning'
				);
				if ( ! link ) {
					return;
				}
				event.preventDefault();
				setModalState( {
					title: link.dataset.warningTitle || '',
					message: link.dataset.warningMessage || '',
					confirmUrl: link.href,
					iconUrl: link.dataset.warningIconUrl || '',
				} );
			}

			document.addEventListener( 'click', onActivateClick );
			return function () {
				document.removeEventListener( 'click', onActivateClick );
			};
		}, [] );

		if ( ! modalState ) {
			return null;
		}

		return createElement(
			Modal,
			{
				title: modalState.title,
				onRequestClose: function () {
					setModalState( null );
				},
			},
			createElement(
				'div',
				{ className: 'machete-module-warning-modal__content' },
				modalState.iconUrl
					? createElement(
							'div',
							{ className: 'machete-module-warning-modal__icon' },
							createElement( 'img', {
								src: modalState.iconUrl,
								alt: '',
								width: 72,
								height: 72,
							} )
					  )
					: null,
				createElement(
					'p',
					{ className: 'machete-module-warning-modal__message' },
					modalState.message
				)
			),
			createElement(
				'div',
				{
					className: 'machete-module-warning-modal__actions',
				},
				createElement(
					Button,
					{
						variant: 'secondary',
						onClick: function () {
							setModalState( null );
						},
					},
					l10n.cancel || 'Cancel'
				),
				createElement(
					Button,
					{
						variant: 'primary',
						onClick: function () {
							window.location.assign( modalState.confirmUrl );
						},
					},
					l10n.confirm || 'Activate'
				)
			)
		);
	}

	const root = document.createElement( 'div' );
	root.id = 'machete-module-warning-root';
	document.body.appendChild( root );
	render( createElement( ModuleActivateWarningModal ), root );
} )();
