wp.blocks.registerBlockStyle( 'core/button', {
	name: 'icon',
	label: 'Icon'
} );

wp.domReady( function () {
	wp.blocks.unregisterBlockStyle( 'core/quote', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/quote', 'plain' );
} );